<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CittadinoInsertRequest extends FormRequest
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
            "nome"=> "required|string",
            "cognome"=> "required|string",
            "codiceFiscale"=> "required|string|regex:^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$",
        ];
    }
}
