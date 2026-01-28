<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUeRequest extends FormRequest
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
        $ueId = $this->route('ue')->id;

        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ues', 'code')->ignore($ueId),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'groupe_id' => ['nullable', 'exists:groupes,id'],
            'enseignant_id' => ['required', 'exists:users,id'],
            'chapters' => ['nullable', 'array'],
            'chapters.*' => ['required', 'string', 'max:255'],
        ];
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
