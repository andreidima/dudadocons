<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'nume' => 'required|string|max:255',
            'tip' => 'nullable|string|max:100',
            'numar_inregistrare' => 'nullable|string|max:100',
            'cod_fiscal' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'telefon' => 'nullable|string|max:50',
            'adresa' => 'nullable|string|max:500',
            'oras' => 'nullable|string|max:100',
            'cod_postal' => 'nullable|string|max:50',
            'tara' => 'nullable|string|max:100',
            'data_inceput_contract' => 'nullable|date',
            'data_sfarsit_contract' => 'nullable|date',
            'tarif_orar' => 'nullable|numeric|between:0,999999.99',
            'pret_fix' => 'nullable|numeric|between:0,999999.99',
            'moneda' => 'nullable|string|max:10',
            'conditii_plata' => 'nullable|string|max:100',
            'specializare' => 'nullable|string|max:255',
            'observatii' => 'nullable|string|max:5000',
        ];
    }
}
