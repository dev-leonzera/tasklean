<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeRequest;
use App\Models\Time;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TimeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $times = Time::where('owner_id', Auth::id())
            ->orWhereHas('membros', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->withCount(['membros', 'projetos'])
            ->get();

        return view('times.index', compact('times'));
    }

    public function create()
    {
        return view('times.create');
    }

    public function store(StoreTimeRequest $request)
    {
        $time = Time::create([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome) . '-' . Str::random(5),
            'descricao' => $request->descricao,
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('times.index')->with('success', 'Time criado com sucesso!');
    }

    public function show(Time $time)
    {
        $this->authorize('view', $time);

        $time->load(['membros', 'projetos', 'owner']);

        return view('times.show', compact('time'));
    }

    public function edit(Time $time)
    {
        $this->authorize('update', $time);

        return view('times.edit', compact('time'));
    }

    public function update(Request $request, Time $time)
    {
        $this->authorize('update', $time);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
        ]);

        $time->update($request->only(['nome', 'descricao']));

        return redirect()->route('times.show', $time->slug)->with('success', 'Time atualizado com sucesso!');
    }

    public function destroy(Time $time)
    {
        $this->authorize('delete', $time);

        $time->delete();

        return redirect()->route('times.index')->with('success', 'Time excluído com sucesso!');
    }
}
