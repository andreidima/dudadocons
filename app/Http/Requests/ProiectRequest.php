<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\ProiectTip;

class ProiectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Return true if the user is allowed to make this request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'proiecte_tipuri_id' => 'required|integer',
            'tema_de_proiectare' => 'required|string|max:255',
            'contract' => 'nullable|string|max:255',
            'oferta' => 'nullable|string|max:255',
            'plati' => 'nullable|string|max:255',

            'obtinere_certificat_urbanism' => 'nullable|string|max:255',
            'dtoe' => 'nullable|string|max:255',
            'certificat_de_urbanism' => 'nullable|string|max:255',
            'alimentare_cu_apa' => 'nullable|string|max:255',
            'canalizare' => 'nullable|string|max:255',
            'transport_urban' => 'nullable|string|max:255',
            'nomenclator' => 'nullable|string|max:255',
            'alimentare_cu_energie_electrica' => 'nullable|string|max:255',
            'gaze_naturale' => 'nullable|string|max:255',
            'telefonizare' => 'nullable|string|max:255',
            'salubritate' => 'nullable|string|max:255',
            'alimentare_cu_energie_termica' => 'nullable|string|max:255',
            'isu' => 'nullable|string|max:255',
            'dsp' => 'nullable|string|max:255',
            'dsv' => 'nullable|string|max:255',
            'anif' => 'nullable|string|max:255',
            'ospa' => 'nullable|string|max:255',
            'apia' => 'nullable|string|max:255',
            'daj' => 'nullable|string|max:255',
            'mediu' => 'nullable|string|max:255',
            'arie_protejata' => 'nullable|string|max:255',
            'studiu_geotehnic' => 'nullable|string|max:255',
            'studiu_topo' => 'nullable|string|max:255',
            'verificare_tehnica_structura' => 'nullable|string|max:255',
            'verificare_tehnica_instalatii' => 'nullable|string|max:255',
            'dovada_oar' => 'nullable|string|max:255',
            'ipj' => 'nullable|string|max:255',
            'cj' => 'nullable|string|max:255',
            'conpet' => 'nullable|string|max:255',
            'cfr' => 'nullable|string|max:255',
            'sga' => 'nullable|string|max:255',
            'nzeb' => 'nullable|string|max:255',
            'saer' => 'nullable|string|max:255',
            'expertiza_tehnica' => 'nullable|string|max:255',
            'notare_ac_la_ocpi' => 'nullable|string|max:255',
            'comunicare_incepere_lucrari_ziar' => 'nullable|string|max:255',
            'comunicare_incepere_lucrari_isc' => 'nullable|string|max:255',
            'comunicare_incheiere_lucrari_primarie' => 'nullable|string|max:255',
            'dirigentie_santier' => 'nullable|string|max:255',
            'receptie_depunere_documentatie' => 'nullable|string|max:255',
            'edificare_depunere_documentatie' => 'nullable|string|max:255',
            'inscriere_constructie' => 'nullable|string|max:255',

            // Add this rule for clienti
            'clienti' => 'nullable|array',
            'clienti.*.id' => 'required|exists:clienti,id', // ensure each ID exists in 'clienti' table
            'clienti.*.observatii' => 'nullable|string|max:5000',
        ];
    }

    protected function prepareForValidation()
    {
        $proiectTip = $this->route('proiectTip');
        $this->merge([
            'proiecte_tipuri_id' => $proiectTip->id ?? null,
        ]);
    }
}
