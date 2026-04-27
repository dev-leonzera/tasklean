<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Não é possível banir outro administrador.');
        }

        if ($user->banned_at) {
            $user->update(['banned_at' => null]);
            return back()->with('success', "Usuário {$user->name} foi reativado.");
        }

        $user->update(['banned_at' => now()]);
        return back()->with('success', "Usuário {$user->name} foi banido.");
    }

    public function impersonate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você já está logado como você mesmo.');
        }

        // Store the original admin ID in the session
        session(['impersonator_id' => auth()->id()]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Você agora está logado como {$user->name}.");
    }

    public function stopImpersonating()
    {
        $adminId = session('impersonator_id');

        if (!$adminId) {
            return redirect()->route('dashboard');
        }

        $admin = User::findOrFail($adminId);
        
        session()->forget('impersonator_id');
        Auth::login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Voltou ao seu perfil de administrador.');
    }
}
