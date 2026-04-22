<?php

namespace App\Http\Controllers;

use App\Models\Compromisso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CompromissoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $compromissos = Compromisso::where('user_id', $user->id)
            ->orderBy('data_inicio', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->paginate(10);

        return view('compromissos.index', compact('compromissos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('compromissos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ]);

        // Se não foi informada data_fim, usar a mesma data_inicio
        if (empty($validated['data_fim'])) {
            $validated['data_fim'] = $validated['data_inicio'];
        }

        // Se não foi informada hora_fim, usar a mesma hora_inicio
        if (empty($validated['hora_fim'])) {
            $validated['hora_fim'] = $validated['hora_inicio'];
        }

        $validated['user_id'] = Auth::id();

        Compromisso::create($validated);

        return redirect()->route('compromissos.index')
            ->with('success', 'Compromisso criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compromisso $compromisso)
    {
        // Verificar se o compromisso pertence ao usuário autenticado
        if ($compromisso->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        return view('compromissos.show', compact('compromisso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compromisso $compromisso)
    {
        // Verificar se o compromisso pertence ao usuário autenticado
        if ($compromisso->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        return view('compromissos.edit', compact('compromisso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compromisso $compromisso)
    {
        // Verificar se o compromisso pertence ao usuário autenticado
        if ($compromisso->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
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
        ]);

        // Se não foi informada data_fim, usar a mesma data_inicio
        if (empty($validated['data_fim'])) {
            $validated['data_fim'] = $validated['data_inicio'];
        }

        // Se não foi informada hora_fim, usar a mesma hora_inicio
        if (empty($validated['hora_fim'])) {
            $validated['hora_fim'] = $validated['hora_inicio'];
        }

        $compromisso->update($validated);

        return redirect()->route('compromissos.index')
            ->with('success', 'Compromisso atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compromisso $compromisso)
    {
        // Verificar se o compromisso pertence ao usuário autenticado
        if ($compromisso->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $compromisso->delete();

        return redirect()->route('compromissos.index')
            ->with('success', 'Compromisso excluído com sucesso!');
    }

    /**
     * Atualizar status do compromisso
     */
    public function updateStatus(Request $request, Compromisso $compromisso)
    {
        // Verificar se o compromisso pertence ao usuário autenticado
        if ($compromisso->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'status' => 'required|in:agendado,em_andamento,concluido,cancelado,adiado'
        ]);

        $compromisso->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!'
        ]);
    }

    /**
     * Obter compromissos para calendário (API)
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $start = $request->get('start', now()->startOfMonth());
        $end = $request->get('end', now()->endOfMonth());

        $compromissos = Compromisso::forUser($user->id)
            ->whereBetween('data_inicio', [$start, $end])
            ->get()
            ->map(function ($compromisso) {
                // Usar data_inicio se data_fim for null
                $dataFim = $compromisso->data_fim ?? $compromisso->data_inicio;
                $horaFim = $compromisso->hora_fim ?? $compromisso->hora_inicio;
                
                return [
                    'id' => $compromisso->id,
                    'title' => $compromisso->titulo,
                    'start' => $compromisso->data_inicio->format('Y-m-d') . 'T' . $compromisso->hora_inicio->format('H:i:s'),
                    'end' => $dataFim->format('Y-m-d') . 'T' . $horaFim->format('H:i:s'),
                    'backgroundColor' => $this->getStatusColor($compromisso->status),
                    'borderColor' => $this->getPriorityColor($compromisso->prioridade),
                    'extendedProps' => [
                        'tipo' => $compromisso->tipo_formatado,
                        'prioridade' => $compromisso->prioridade_formatada,
                        'local' => $compromisso->local,
                        'descricao' => $compromisso->descricao,
                    ]
                ];
            });

        return response()->json($compromissos);
    }

    /**
     * Obter compromissos próximos
     */
    public function upcoming()
    {
        $user = Auth::user();
        $compromissos = Compromisso::forUser($user->id)
            ->upcoming(7)
            ->orderBy('data_inicio')
            ->orderBy('hora_inicio')
            ->get();

        return view('compromissos.upcoming', compact('compromissos'));
    }

    /**
     * Obter compromissos de hoje
     */
    public function today()
    {
        $user = Auth::user();
        $compromissos = Compromisso::forUser($user->id)
            ->today()
            ->orderBy('hora_inicio')
            ->get();

        return view('compromissos.today', compact('compromissos'));
    }

    /**
     * Obter cor baseada no status
     */
    private function getStatusColor($status)
    {
        $colors = [
            'agendado' => '#3b82f6',
            'em_andamento' => '#f59e0b',
            'concluido' => '#10b981',
            'cancelado' => '#ef4444',
            'adiado' => '#6b7280',
        ];

        return $colors[$status] ?? '#3b82f6';
    }

    /**
     * Obter cor baseada na prioridade
     */
    private function getPriorityColor($prioridade)
    {
        $colors = [
            'baixa' => '#10b981',
            'media' => '#f59e0b',
            'alta' => '#f97316',
            'urgente' => '#ef4444',
        ];

        return $colors[$prioridade] ?? '#f59e0b';
    }
}
