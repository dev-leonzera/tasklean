<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use App\Models\Time;
use App\Models\Projeto;
use Illuminate\Support\Str;

class Wizard extends Component
{
    public int $currentStep = 1;

    // Step 1: Team
    public string $teamName = '';
    public string $teamDescription = '';

    // Step 2: Project
    public string $projectTitle = '';

    public function mount()
    {
        if (auth()->user()->onboarding_completed_at !== null) {
            return redirect()->route('dashboard');
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'teamName' => 'required|min:3|max:255',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'projectTitle' => 'required|min:3|max:255',
            ]);
        }

        $this->currentStep++;

        if ($this->currentStep > 2) {
            $this->finish();
        }
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function finish()
    {
        $user = auth()->user();

        // 1. Create Team
        $time = Time::create([
            'nome' => $this->teamName,
            'slug' => Str::slug($this->teamName) . '-' . uniqid(),
            'descricao' => $this->teamDescription,
            'owner_id' => $user->id,
        ]);

        // Add user to team as owner
        $time->membros()->attach($user->id, ['regra' => 'owner']);

        // 2. Create Project
        $projeto = Projeto::create([
            'titulo' => $this->projectTitle,
            'ativo' => true,
            'responsavel_id' => $user->id,
            'user_id' => $user->id,
            'time_id' => $time->id,
            'data_criacao' => now(),
        ]);

        // 3. Mark Onboarding as Completed
        $user->update(['onboarding_completed_at' => now()]);

        // 4. Redirect to dashboard
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.onboarding.wizard')
            ->layout('components.layouts.auth');
    }
}
