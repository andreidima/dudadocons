<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proiect;
use App\Http\Requests\ProiectRequest;
use App\Models\Membru;
use App\Models\MembruProiect;
use App\Models\Client;
use App\Models\ProiectTip;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;

class ProiectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,ProiectTip $proiectTip)
    {
        $request->session()->forget('returnUrl');

        $searchTemaDeProiectare = trim($request->searchTemaDeProiectare);
        $searchContract = trim($request->searchContract);
        $searchClient = trim($request->searchClient);

        $proiecte = Proiect::with('proiectTip', 'clienti', 'fisiere', 'membri')
            ->where('proiecte_tipuri_id', $proiectTip->id ?? null)
            ->when($searchTemaDeProiectare, function ($query, $searchTemaDeProiectare) {
                return $query->where('tema_de_proiectare', 'LIKE', "%{$searchTemaDeProiectare}%");
            })
            ->when($searchContract, function ($query, $searchContract) {
                return $query->where('contract', 'LIKE', "%{$searchContract}%");
            })
            ->when($searchClient, function ($query, $searchClient) {
                return $query->whereHas('clienti', function ($q) use ($searchClient) {
                    $q->where('nume', 'LIKE', "%{$searchClient}%");
                });
            })
            ->latest()
            ->simplePaginate(25);

        $membri = Membru::select('id','nume')->orderBy('nume')->get();

        return view('proiecte.index', compact('proiectTip', 'proiecte', 'membri', 'searchTemaDeProiectare', 'searchContract', 'searchClient'));
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
        $allClienti = Client::select('id','nume')->orderBy('nume')->get();
        $existingClienti = []; // empty array

        return view('proiecte.save', compact('proiectTip', 'allMembri', 'existingMembri', 'allClienti', 'existingClienti'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProiectRequest $request,ProiectTip $proiectTip)
    {
        // Exclude the custom members and subcontractants arrays from the general data.
        $data = $request->safe()->except(['membri', 'clienti']);
        $proiect = Proiect::create($data);

        // Process clients data: create an array suitable for sync()
        $clienti = $request->safe()->input('clienti', []);
        $syncClienti = [];
        foreach ($clienti as $client) {
            $syncClienti[$client['id']] = [
                'observatii' => (isset($client['observatii']) && trim($client['observatii']) !== '')
                ? $client['observatii']
                : null,
            ];
        }

        // Sync the relations with the pivot data (observatii)
        $proiect->clienti()->sync($syncClienti);

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


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,ProiectTip $proiectTip, Proiect $proiect)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        // Get all clients
        $allClienti = Client::select('id','nume')
            ->orderBy('nume')
            ->get();

        // Retrive existing clienti with pivot data for observatii
        $existingClienti = $proiect
            ->clienti()  // belongsToMany relationship with pivot data
            ->select('clienti.id', 'clienti.nume') // note the table name
            ->get()
            ->map(function ($client) {
                $client->observatii = $client->pivot->observatii; // add observatii from the pivot
                return $client;
            });

        return view('proiecte.save', compact(
            'proiectTip',
            'proiect',
            'allClienti',
            'existingClienti',
        ));
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(ProiectRequest $request, ProiectTip $proiectTip, Proiect $proiect)
    {
        // Exclude the new members/subcontractanti arrays from the general data.
        $data = $request->safe()->except(['clienti']);
        $proiect->update($data);

        // === STEP 2: Process clienti data from request to build a sync array ===
        $clienti = $request->safe()->input('clienti', []);
        $syncClienti = [];
        foreach ($clienti as $client) {
            $syncClienti[$client['id']] = [
                'observatii' => (isset($client['observatii']) && trim($client['observatii']) !== '')
                    ? $client['observatii']
                    : null,
            ];
        }

        // === STEP 3: Sync the relationships with the pivot data ===
        $proiect->clienti()->sync($syncClienti);

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
    public function destroy(Request $request, ProiectTip $proiectTip, Proiect $proiect)
    {
        // Check if the project has any attached files
        if ($proiect->fisiere()->exists()) {
            return redirect()->back()->with('error', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> nu poate fi șters deoarece are fișiere atașate.');
        }

        // Detach all related members and subcontractants from the pivot tables.
        $proiect->clienti()->detach();

        // Delete the project.
        $proiect->delete();

        return back()->with('status', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost șters cu succes!');
    }

    /**
     * Handle the “assign member to slot” form submit.
     */
    public function assignMember(Request $request, ProiectTip $proiectTip, Proiect $proiect)
    {
        // 1. Validate
        $data = $request->validate([
            'member_id'  => 'required|exists:membri,id',
            'tip'  => 'required|string|max:255',
            'observatii' => 'nullable|string|max:5000',
        ]);

        // 2. Create the pivot record
        MembruProiect::create([
            'membru_id'   => $data['member_id'],
            'proiect_id'  => $proiect->id,
            'tip'   => $data['tip'],
            'observatii'  => $data['observatii'],
        ]);

        // 3. Redirect back with a flash message
        return redirect()
            ->back()
            ->with('success', "Membru adăugat cu succes la “{$data['tip']}”.");
    }

    /**
     * Update an existing project–member assignment.
     */
    public function membruModifica(
        Request $request,
        ProiectTip $proiectTip,
        Proiect $proiect,
        $pivotMembruProiectId
    ) {
        // 1. Validate input
        $data = $request->validate([
            'member_id'  => 'required|exists:membri,id',
            'tip'        => 'required|string|max:255',
            'observatii' => 'nullable|string|max:5000',
        ]);

        // 2. Fetch the existing pivot row (scoped to this project)
        $assignment = MembruProiect::where('id', $pivotMembruProiectId)
            ->where('proiect_id', $proiect->id)
            ->firstOrFail();

        // 3. Update it
        $assignment->update([
            'membru_id'   => $data['member_id'],
            'tip'         => $data['tip'],
            'observatii'  => $data['observatii'] ?? null,
        ]);

        // 4. Redirect with success
        return redirect()
            ->back()
            ->with('success', "Membru actualizat cu succes la “{$data['tip']}”.");
    }

    /**
     * Delete a project–member assignment.
     */
    public function membruSterge(
        ProiectTip $proiectTip,
        Proiect $proiect,
        $pivotMembruProiectId
    ) {
        // 1. Fetch the assignment
        $assignment = MembruProiect::where('id', $pivotMembruProiectId)
            ->where('proiect_id', $proiect->id)
            ->firstOrFail();

        // 2. Delete it
        $assignment->delete();

        // 3. Redirect with success
        return redirect()
            ->back()
            ->with('success', 'Membru șters cu succes.');
    }

}
