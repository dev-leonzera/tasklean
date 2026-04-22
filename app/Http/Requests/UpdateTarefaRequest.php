<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarefaRequest extends FormRequest
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
            'descricao' => 'sometimes|string|max:1000',
            'status' => 'sometimes|in:backlog,pendente,em desenvolvimento,concluida',
            'data_vencimento' => 'sometimes|date',
            'responsavel' => 'sometimes|string|max:255|min:2',
            'projeto_id' => 'sometimes|exists:projetos,id'
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
            'descricao.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'status.in' => 'O status deve ser: backlog, pendente, em desenvolvimento ou concluida.',
            'data_vencimento.date' => 'A data de vencimento deve ser uma data válida.',
            'responsavel.min' => 'O nome do responsável deve ter pelo menos 2 caracteres.',
            'responsavel.max' => 'O nome do responsável não pode ter mais de 255 caracteres.',
            'projeto_id.exists' => 'O projeto selecionado não existe.'
        ];
    }
}
