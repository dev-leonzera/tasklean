<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Exibe a página de seleção de relatórios
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buscar projetos e compromissos para filtros
        $projetos = Projeto::where('user_id', $user->id)->orderBy('titulo')->get();
        $usuarios = \App\Models\User::orderBy('name')->take(100)->get();
        
        return view('relatorios.index', compact('projetos', 'usuarios'));
    }

    /**
     * Gera relatório de projetos
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function projetos(Request $request)
    {
        $user = Auth::user();
        
        $query = Projeto::where('user_id', $user->id)->with(['tarefas', 'responsavel']);
        
        // Filtros
        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->ativos();
            } elseif ($request->status === 'inativo') {
                $query->inativos();
            }
        }
        
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_criacao', '>=', $request->data_inicio);
        }
        
        if ($request->filled('data_fim')) {
            $query->whereDate('data_criacao', '<=', $request->data_fim);
        }

        if ($request->filled('responsavel_id')) {
            $query->where('responsavel_id', $request->responsavel_id);
        }
        
        $projetos = $query->withCount([
            'tarefas as total_tarefas' => function($q) use ($user) { $q->where('user_id', $user->id); },
            'tarefas as tarefas_pendentes' => function($q) use ($user) { $q->where('user_id', $user->id)->where('status', 'pendente'); },
            'tarefas as tarefas_em_desenvolvimento' => function($q) use ($user) { $q->where('user_id', $user->id)->where('status', 'em desenvolvimento'); },
            'tarefas as tarefas_concluidas' => function($q) use ($user) { $q->where('user_id', $user->id)->where('status', 'concluida'); },
            'tarefas as tarefas_backlog' => function($q) use ($user) { $q->where('user_id', $user->id)->where('status', 'backlog'); }
        ])->get()->map(function($projeto) {
            $projeto->percentual_concluido = $projeto->total_tarefas > 0 
                ? round(($projeto->tarefas_concluidas / $projeto->total_tarefas) * 100, 1)
                : 0;
            return $projeto;
        });
        
        $estatisticas = [
            'total' => $projetos->count(),
            'ativos' => $projetos->where('ativo', true)->count(),
            'inativos' => $projetos->where('ativo', false)->count(),
            'total_tarefas' => $projetos->sum('total_tarefas'),
            'tarefas_concluidas' => $projetos->sum('tarefas_concluidas'),
        ];
        
        return view('relatorios.projetos', compact('projetos', 'estatisticas', 'request'));
    }

    /**
     * Gera relatório de tarefas
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function tarefas(Request $request)
    {
        $user = Auth::user();
        
        $query = Tarefa::where('user_id', $user->id)->with(['projeto', 'responsavel']);
        
        // Filtros
        if ($request->filled('projeto_id')) {
            $query->where('projeto_id', $request->projeto_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_criacao', '>=', $request->data_inicio);
        }
        
        if ($request->filled('data_fim')) {
            $query->whereDate('data_criacao', '<=', $request->data_fim);
        }
        
        if ($request->filled('data_vencimento_inicio')) {
            $query->whereDate('data_vencimento', '>=', $request->data_vencimento_inicio);
        }
        
        if ($request->filled('data_vencimento_fim')) {
            $query->whereDate('data_vencimento', '<=', $request->data_vencimento_fim);
        }
        
        if ($request->filled('responsavel_id')) {
            $query->where('responsavel_id', $request->responsavel_id);
        }
        
        if ($request->filled('apenas_atrasadas') && $request->apenas_atrasadas) {
            $query->atrasadas();
        }
        
        $tarefas = $query->orderBy('data_vencimento', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        $estatisticas = [
            'total' => $tarefas->count(),
            'backlog' => $tarefas->where('status', 'backlog')->count(),
            'pendentes' => $tarefas->where('status', 'pendente')->count(),
            'em_desenvolvimento' => $tarefas->where('status', 'em desenvolvimento')->count(),
            'concluidas' => $tarefas->where('status', 'concluida')->count(),
            'atrasadas' => $tarefas->filter(function($tarefa) {
                return $tarefa->isAtrasada();
            })->count(),
        ];
        
        return view('relatorios.tarefas', compact('tarefas', 'estatisticas', 'request'));
    }

}

