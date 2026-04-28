<div>
    {{-- Componente lógico sem UI --}}
    <script>
        document.addEventListener('livewire:init', function() {
            if (!window.Echo) return;

            console.log('📡 RealtimeManager pronto.');

            @php
                $userId = auth()->id();
                $times = auth()->user()->times()->pluck('times.id')->merge(
                    auth()->user()->ownedTimes()->pluck('id')
                )->unique();
            @endphp

            // Debug logs para o canal privado
            window.Echo.private('user.{{ $userId }}')
                .listen('TarefaCriada', function(e) { console.log('✨ Evento (User): Tarefa Criada', e); })
                .listen('TarefaAtualizada', function(e) { console.log('🔄 Evento (User): Tarefa Atualizada', e); });

            // Debug logs para os canais de times
            @foreach($times as $timeId)
                window.Echo.private('time.{{ $timeId }}')
                    .listen('TarefaCriada', function(e) { console.log('✨ Evento (Time {{ $timeId }}): Tarefa Criada', e); })
                    .listen('TarefaAtualizada', function(e) { console.log('🔄 Evento (Time {{ $timeId }}): Tarefa Atualizada', e); });
            @endforeach
        });
    </script>
</div>
