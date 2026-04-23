<section class="w-full">
    <x-settings.layout :heading="__('Aparência')" :subheading=" __('Personalize como o Tasklean aparece no seu dispositivo.')">
        <div class="mt-4">
            <label class="form-label fw-bold mb-3">{{ __('Tema do Sistema') }}</label>
            
            <div class="chart-container p-2 bg-light border-0 shadow-none rounded-pill d-inline-flex gap-1 mb-4">
                <button class="btn {{ $appearance === 'light' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setAppearance('light')" type="button">
                    <i class="bi bi-sun me-2"></i> {{ __('Claro') }}
                </button>
                <button class="btn {{ $appearance === 'dark' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setAppearance('dark')" type="button">
                    <i class="bi bi-moon me-2"></i> {{ __('Escuro') }}
                </button>
                <button class="btn {{ $appearance === 'system' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setAppearance('system')" type="button">
                    <i class="bi bi-display me-2"></i> {{ __('Sistema') }}
                </button>
            </div>

            <div class="mt-4 p-4 rounded-4 bg-light border-start border-4 border-primary">
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    A preferência de tema será aplicada automaticamente ao carregar a página. O modo "Sistema" sincroniza com as configurações do seu navegador ou sistema operacional.
                </p>
            </div>
        </div>
    </x-settings.layout>
</section>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('appearance-updated', (event) => {
            const appearance = event[0].appearance;
            if (appearance === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (appearance === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    });
</script>
