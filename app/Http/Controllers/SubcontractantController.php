<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcontractant;
use App\Http\Requests\SubcontractantRequest;
use Carbon\Carbon;

class SubcontractantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('returnUrl');

        $searchNume = trim($request->searchNume);
        $searchTelefon = trim($request->searchTelefon);

        $subcontractanti = Subcontractant::when($searchNume, function ($query, $searchNume) {
                return $query->where('nume', 'LIKE', "%{$searchNume}%");
            })
            ->when($searchTelefon, function ($query, $searchTelefon) {
                return $query->where('telefon', 'LIKE', "%{$searchTelefon}%");
            })
            ->latest()
            ->simplePaginate(25);

        return view('subcontractanti.index', compact('subcontractanti', 'searchNume', 'searchTelefon'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('subcontractanti.save');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubcontractantRequest $request)
    {
        $data = $request->validated();

        $subcontractant = Subcontractant::create($data);

        return redirect($request->session()->get('returnUrl', route('subcontractanti.index')))
            ->with('success', 'Subcontractantul <strong>' . e($subcontractant->nume) . '</strong> a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Subcontractant $subcontractant)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('subcontractanti.show', compact('subcontractant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Subcontractant $subcontractant)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('subcontractanti.save', compact('subcontractant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubcontractantRequest $request, Subcontractant $subcontractant)
    {
        $data = $request->validated();

        $subcontractant->update($data);

        return redirect($request->session()->get('returnUrl', route('subcontractanti.index')))
            ->with('status', 'Subcontractantul <strong>' . e($subcontractant->nume) . '</strong> a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Subcontractant $subcontractant)
    {
        $subcontractant->delete();

        return back()->with('status', 'Subcontractantul <strong>' . e($subcontractant->nume) . '</strong> a fost șters cu succes!');
    }
}
