<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    use WithFileUploads;

    #[Validate('required|string|email|max:255')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    public bool $remember = false;
    public bool $isLoading = false;
    public bool $showPassword = false;
    public bool $emailValid = false;
    public bool $passwordValid = false;

    protected $rules = [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8',
    ];

    protected $messages = [
        'email.required' => 'O campo e-mail é obrigatório.',
        'email.email' => 'Por favor, insira um e-mail válido.',
        'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
    ];

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->isLoading = true;
        
        try {
            $this->validate();

            $this->ensureIsNotRateLimited();

            if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => 'As credenciais fornecidas não conferem com nossos registros.',
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            Session::regenerate();

            // Adicionar evento de login bem-sucedido
            $this->dispatch('login-success');

            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            
        } catch (ValidationException $e) {
            $this->isLoading = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isLoading = false;
            $this->addError('email', 'Ocorreu um erro inesperado. Tente novamente.');
        }
    }

    /**
     * Toggle password visibility
     */
    public function togglePasswordVisibility(): void
    {
        $this->showPassword = !$this->showPassword;
    }

    /**
     * Clear form data
     */
    public function clearForm(): void
    {
        $this->reset(['email', 'password', 'remember', 'emailValid', 'passwordValid']);
        $this->resetErrorBag();
    }

    /**
     * Validate email in real-time
     */
    public function updatedEmail(): void
    {
        $this->emailValid = filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false && !empty($this->email);
        $this->resetErrorBag('email');
    }

    /**
     * Validate password in real-time
     */
    public function updatedPassword(): void
    {
        $this->passwordValid = strlen($this->password) >= 8 && !empty($this->password);
        $this->resetErrorBag('password');
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.auth.login');
    }
}
