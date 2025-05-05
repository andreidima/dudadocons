<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\ClientRequest;
use Carbon\Carbon;

class ClientController extends Controller
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

        $clienti = Client::when($searchNume, function ($query, $searchNume) {
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
    public function store(ClientRequest $request)
    {
        $data = $request->validated();

        $client = Client::create($data);

        return redirect($request->session()->get('returnUrl', route('clienti.index')))
            ->with('success', 'Clientul <strong>' . e($client->nume) . '</strong> a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Client $client)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('clienti.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Client $client)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('clienti.save', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $data = $request->validated();

        $client->update($data);

        return redirect($request->session()->get('returnUrl', route('clienti.index')))
            ->with('status', 'Clientul <strong>' . e($client->nume) . '</strong> a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Client $client)
    {
        $client->delete();

        return back()->with('status', 'Clientul <strong>' . e($client->nume) . '</strong> a fost șters cu succes!');
    }
}
