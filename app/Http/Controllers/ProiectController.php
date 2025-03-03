<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proiect;
use App\Http\Requests\ProiectRequest;
use App\Models\Membru;
use App\Models\Subcontractant;
use App\Models\ProiectTip;

class ProiectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $tipProiect)
    {
        $request->session()->forget('returnUrl');

        $searchDenumire = trim($request->searchDenumire);
        $searchNrContract = trim($request->searchNrContract);
        $searchIntervalDataContract = trim($request->searchIntervalDataContract);
        $searchMembru = trim($request->searchMembru);
        $searchSubcontractant = trim($request->searchSubcontractant);

        $proiecte = Proiect::with('tipProiect', 'membri', 'subcontractanti', 'fisiere', 'emailuriTrimise')
            ->where('proiecte_tipuri_id', ProiectTip::where('slug', $tipProiect)->first()->id ?? null)
            ->when($searchDenumire, function ($query, $searchDenumire) {
                $words = explode(' ', $searchDenumire);
                return $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->orWhere('denumire_contract', 'LIKE', "%{$word}%");
                    }
                });
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
                    $q->whereRaw("CONCAT(nume, ' ', prenume) LIKE ?", ["%{$searchMembru}%"])
                    ->orWhereRaw("CONCAT(prenume, ' ', nume) LIKE ?", ["%{$searchMembru}%"]);
                });
            })
            ->when($searchSubcontractant, function ($query, $searchSubcontractant) {
                return $query->whereHas('subcontractanti', function ($q) use ($searchSubcontractant) {
                    $q->where('nume', 'LIKE', "%{$searchSubcontractant}%");
                });
            })

            ->latest()
            ->simplePaginate(25);

        return view('proiecte.index', compact('tipProiect', 'proiecte', 'searchDenumire', 'searchNrContract', 'searchIntervalDataContract', 'searchMembru', 'searchSubcontractant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $tipProiect)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        // Get all membri (only the necessary fields)
        $allMembri = Membru::select('id','prenume','nume')->get()
            ->map(function ($membru) {
                $membru->full_name = $membru->prenume . ' ' . $membru->nume;
                return $membru;
            });
        $existingMembri = []; // empty array

        // Get all subcontractanti (only the necessary fields)
        $allSubcontractanti = Subcontractant::select('id','nume')->get();
        $existingSubcontractanti = []; // empty array

        return view('proiecte.save', compact('tipProiect', 'allMembri', 'existingMembri', 'allSubcontractanti', 'existingSubcontractanti'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProiectRequest $request, $tipProiect)
    {
        $data = $request->safe()->except(['membri_ids', 'subcontractanti_ids']);
        $proiect = Proiect::create($data);

        // Extract the IDs as plain arrays:
        $membriIds = $request->safe()->only('membri_ids')['membri_ids'] ?? [];
        $subcontractantiIds = $request->safe()->only('subcontractanti_ids')['subcontractanti_ids'] ?? [];

        $proiect->membri()->sync($membriIds);
        $proiect->subcontractanti()->sync($subcontractantiIds);

        return redirect($request->session()->get('returnUrl', route('proiecte.index', $tipProiect)))
            ->with('success', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $tipProiect, Proiect $proiect)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('proiecte.show', compact('tipProiect', 'proiect'));
    }

    public function showEmailuri(Request $request, $tipProiect, $proiect, $destinatar_type, $destinatar_id)
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

        return view('proiecte.showEmailuri', compact('tipProiect', 'proiect', 'emailuri', 'destinatar_type', 'destinatar_id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $tipProiect, Proiect $proiect)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        // Get all membri
        $allMembri = Membru::select('id','prenume','nume')->get()
            ->map(function ($membru) {
                $membru->full_name = $membru->prenume . ' ' . $membru->nume;
                return $membru;
            });
        $existingMembri = $proiect
            ->membri()  // belongsToMany relationship
            ->select('membri.id', 'membri.prenume', 'membri.nume') // note the table name
            ->get()
            ->map(function ($membru) {
                $membru->full_name = $membru->prenume . ' ' . $membru->nume;
                return $membru;
            });

        // Get all subcontractanti
        $allSubcontractanti = Subcontractant::select('id','nume')->get();
        $existingSubcontractanti = $proiect
            ->subcontractanti()  // belongsToMany relationship
            ->select('subcontractanti.id', 'subcontractanti.nume') // note the table name
            ->get();

        return view('proiecte.save', compact('tipProiect', 'proiect', 'allMembri', 'existingMembri', 'allSubcontractanti', 'existingSubcontractanti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProiectRequest $request, $tipProiect, Proiect $proiect)
    {
        $data = $request->safe()->except(['membri_ids', 'subcontractanti_ids']);
        $proiect->update($data);

        // Extract the IDs as plain arrays:
        $membriIds = $request->safe()->only('membri_ids')['membri_ids'] ?? [];
        $subcontractantiIds = $request->safe()->only('subcontractanti_ids')['subcontractanti_ids'] ?? [];

        $proiect->membri()->sync($membriIds);
        $proiect->subcontractanti()->sync($subcontractantiIds);

        return redirect($request->session()->get('returnUrl', route('proiecte.index', $tipProiect)))
            ->with('status', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $tipProiect, Proiect $proiect)
    {
        // Check if the project has any attached files
        if ($proiect->fisiere()->exists()) {
            return redirect()->back()->with('error', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> nu poate fi șters deoarece are fișiere atașate.');
        }

        // Detach all related members and subcontractants from the pivot tables.
        $proiect->membri()->detach();
        $proiect->subcontractanti()->detach();

        // Delete the project.
        $proiect->delete();

        return back()->with('status', 'Proiectul <strong>' . e($proiect->denumire_contract) . '</strong> a fost șters cu succes!');
    }
}
