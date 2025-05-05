<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Membru;
use App\Http\Requests\MembruRequest;
use Carbon\Carbon;

class MembruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('returnUrl');

        $searchNume = trim($request->searchNume); // Name search field
        $searchTelefon = trim($request->searchTelefon); // Phone search field

        $membri = Membru::when($searchNume, function ($query, $searchNume) {
                return $query->where('nume', 'LIKE', "%{$searchNume}%");
            })
            ->when($searchTelefon, function ($query, $searchTelefon) {
                return $query->where('telefon', 'LIKE', "%{$searchTelefon}%");
            })
            ->latest()
            ->simplePaginate(25);

        return view('membri.index', compact('membri', 'searchNume', 'searchTelefon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('membri.save')->with([
            'preFilledFields' => $request->all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MembruRequest $request)
    {
        $data = $request->validated();

        $membru = Membru::create($data);

        return redirect($request->session()->get('returnUrl', route('membri.index')))->with('success', 'Membrul <strong>' . e($membru->nume) . '</strong> a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Membru  $membru
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Membru $membru)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('membri.show', compact('membru'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Membru  $membru
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Membru $membru)
    {
        $request->session()->get('returnUrl') ?: $request->session()->put('returnUrl', url()->previous());

        return view('membri.save', compact('membru'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Membru  $membru
     * @return \Illuminate\Http\Response
     */
    public function update(MembruRequest $request, Membru $membru)
    {
        $data = $request->validated();

        $membru->update($data);

        return redirect($request->session()->get('returnUrl', route('membri.index')))->with('status', 'Membrul <strong>' . e($membru->nume) . '</strong> a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Membru  $membru
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Membru $membru)
    {
        if ($membru->proiecte()->exists()) {
            return back()->with('error', 'Membrul nu poate fi șters deoarece este asociat cu unul sau mai multe proiecte.');
        }

        $membru->delete();

        return back()->with('status', 'Membrul <strong>' . e($membru->nume) . '</strong> a fost șters cu succes!');
    }
}
