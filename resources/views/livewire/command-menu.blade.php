<div 
    x-data="{ 
        isOpen: @entangle('isOpen'),
        selectedIndex: @entangle('selectedIndex'),
        resultsCount: 0
    }"
    x-on:keydown.window.prevent.cmd.k="isOpen = true; $nextTick(() => $refs.commandInput.focus())"
    x-on:keydown.window.prevent.ctrl.k="isOpen = true; $nextTick(() => $refs.commandInput.focus())"
    x-on:keydown.escape.window="isOpen = false"
    class="command-menu-container"
    x-show="isOpen"
    x-cloak
>
    <!-- Backdrop -->
    <div 
        x-show="isOpen"
        x-transition:enter="fade-enter"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="fade-leave"
        class="command-menu-backdrop"
        @click="isOpen = false"
    ></div>

    <!-- Modal Content -->
    <div class="command-menu-wrapper">
        <div 
            x-show="isOpen"
            x-transition:enter="modal-enter"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="modal-leave"
            class="command-menu-modal"
            @click.away="isOpen = false"
        >
            <div class="command-menu-header">
                <i class="bi bi-search search-icon"></i>
                <input 
                    x-ref="commandInput"
                    wire:model.live.debounce.200ms="query"
                    type="text" 
                    class="command-menu-input" 
                    placeholder="O que você está procurando? (Cmd+K)"
                    x-on:keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, resultsCount - 1)"
                    x-on:keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)"
                    x-on:keydown.enter.prevent="$wire.selectItem(selectedIndex)"
                >
            </div>

            <div class="command-menu-body">
                <!-- Pre-defined Actions -->
                <div x-show="!$wire.query">
                    <h6 class="category-title">Ações Rápidas</h6>
                    <div class="actions-list">
                        <a href="{{ route('dashboard') }}" class="action-item">
                            <i class="bi bi-speedometer2"></i>
                            <span>Ir para Dashboard</span>
                            <span class="shortcut-hint">
                                <kbd>D</kbd>
                            </span>
                        </a>
                        <a href="{{ route('tarefas.create') }}" class="action-item">
                            <i class="bi bi-plus-circle"></i>
                            <span>Nova Tarefa</span>
                            <span class="shortcut-hint">
                                <kbd>N</kbd>
                            </span>
                        </a>
                        <a href="{{ route('projetos.index') }}" class="action-item">
                            <i class="bi bi-folder"></i>
                            <span>Ver Projetos</span>
                            <span class="shortcut-hint">
                                <kbd>P</kbd>
                            </span>
                        </a>
                        <a href="{{ route('kanban') }}" class="action-item">
                            <i class="bi bi-kanban"></i>
                            <span>Quadro Kanban</span>
                            <span class="shortcut-hint">
                                <kbd>K</kbd>
                            </span>
                        </a>
                        <a href="{{ route('sprints') }}" class="action-item">
                            <i class="bi bi-calendar-week"></i>
                            <span>Sprints</span>
                            <span class="shortcut-hint">
                                <kbd>S</kbd>
                            </span>
                        </a>
                        <a href="{{ route('times.index') }}" class="action-item">
                            <i class="bi bi-people"></i>
                            <span>Equipes</span>
                            <span class="shortcut-hint">
                                <kbd>E</kbd>
                            </span>
                        </a>
                        <a href="{{ route('compromissos.index') }}" class="action-item">
                            <i class="bi bi-calendar-event"></i>
                            <span>Compromissos</span>
                            <span class="shortcut-hint">
                                <kbd>C</kbd>
                            </span>
                        </a>
                        <a href="{{ route('relatorios.index') }}" class="action-item">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Relatórios</span>
                            <span class="shortcut-hint">
                                <kbd>R</kbd>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Search Results -->
                <div x-show="$wire.query" x-effect="resultsCount = $wire.results.length">
                    <template x-if="$wire.results.length > 0">
                        <div class="actions-list">
                            <template x-for="(result, index) in $wire.results" :key="index">
                                <div 
                                    @click="$wire.selectItem(index)"
                                    @mouseenter="selectedIndex = index"
                                    :class="{ 'active': selectedIndex === index }"
                                    class="action-item"
                                >
                                    <i :class="result.icon"></i>
                                    <div class="result-info">
                                        <span class="result-title" x-text="result.title"></span>
                                        <span class="result-type" x-text="result.type.charAt(0).toUpperCase() + result.type.slice(1)"></span>
                                    </div>
                                    <i x-show="selectedIndex === index" class="bi bi-arrow-return-left ms-auto opacity-50"></i>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="$wire.query.length >= 2 && $wire.results.length === 0">
                        <div class="no-results">
                            <i class="bi bi-search mb-3"></i>
                            <p>Nenhum resultado para "<span x-text="$wire.query"></span>".</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div class="command-menu-footer">
                <div class="footer-hints">
                    <span class="hint-item"><kbd>↑↓</kbd> Navegar</span>
                    <span class="hint-item"><kbd>↵</kbd> Selecionar</span>
                    <span class="hint-item"><kbd>ESC</kbd> Fechar</span>
                </div>
                <div class="footer-brand">Tasklean Command</div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        
        .command-menu-container {
            position: fixed;
            inset: 0;
            z-index: 9999;
        }

        .command-menu-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .command-menu-wrapper {
            position: fixed;
            inset: 0;
            display: flex;
            justify-content: center;
            padding: 5rem 1rem;
            pointer-events: none;
        }

        .command-menu-modal {
            width: 100%;
            max-width: 640px;
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            pointer-events: auto;
            height: fit-content;
        }

        .command-menu-header {
            position: relative;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .search-icon {
            font-size: 1.25rem;
            color: var(--light-text);
            margin-right: 0.75rem;
        }

        .command-menu-input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 1.1rem;
            color: var(--dark-text);
            background: transparent;
        }

        .command-menu-body {
            padding: 0.75rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .category-title {
            padding: 0.5rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--light-text);
            margin-bottom: 0.25rem;
        }

        .actions-list {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            color: var(--medium-text);
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            gap: 0.75rem;
        }

        .action-item:hover, .action-item.active {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            transform: translateX(4px);
        }

        .action-item i {
            font-size: 1.1rem;
            opacity: 0.7;
        }

        .shortcut-hint {
            margin-left: auto;
            display: flex;
            gap: 4px;
        }

        .shortcut-hint kbd, .footer-hints kbd {
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--medium-text);
            font-family: inherit;
            box-shadow: 0 1px 0 rgba(0,0,0,0.1);
        }

        .action-item.active .shortcut-hint kbd {
            background-color: var(--white);
            border-color: var(--primary-color);
            color: var(--primary-dark);
        }

        .result-info {
            display: flex;
            flex-direction: column;
        }

        .result-title {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .result-type {
            font-size: 0.75rem;
            opacity: 0.6;
        }

        .no-results {
            padding: 3rem 1rem;
            text-align: center;
            color: var(--light-text);
        }

        .no-results i {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .command-menu-footer {
            padding: 0.75rem 1.25rem;
            background-color: var(--light-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: var(--light-text);
        }

        .footer-hints {
            display: flex;
            gap: 1rem;
        }

        .hint-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .hint-item kbd {
            background-color: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 1px 4px;
            font-size: 0.7rem;
            color: var(--medium-text);
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        }

        .footer-brand {
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Transitions */
        .fade-enter { transition: opacity 0.3s ease-out; }
        .fade-leave { transition: opacity 0.2s ease-in; }
        .modal-enter { transition: all 0.3s ease-out; }
        .modal-leave { transition: all 0.2s ease-in; }
    </style>
</div>
