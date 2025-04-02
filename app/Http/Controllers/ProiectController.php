<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proiect;
use App\Http\Requests\ProiectRequest;
use App\Models\Membru;
use App\Models\Subcontractant;
use App\Models\ProiectTip;
use App\Models\ProiectEmailTrimis;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\MemberAddedToProject;
use App\Mail\MemberRemovedFromProject;
use App\Mail\ProjectComentariiUpdated;

class ProiectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,ProiectTip $proiectTip)
    {
        $request->session()->forget('returnUrl');

        $searchDenumire = trim($request->searchDenumire);
        $searchNrContract = trim($request->searchNrContract);
        $searchIntervalDataContract = trim($request->searchIntervalDataContract);
        $searchMembru = trim($request->searchMembru);
        $searchSubcontractant = trim($request->searchSubcontractant);

        $proiecte = Proiect::with('proiectTip', 'membri', 'subcontractanti', 'fisiere', 'emailuriTrimise')
            ->where('proiecte_tipuri_id', $proiectTip->id ?? null)
            ->when($searchDenumire, function ($query, $searchDenumire) {
                return $query->where('denumire_contract', 'LIKE', "%{$searchDenumire}%");
            })
            ->when($searchNrContract, function ($query, $searchNrContract) {
                return $query->where('nr_contract', 'LIKE', "%{$searchNrContract}%");
            })
            ->when($searchIntervalDataContract, function ($query, $searchIntervalDataContract) {
                $dates = explode(',', $searchIntervalDataContract);
                return $query->whereBetween('data_contract', [$dates[0] ?? null, $dates[1] ?? null]);
            })
            ->when($searchMembru, function ($query, $searchMembru) {
                return $query->whereHas('membri', function ($q) use ($searchMembru) {
                    $q->where('nume', 'LIKE', "%{$searchMembru}%");
                });
            })
            ->when($searchSubcontractant, function ($query, $searchSubcontractant) {
                return $query->whereHas('subcontractanti', function ($q) use ($searchSubcontractant) {
                    $q->where('nume', 'LIKE', "%{$searchSubcontractant}%");
                });
            })
            ->orderByRaw("
                CASE
                    WHEN data_proces_verbal_predare_primire IS NULL THEN 0 ELSE 1
                END ASC, -- Active projects first, closed ones last
                CASE
                    WHEN data_limita_predare IS NOT NULL AND data_limita_predare < NOW()
                        THEN 0 -- Overdue projects first
                    WHEN data_limita_predare IS NOT NULL AND data_limita_predare >= NOW()
                        THEN 1 -- Upcoming deadlines next
                    ELSE 2 -- NULL deadlines last
                END ASC,
                data_limita_predare ASC -- Sort overdue by most days overdue, upcoming by closest deadline
            ")
            ->simplePaginate(25);

        return view('proiecte.index', compact('proiectTip', 'proiecte', 'searchDenumire', 'searchNrContract', 'searchIntervalDataContract', 'searchMembru', 'searchSubcontractant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,ProiectTip  $proiectTip)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        // Get all membri (only the necessary fields)
        $allMembri = Membru::select('id' ,'nume')
            ->orderBy('nume')
            ->get();
        $existingMembri = []; // empty array

        // Get all subcontractanti (only the necessary fields)
        $allSubcontractanti = Subcontractant::select('id','nume')->orderBy('nume')->get();
        $existingSubcontractanti = []; // empty array

        return view('proiecte.save', compact('proiectTip', 'allMembri', 'existingMembri', 'allSubcontractanti', 'existingSubcontractanti'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProiectRequest $request,ProiectTip $proiectTip)
    {
        // Exclude the custom members and subcontractants arrays from the general data.
        $data = $request->safe()->except(['membri', 'subcontractanti']);
        $proiect = Proiect::create($data);

        // Process members data: create an array suitable for sync()
        $membri = $request->safe()->input('membri', []);
        $syncMembri = [];
        foreach ($membri as $membru) {
            $syncMembri[$membru['id']] = [
                'observatii' => (isset($membru['observatii']) && trim($membru['observatii']) !== '')
                ? $membru['observatii']
                : null,
            ];
        }

        // Process subcontractants data: create an array suitable for sync()
        $subcontractanti = $request->safe()->input('subcontractanti', []);
        $syncSubcontractanti = [];
        foreach ($subcontractanti as $subcontractant) {
            $syncSubcontractanti[$subcontractant['id']] = [
                'observatii' => (isset($subcontractant['observatii']) && trim($subcontractant['observatii']) !== '')
                    ? $subcontractant['observatii']
                    : null,
            ];
        }

        // Sync the relations with the pivot data (observatii)
        $proiect->membri()->sync($syncMembri);
        $proiect->subcontractanti()->sync($syncSubcontractanti);


        // === Email Notification for New Members ===
        $errors = [];

        // Build recipient array from the $membri array (all are new, since this is a new project)
        $addedRecipients = [];
        foreach ($membri as $item) {
            $member = Membru::find($item['id']);
            if ($member && filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
                $addedRecipients[] = [
                    'id'      => $member->id,
                    'email'   => $member->email,
                    'nume'    => $member->nume,
                ];
            } else {
                $errorMessage = $member
                    ? "Emailul membrului {$member->nume} este invalid, motiv pentru care nu i s-a putut trimite email."
                    : "Email invalid pentru un membru necunoscut.";
                $errors[] = $errorMessage;
                // Log the invalid email
                ProiectEmailTrimis::create([
                    'proiect_id'       => $proiect->id,
                    'destinatar_id'    => $member ? $member->id : 0,
                    'destinatar_type'  => 'membru',
                    'email_destinatar' => $member ? $member->email : 'unknown',
                    'email_subiect'    => "Aplicatie Alma Consulting - Ai fost adăugat la proiectul " . Str::limit($proiect->denumire_contract, 50),
                    'email_mesaj'      => $errorMessage,
                    'sent_at'          => now(),
                    'error_code'       => "INVALID_EMAIL",
                    'error_message'    => $errorMessage,
                ]);
            }
        }

        // Send bulk email only if we have valid recipient emails
        if (!empty($addedRecipients)) {
            $emailsForBulk = array_column($addedRecipients, 'email');
            try {
                $mail = new MemberAddedToProject($proiect);
                $mail->subject = "Aplicatie Alma Consulting - Ai fost adăugat la proiectul " . Str::limit($proiect->denumire_contract, 50);
                Mail::to($emailsForBulk)->send($mail);
                // Log success for each recipient.
                foreach ($addedRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Ai fost adăugat la proiect.',
                        'sent_at'          => now(),
                        'error_code'       => null,
                        'error_message'    => null,
                    ]);
                }
            } catch (\Exception $e) {
                // Log error for each recipient if bulk sending fails.
                foreach ($addedRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Ai fost adăugat la proiect.',
                        'sent_at'          => now(),
                        'error_code'       => $e->getCode(),
                        'error_message'    => $e->getMessage(),
                    ]);
                }
                $errors[] = "Nu s-a putut trimite emailul pentru adăugare: " . $e->getMessage();
            }
        }

        // Optionally, flash errors to the session so that the operator is informed.
        if (!empty($errors)) {
            session()->flash('email_errors', $errors);
        }

        return redirect($request->session()->get('returnUrl', route('proiecte.index', $proiectTip)))
            ->with('success', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,ProiectTip $proiectTip, Proiect $proiect)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('proiecte.show', compact('proiectTip', 'proiect'));
    }

    public function showEmailuri(Request $request,ProiectTip $proiectTip, $proiect, $destinatar_type, $destinatar_id)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        // Load the project along with its sent emails
        $proiect = Proiect::with('emailuriTrimise')->findOrFail($proiect);

        // Filter emails for this specific destinatar within the project
        $emailuri = $proiect->emailuriTrimise()
            ->where('destinatar_id', $destinatar_id)
            ->where('destinatar_type', $destinatar_type)
            ->orderBy('sent_at', 'desc')
            ->get();

        return view('proiecte.showEmailuri', compact('proiectTip', 'proiect', 'emailuri', 'destinatar_type', 'destinatar_id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,ProiectTip $proiectTip, Proiect $proiect)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        // Get all membri
        $allMembri = Membru::select('id','nume')
            ->orderBy('nume')
            ->get();

        // Retrive existing membri with pivot data for observatii
        $existingMembri = $proiect
            ->membri()  // belongsToMany relationship with pivot data
            ->select('membri.id', 'membri.nume') // note the table name
            ->get()
            ->map(function ($membru) {
                $membru->observatii = $membru->pivot->observatii; // add observatii from the pivot
                return $membru;
            });

        // Get all subcontractanti
        $allSubcontractanti = Subcontractant::select('id','nume')
            ->orderBy('nume')
            ->get();

        // Retrieve existing subcontractanti with pivot data for observatii
        $existingSubcontractanti = $proiect
            ->subcontractanti()  // belongsToMany relationship with pivot data
            ->select('subcontractanti.id', 'subcontractanti.nume') // note the table name
            ->get()
            ->map(function ($subcontractant) {
                $subcontractant->observatii = $subcontractant->pivot->observatii; // add observatii from the pivot
                return $subcontractant;
            });

        return view('proiecte.save', compact(
            'proiectTip',
            'proiect',
            'allMembri',
            'existingMembri',
            'allSubcontractanti',
            'existingSubcontractanti'
        ));
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(ProiectRequest $request, ProiectTip $proiectTip, Proiect $proiect)
    {
        // Exclude the new members/subcontractanti arrays from the general data.
        $data = $request->safe()->except(['membri', 'subcontractanti']);
        $proiect->update($data);

        // === STEP 1: Capture the old member IDs BEFORE syncing ===
        $oldMemberIds = $proiect->membri()->pluck('membri.id')->toArray();

        // === STEP 2: Process membri data from request to build a sync array ===
        $membri = $request->safe()->input('membri', []);
        $syncMembri = [];
        foreach ($membri as $membru) {
            $syncMembri[$membru['id']] = [
                'observatii' => (isset($membru['observatii']) && trim($membru['observatii']) !== '')
                    ? $membru['observatii']
                    : null,
            ];
        }
        // Build an array of new member IDs for comparison.
        $newMemberIds = array_map(fn($item) => $item['id'], $membri);

        // === STEP 3: Process subcontractanti data ===
        $subcontractanti = $request->safe()->input('subcontractanti', []);
        $syncSubcontractanti = [];
        foreach ($subcontractanti as $subcontractant) {
            $syncSubcontractanti[$subcontractant['id']] = [
                'observatii' => (isset($subcontractant['observatii']) && trim($subcontractant['observatii']) !== '')
                    ? $subcontractant['observatii']
                    : null,
            ];
        }

        // === STEP 4: Determine which members were added and which removed ===
        $addedMemberIds = array_diff($newMemberIds, $oldMemberIds);
        $removedMemberIds = array_diff($oldMemberIds, $newMemberIds);

        // === STEP 5: Sync the relationships with the pivot data ===
        $proiect->membri()->sync($syncMembri);
        $proiect->subcontractanti()->sync($syncSubcontractanti);

        // === STEP 6: Prepare to send email notifications and log them ===
        $errors = [];

        /*
        * Build recipient arrays.
        * Each recipient is stored as an associative array with keys:
        * 'id', 'email', and 'nume'
        */

        // --- Prepare recipients for added members ---
        $addedRecipients = [];
        foreach ($addedMemberIds as $memberId) {
            $member = Membru::find($memberId);
            if ($member && filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
                $addedRecipients[] = [
                    'id'      => $member->id,
                    'email'   => $member->email,
                    'nume'    => $member->nume,
                ];
            } else {
                // Create a friendly error message using the member's name.
                $errorMessage = $member
                    ? "Emailul membrului {$member->nume} este invalid, motiv pentru care nu i s-a putut trimite email."
                    : "Email invalid pentru un membru necunoscut.";
                $errors[] = $errorMessage;
                // Log the invalid email as well.
                ProiectEmailTrimis::create([
                    'proiect_id'       => $proiect->id,
                    'destinatar_id'    => $member ? $member->id : 0,
                    'destinatar_type'  => 'membru',
                    'email_destinatar' => $member ? $member->email : 'unknown',
                    'email_subiect'    => "Aplicatie Alma Consulting - Ai fost adăugat la proiectul " . $proiect->denumire_contract,
                    'email_mesaj'      => $errorMessage,
                    'sent_at'          => now(),
                    'error_code'       => "INVALID_EMAIL",
                    'error_message'    => $errorMessage,
                ]);
            }
        }

        // --- Prepare recipients for removed members ---
        $removedRecipients = [];
        foreach ($removedMemberIds as $memberId) {
            $member = Membru::find($memberId);
            if ($member && filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
                $removedRecipients[] = [
                    'id'      => $member->id,
                    'email'   => $member->email,
                    'nume'    => $member->nume,
                ];
            } else {
                $errorMessage = $member
                    ? "Emailul membrului {$member->nume} este invalid, motiv pentru care nu i s-a putut trimite email."
                    : "Email invalid pentru un membru necunoscut.";
                $errors[] = $errorMessage;
                ProiectEmailTrimis::create([
                    'proiect_id'       => $proiect->id,
                    'destinatar_id'    => $member ? $member->id : 0,
                    'destinatar_type'  => 'membru',
                    'email_destinatar' => $member ? $member->email : 'unknown',
                    'email_subiect'    => "Aplicatie Alma Consulting - Ai fost eliminat din proiectul " . $proiect->denumire_contract,
                    'email_mesaj'      => $errorMessage,
                    'sent_at'          => now(),
                    'error_code'       => "INVALID_EMAIL",
                    'error_message'    => $errorMessage,
                ]);
            }
        }

        // --- Prepare recipients for comentarii changes, excluding newly added members --- ---
        $commentaryRecipients = [];
        if ($proiect->wasChanged('comentarii')) {
            foreach ($proiect->membri as $member) {
                // Skip newly added members
                if (in_array($member->id, $addedMemberIds)) {
                    continue;
                }
                if ($member && filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
                    $commentaryRecipients[] = [
                        'id'      => $member->id,
                        'email'   => $member->email,
                        'nume'    => $member->nume,
                    ];
                } else {
                    $errorMessage = $member
                        ? "Emailul membrului {$member->nume} este invalid, motiv pentru care nu i s-a putut trimite email."
                        : "Email invalid pentru un membru necunoscut.";
                    $errors[] = $errorMessage;
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $member ? $member->id : 0,
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $member ? $member->email : 'unknown',
                        'email_subiect'    => "Aplicatie Alma Consulting - Comentariile au fost actualizate pentru proiectul " . $proiect->denumire_contract,
                        'email_mesaj'      => $errorMessage,
                        'sent_at'          => now(),
                        'error_code'       => "INVALID_EMAIL",
                        'error_message'    => $errorMessage,
                    ]);
                }
            }
        }

        /*
        * Now send one bulk email per event.
        * For each event, if the bulk email sending fails,
        * we catch the exception and log the error for each recipient.
        */

        // --- Send one email for added members ---
        if (!empty($addedRecipients)) {
            $emailsForBulk = array_column($addedRecipients, 'email');
            try {
                $mail = new MemberAddedToProject($proiect);
                $mail->subject = "Aplicatie Alma Consulting - Ai fost adăugat la proiectul " . Str::limit($proiect->denumire_contract, 50);
                Mail::to($emailsForBulk)->send($mail);
                // Log success for each recipient.
                foreach ($addedRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Ai fost adăugat la proiect.',
                        'sent_at'          => now(),
                        'error_code'       => null,
                        'error_message'    => null,
                    ]);
                }
            } catch (\Exception $e) {
                foreach ($addedRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Ai fost adăugat la proiect.',
                        'sent_at'          => now(),
                        'error_code'       => $e->getCode(),
                        'error_message'    => $e->getMessage(),
                    ]);
                }
                $errors[] = "Nu s-a putut trimite emailul pentru adăugare: " . $e->getMessage();
            }
        }

        // --- Send one email for removed members ---
        if (!empty($removedRecipients)) {
            try {
                $mail = new MemberRemovedFromProject($proiect, null);
                $mail->subject = "Aplicatie Alma Consulting - Ai fost eliminat din proiectul " . Str::limit($proiect->denumire_contract, 50);
                $emailsForBulk = array_column($removedRecipients, 'email');
                Mail::to($emailsForBulk)->send($mail);
                foreach ($removedRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Ai fost eliminat din proiect.',
                        'sent_at'          => now(),
                        'error_code'       => null,
                        'error_message'    => null,
                    ]);
                }
            } catch (\Exception $e) {
                foreach ($removedRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Ai fost eliminat din proiect.',
                        'sent_at'          => now(),
                        'error_code'       => $e->getCode(),
                        'error_message'    => $e->getMessage(),
                    ]);
                }
                $errors[] = "Nu s-a putut trimite emailul pentru eliminare: " . $e->getMessage();
            }
        }

        // --- Send one email for comentarii changes ---
        if ($proiect->wasChanged('comentarii') && !empty($commentaryRecipients)) {
            try {
                $mail = new ProjectComentariiUpdated($proiect);
                $mail->subject = "Aplicatie Alma Consulting - Comentariile au fost actualizate pentru proiectul " . Str::limit($proiect->denumire_contract, 50);
                $emailsForBulk = array_column($commentaryRecipients, 'email');
                Mail::to($emailsForBulk)->send($mail);
                foreach ($commentaryRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Comentariile proiectului au fost actualizate.',
                        'sent_at'          => now(),
                        'error_code'       => null,
                        'error_message'    => null,
                    ]);
                }
            } catch (\Exception $e) {
                foreach ($commentaryRecipients as $recipient) {
                    ProiectEmailTrimis::create([
                        'proiect_id'       => $proiect->id,
                        'destinatar_id'    => $recipient['id'],
                        'destinatar_type'  => 'membru',
                        'email_destinatar' => $recipient['email'],
                        'email_subiect'    => $mail->subject,
                        'email_mesaj'      => 'Notificare: Comentariile proiectului au fost actualizate.',
                        'sent_at'          => now(),
                        'error_code'       => $e->getCode(),
                        'error_message'    => $e->getMessage(),
                    ]);
                }
                $errors[] = "Nu s-a putut trimite emailul pentru comentarii: " . $e->getMessage();
            }
        }

        // Optionally, flash errors to the session so they can be displayed in your Blade view.
        if (!empty($errors)) {
            session()->flash('email_errors', $errors);
        }

        return redirect($request->session()->get('returnUrl', route('proiecte.index', $proiectTip)))
            ->with('status', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,ProiectTip $proiectTip, Proiect $proiect)
    {
        // Check if the project has any attached files
        if ($proiect->fisiere()->exists()) {
            return redirect()->back()->with('error', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> nu poate fi șters deoarece are fișiere atașate.');
        }

        // Delete all email logs associated with the project.
        $proiect->emailuriTrimise()->delete();

        // Detach all related members and subcontractants from the pivot tables.
        $proiect->membri()->detach();
        $proiect->subcontractanti()->detach();

        // Delete the project.
        $proiect->delete();

        return back()->with('status', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost șters cu succes!');
    }
}
