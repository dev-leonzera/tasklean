<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjetoRequest extends FormRequest
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
            'titulo' => 'sometimes|string|max:255|min:3',
            'responsavel' => 'sometimes|string|max:255|min:2',
            'ativo' => 'sometimes|boolean'
        ];
    }

    /**
     * Mensagens de validação personalizadas
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titulo.min' => 'O título deve ter pelo menos 3 caracteres.',
            'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
            'responsavel.min' => 'O nome do responsável deve ter pelo menos 2 caracteres.',
            'responsavel.max' => 'O nome do responsável não pode ter mais de 255 caracteres.',
            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso.'
        ];
    }
}
