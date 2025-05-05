<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CLient;
use App\Http\Requests\CLientRequest;
use Carbon\Carbon;

class CLientController extends Controller
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

        $clienti = CLient::when($searchNume, function ($query, $searchNume) {
                return $query->where('nume', 'LIKE', "%{$searchNume}%");
            })
            ->when($searchTelefon, function ($query, $searchTelefon) {
                return $query->where('telefon', 'LIKE', "%{$searchTelefon}%");
            })
            ->latest()
            ->simplePaginate(25);

        return view('clienti.index', compact('clienti', 'searchNume', 'searchTelefon'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('clienti.save');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CLientRequest $request)
    {
        $data = $request->validated();

        $client = CLient::create($data);

        return redirect($request->session()->get('returnUrl', route('clienti.index')))
            ->with('success', 'CLientul <strong>' . e($client->nume) . '</strong> a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, CLient $client)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('clienti.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, CLient $client)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('clienti.save', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CLientRequest $request, CLient $client)
    {
        $data = $request->validated();

        $client->update($data);

        return redirect($request->session()->get('returnUrl', route('clienti.index')))
            ->with('status', 'CLientul <strong>' . e($client->nume) . '</strong> a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CLient $client)
    {
        $client->delete();

        return back()->with('status', 'CLientul <strong>' . e($client->nume) . '</strong> a fost șters cu succes!');
    }
}
