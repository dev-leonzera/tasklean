<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    /**
     * Lista todas as tarefas
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tarefas = Tarefa::where('user_id', Auth::id())
            ->with(['projeto', 'responsavel'])
            ->latest()
            ->paginate(15);
        
        return view('tarefas.index', compact('tarefas'));
    }

    /**
     * Exibe formulário para criar nova tarefa
     */
    public function create()
    {
        $projetos = Projeto::where('user_id', Auth::id())->ativos()->get();
        $usuarios = \App\Models\User::orderBy('name')->take(50)->get();
        $projetoId = request('projeto_id');
        
        return view('tarefas.create', compact('projetos', 'usuarios', 'projetoId'));
    }

    /**
     * Cria um nova tarefa
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'in:backlog,pendente,em desenvolvimento,concluida',
            'data_vencimento' => 'nullable|date',
            'responsavel_id' => 'required|exists:users,id',
            'projeto_id' => 'required|exists:projetos,id'
        ]);

        $tarefa = Tarefa::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'status' => $request->status ?? 'backlog',
            'data_vencimento' => $request->data_vencimento,
            'responsavel_id' => $request->responsavel_id,
            'projeto_id' => $request->projeto_id,
            'data_criacao' => now(),
            'user_id' => Auth::id()
        ]);

        // Verificar se a tarefa criada está prestes a atrasar
        if ($tarefa->data_vencimento) {
            $projeto = $tarefa->projeto;
            if ($tarefa->data_vencimento->isToday()) {
                NotificationHelper::warning(
                    'Tarefa criada vence hoje!',
                    "A tarefa '{$tarefa->titulo}' do projeto '{$projeto->titulo}' vence hoje.",
                    ['url' => route('tarefas.show', $tarefa->id), 'text' => 'Ver Tarefa', 'class' => 'btn-warning']
                );
            } elseif ($tarefa->data_vencimento->isTomorrow()) {
                NotificationHelper::info(
                    'Tarefa criada vence amanhã',
                    "A tarefa '{$tarefa->titulo}' do projeto '{$projeto->titulo}' vence amanhã.",
                    ['url' => route('tarefas.show', $tarefa->id), 'text' => 'Ver Tarefa', 'class' => 'btn-info']
                );
            }
        }

        return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Exibe uma tarefa específica
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())
            ->with(['projeto', 'responsavel', 'comentarios.user'])
            ->find($id);

        if (!$tarefa) {
            return redirect()->route('tarefas.index')->with('error', 'Tarefa não encontrada!');
        }

        return view('tarefas.show', compact('tarefa'));
    }

    /**
     * Exibe formulário para editar tarefa
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())->find($id);
        
        if (!$tarefa) {
            return redirect()->route('tarefas.index')->with('error', 'Tarefa não encontrada!');
        }
        
        $projetos = Projeto::where('user_id', Auth::id())->ativos()->get();
        $usuarios = \App\Models\User::orderBy('name')->take(50)->get();
        
        return view('tarefas.edit', compact('tarefa', 'projetos', 'usuarios'));
    }

    /**
     * Atualiza uma tarefa existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())->find($id);

        if (!$tarefa) {
            return redirect()->route('tarefas.index')->with('error', 'Tarefa não encontrada!');
        }

        $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descricao' => 'sometimes|string',
            'status' => 'sometimes|in:backlog,pendente,em desenvolvimento,concluida',
            'data_vencimento' => 'sometimes|date',
            'responsavel_id' => 'sometimes|exists:users,id',
            'projeto_id' => 'sometimes|exists:projetos,id'
        ]);

        $tarefa->update($request->only([
            'titulo', 'descricao', 'status', 'data_vencimento', 'responsavel_id', 'projeto_id'
        ]));

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove uma tarefa
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())->find($id);

        if (!$tarefa) {
            return redirect()->route('tarefas.index')->with('error', 'Tarefa não encontrada!');
        }

        $tarefa->delete();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa removida com sucesso!');
    }

    /**
     * Altera o status de uma tarefa
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function alterarStatus(Request $request, int $id)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())->find($id);

        if (!$tarefa) {
            return redirect()->route('tarefas.index')->with('error', 'Tarefa não encontrada!');
        }

        $request->validate([
            'status' => 'required|in:backlog,pendente,em desenvolvimento,concluida'
        ]);

        $statusAnterior = $tarefa->status;
        $tarefa->update(['status' => $request->status]);

        // Notificação especial quando tarefa é concluída
        if ($request->status === 'concluida' && $statusAnterior !== 'concluida') {
            NotificationHelper::success(
                'Tarefa concluída!',
                "A tarefa '{$tarefa->titulo}' foi marcada como concluída.",
                ['url' => route('tarefas.show', $tarefa->id), 'text' => 'Ver Tarefa', 'class' => 'btn-success']
            );
        }

        return redirect()->back()->with('success', 'Status da tarefa alterado com sucesso!');
    }


    /**
     * Lista tarefas por projeto
     *
     * @param int $projetoId
     * @return JsonResponse
     */
    public function porProjeto(int $projetoId): JsonResponse
    {
        $projeto = Projeto::where('user_id', Auth::id())->find($projetoId);

        if (!$projeto) {
            return response()->json([
                'success' => false,
                'message' => 'Projeto não encontrado'
            ], 404);
        }

        $tarefas = $projeto->tarefas()->where('user_id', Auth::id())->get();
        
        return response()->json([
            'success' => true,
            'data' => $tarefas
        ]);
    }
}
