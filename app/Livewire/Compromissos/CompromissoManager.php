<?php

namespace App\Livewire\Compromissos;

use App\Models\Compromisso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CompromissoManager extends Component
{
    use WithPagination;

    // Propriedades para filtros
    public $filtro_status = '';
    public $filtro_tipo = '';
    public $filtro_prioridade = '';
    public $filtro_data_inicio = '';
    public $filtro_data_fim = '';
    public $busca = '';

    // Propriedades para ordenação
    public $ordenar_por = 'data_inicio';
    public $direcao_ordenacao = 'asc';

    // Propriedades para o modal de criação/edição
    public $showModal = false;
    public $editingId = null;
    public $titulo = '';
    public $descricao = '';
    public $data_inicio = '';
    public $data_fim = '';
    public $hora_inicio = '';
    public $hora_fim = '';
    public $local = '';
    public $tipo = 'compromisso_pessoal';
    public $status = 'agendado';
    public $prioridade = 'media';
    public $lembrete = '';
    public $observacoes = '';

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'data_inicio' => 'required|date',
        'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fim' => 'nullable|date_format:H:i',
        'local' => 'nullable|string|max:255',
        'tipo' => 'required|in:reuniao,evento,tarefa,lembrete,compromisso_pessoal,outro',
        'status' => 'in:agendado,em_andamento,concluido,cancelado,adiado',
        'prioridade' => 'required|in:baixa,media,alta,urgente',
        'lembrete' => 'nullable|date',
        'observacoes' => 'nullable|string',
    ];

    public function mount()
    {
        // Definir valores padrão
        $this->data_inicio = now()->format('Y-m-d');
        $this->hora_inicio = '09:00';
        $this->hora_fim = '10:00';
    }

    public function render()
    {
        $query = Compromisso::forUser(Auth::id());

        // Aplicar filtros
        if ($this->filtro_status) {
            $query->byStatus($this->filtro_status);
        }

        if ($this->filtro_tipo) {
            $query->byType($this->filtro_tipo);
        }

        if ($this->filtro_prioridade) {
            $query->byPriority($this->filtro_prioridade);
        }

        if ($this->filtro_data_inicio) {
            $query->whereDate('data_inicio', '>=', $this->filtro_data_inicio);
        }

        if ($this->filtro_data_fim) {
            $query->whereDate('data_inicio', '<=', $this->filtro_data_fim);
        }

        if ($this->busca) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', '%' . $this->busca . '%')
                  ->orWhere('descricao', 'like', '%' . $this->busca . '%')
                  ->orWhere('local', 'like', '%' . $this->busca . '%');
            });
        }

        // Aplicar ordenação
        $query->orderBy($this->ordenar_por, $this->direcao_ordenacao);

        $compromissos = $query->paginate(10);

        return view('livewire.compromissos.compromisso-manager', [
            'compromissos' => $compromissos,
            'tipos' => $this->getTipos(),
            'status' => $this->getStatus(),
            'prioridades' => $this->getPrioridades(),
        ]);
    }

    public function abrirModal($id = null)
    {
        $this->resetForm();
        
        if ($id) {
            $compromisso = Compromisso::forUser(Auth::id())->findOrFail($id);
            $this->editingId = $id;
            $this->titulo = $compromisso->titulo;
            $this->descricao = $compromisso->descricao;
            $this->data_inicio = $compromisso->data_inicio->format('Y-m-d');
            $this->data_fim = $compromisso->data_fim ? $compromisso->data_fim->format('Y-m-d') : '';
            $this->hora_inicio = $compromisso->hora_inicio->format('H:i');
            $this->hora_fim = $compromisso->hora_fim ? $compromisso->hora_fim->format('H:i') : '';
            $this->local = $compromisso->local;
            $this->tipo = $compromisso->tipo;
            $this->status = $compromisso->status;
            $this->prioridade = $compromisso->prioridade;
            $this->lembrete = $compromisso->lembrete ? $compromisso->lembrete->format('Y-m-d\TH:i') : '';
            $this->observacoes = $compromisso->observacoes;
        }

        $this->showModal = true;
    }

    public function fecharModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function salvar()
    {
        $this->validate();

        $data = [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim ?: $this->data_inicio,
            'hora_inicio' => $this->hora_inicio,
            'hora_fim' => $this->hora_fim ?: $this->hora_inicio,
            'local' => $this->local,
            'tipo' => $this->tipo,
            'status' => $this->status,
            'prioridade' => $this->prioridade,
            'lembrete' => $this->lembrete,
            'observacoes' => $this->observacoes,
            'user_id' => Auth::id(),
        ];

        if ($this->editingId) {
            Compromisso::forUser(Auth::id())->findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Compromisso atualizado com sucesso!');
        } else {
            Compromisso::create($data);
            session()->flash('message', 'Compromisso criado com sucesso!');
        }

        $this->fecharModal();
    }

    public function excluir($id)
    {
        $compromisso = Compromisso::forUser(Auth::id())->findOrFail($id);
        $compromisso->delete();
        
        session()->flash('message', 'Compromisso excluído com sucesso!');
    }

    public function atualizarStatus($id, $status)
    {
        $compromisso = Compromisso::forUser(Auth::id())->findOrFail($id);
        $compromisso->update(['status' => $status]);
        
        session()->flash('message', 'Status atualizado com sucesso!');
    }

    public function ordenarPor($campo)
    {
        if ($this->ordenar_por === $campo) {
            $this->direcao_ordenacao = $this->direcao_ordenacao === 'asc' ? 'desc' : 'asc';
        } else {
            $this->ordenar_por = $campo;
            $this->direcao_ordenacao = 'asc';
        }
    }

    public function limparFiltros()
    {
        $this->filtro_status = '';
        $this->filtro_tipo = '';
        $this->filtro_prioridade = '';
        $this->filtro_data_inicio = '';
        $this->filtro_data_fim = '';
        $this->busca = '';
        $this->ordenar_por = 'data_inicio';
        $this->direcao_ordenacao = 'asc';
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->titulo = '';
        $this->descricao = '';
        $this->data_inicio = now()->format('Y-m-d');
        $this->data_fim = '';
        $this->hora_inicio = '09:00';
        $this->hora_fim = '';
        $this->local = '';
        $this->tipo = 'compromisso_pessoal';
        $this->status = 'agendado';
        $this->prioridade = 'media';
        $this->lembrete = '';
        $this->observacoes = '';
    }

    private function getTipos()
    {
        return [
            'reuniao' => 'Reunião',
            'evento' => 'Evento',
            'tarefa' => 'Tarefa',
            'lembrete' => 'Lembrete',
            'compromisso_pessoal' => 'Compromisso Pessoal',
            'outro' => 'Outro',
        ];
    }

    private function getStatus()
    {
        return [
            'agendado' => 'Agendado',
            'em_andamento' => 'Em Andamento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
            'adiado' => 'Adiado',
        ];
    }

    private function getPrioridades()
    {
        return [
            'baixa' => 'Baixa',
            'media' => 'Média',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];
    }
}
