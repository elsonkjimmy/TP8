<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:ues,code'],
            'nom' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'groupe_id' => ['nullable', 'exists:groupes,id'],
            'enseignant_id' => ['required', 'exists:users,id'],
            'chapters' => ['nullable', 'array'],
            'chapters.*' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Le code de l\'UE est requis.',
            'code.unique' => 'Ce code d\'UE existe déjà.',
            'nom.required' => 'Le nom de l\'UE est requis.',
            'filiere_id.required' => 'La filière est requise.',
            'filiere_id.exists' => 'La filière sélectionnée n\'existe pas.',
            'groupe_id.exists' => 'Le groupe sélectionné n\'existe pas.',
            'enseignant_id.required' => 'L\'enseignant responsable est requis.',
            'enseignant_id.exists' => 'L\'enseignant sélectionné n\'existe pas.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Si un groupe est sélectionné, vérifier qu'il appartient à la même filière
        if ($this->groupe_id) {
            $groupe = \App\Models\Groupe::find($this->groupe_id);
            if ($groupe && $groupe->filiere_id != $this->filiere_id) {
                // Invalider le champ pour déclencher une erreur
                $this->merge([
                    'groupe_filiere_mismatch' => true
                ]);
            }
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        if ($this->groupe_id && \App\Models\Groupe::find($this->groupe_id)?->filiere_id != $this->filiere_id) {
            $validator->errors()->add('groupe_id', 'Le groupe sélectionné n\'appartient pas à la filière choisie.');
        }

        parent::failedValidation($validator);
    }
}
