<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Time;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Time::with(['owner', 'membros', 'projetos']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nome', 'like', "%{$search}%");
        }

        $teams = $query->latest()->paginate(20);

        return view('admin.teams.index', compact('teams'));
    }
}
