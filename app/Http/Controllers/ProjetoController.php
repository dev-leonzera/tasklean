<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetoController extends Controller
{
    /**
     * Lista todos os projetos
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Projeto::where('user_id', Auth::id())->with(['responsavel', 'tarefas' => function($query) {
            $query->where('user_id', Auth::id());
        }]);
        
        // Filtro por status
        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }
        
        // Filtro por responsável
        if ($request->filled('responsavel_id')) {
            $query->where('responsavel_id', $request->responsavel_id);
        }
        
        // Filtro por busca (título)
        if ($request->filled('busca')) {
            $query->where('titulo', 'like', '%' . $request->busca . '%');
        }
        
        // Ordenação
        $ordenacao = $request->get('ordenacao', 'data_criacao');
        $direcao = $request->get('direcao', 'desc');
        
        if (in_array($ordenacao, ['titulo', 'responsavel_id', 'data_criacao', 'ativo'])) {
            $query->orderBy($ordenacao, $direcao);
        }
        
        $projetos = $query->paginate(10);
        
        return view('projetos.index', compact('projetos'));
    }

    /**
     * Exibe formulário para criar novo projeto
     */
    public function create()
    {
        $usuarios = \App\Models\User::orderBy('name')->take(50)->get();
        return view('projetos.create', compact('usuarios'));
    }

    /**
     * Cria um novo projeto
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'responsavel_id' => 'required|exists:users,id',
            'ativo' => 'boolean'
        ]);

        Projeto::create([
            'titulo' => $request->titulo,
            'responsavel_id' => $request->responsavel_id,
            'ativo' => $request->ativo ?? true,
            'data_criacao' => now(),
            'user_id' => Auth::id()
        ]);

        return redirect()->route('projetos.index')->with('success', 'Projeto criado com sucesso!');
    }

    /**
     * Exibe um projeto específico
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        $projeto = Projeto::where('user_id', Auth::id())
            ->with(['tarefas' => function($query) {
                $query->where('user_id', Auth::id());
            }, 'responsavel'])
            ->find($id);

        if (!$projeto) {
            return redirect()->route('projetos.index')->with('error', 'Projeto não encontrado!');
        }

        return view('projetos.show', compact('projeto'));
    }

    /**
     * Exibe formulário para editar projeto
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $projeto = Projeto::where('user_id', Auth::id())->find($id);
        
        if (!$projeto) {
            return redirect()->route('projetos.index')->with('error', 'Projeto não encontrado!');
        }
        
        $usuarios = \App\Models\User::orderBy('name')->take(50)->get();
        return view('projetos.edit', compact('projeto', 'usuarios'));
    }

    /**
     * Atualiza um projeto existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $projeto = Projeto::where('user_id', Auth::id())->find($id);

        if (!$projeto) {
            return redirect()->route('projetos.index')->with('error', 'Projeto não encontrado!');
        }

        $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'responsavel_id' => 'sometimes|exists:users,id',
            'ativo' => 'sometimes|boolean'
        ]);

        $projeto->update($request->only(['titulo', 'responsavel_id', 'ativo']));

        return redirect()->route('projetos.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    /**
     * Remove um projeto
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $projeto = Projeto::where('user_id', Auth::id())->find($id);

        if (!$projeto) {
            return redirect()->route('projetos.index')->with('error', 'Projeto não encontrado!');
        }

        $projeto->delete();

        return redirect()->route('projetos.index')->with('success', 'Projeto removido com sucesso!');
    }

    /**
     * Ativa um projeto
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ativar(int $id)
    {
        $projeto = Projeto::where('user_id', Auth::id())->find($id);

        if (!$projeto) {
            return redirect()->route('projetos.index')->with('error', 'Projeto não encontrado!');
        }

        $projeto->update(['ativo' => true]);

        return redirect()->route('projetos.index')->with('success', 'Projeto ativado com sucesso!');
    }

    /**
     * Inativa um projeto
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inativar(int $id)
    {
        $projeto = Projeto::where('user_id', Auth::id())->find($id);

        if (!$projeto) {
            return redirect()->route('projetos.index')->with('error', 'Projeto não encontrado!');
        }

        $projeto->update(['ativo' => false]);

        return redirect()->route('projetos.index')->with('success', 'Projeto inativado com sucesso!');
    }
}
