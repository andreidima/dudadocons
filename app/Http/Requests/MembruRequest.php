<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembruRequest extends FormRequest
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
            'nume' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'telefon' => 'nullable|string|max:100',
            'adresa' => 'nullable|string|max:500',
            'functie' => 'nullable|string|max:100',
            'departament' => 'nullable|string|max:100',
            'observatii' => 'nullable|string|max:5000',
        ];
    }
}
