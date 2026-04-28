<div
    x-data="{
        toasts: [],
        add(detail) {
            // Se o detalhe for do evento 'nova-notificacao-realtime'
            if (detail.message && !detail.slots) {
                detail = {
                    slots: {
                        text: detail.message,
                        heading: detail.title
                    },
                    dataset: {
                        variant: detail.type || 'info'
                    },
                    duration: 5000
                };
            }

            const id = Date.now();
            this.toasts.push({
                id,
                text: detail.slots?.text || '',
                heading: detail.slots?.heading || '',
                variant: detail.dataset?.variant || 'info',
                position: detail.dataset?.position || 'top right'
            });

            setTimeout(() => this.remove(id), detail.duration || 5000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-on:toast-show.window="add($event.detail)"
    x-on:nova-notificacao-realtime.window="add($event.detail)"
    class="fixed top-0 right-0 p-6 z-[9999] flex flex-col gap-3 pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            class="pointer-events-auto w-80 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-lg rounded-lg overflow-hidden p-4 mb-3 transition-all duration-300 transform"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <template x-if="toast.variant === 'success'">
                        <flux:icon icon="check-circle" variant="micro" class="text-green-500" />
                    </template>
                    <template x-if="toast.variant === 'error' || toast.variant === 'danger'">
                        <flux:icon icon="x-circle" variant="micro" class="text-red-500" />
                    </template>
                    <template x-if="toast.variant === 'warning'">
                        <flux:icon icon="exclamation-triangle" variant="micro" class="text-yellow-500" />
                    </template>
                    <template x-if="toast.variant === 'info' || toast.variant === 'default'">
                        <flux:icon icon="information-circle" variant="micro" class="text-blue-500" />
                    </template>
                </div>
                <div class="flex-1">
                    <h3 x-show="toast.heading" class="text-sm font-semibold text-zinc-900 dark:text-white" x-text="toast.heading"></h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400" x-text="toast.text"></p>
                </div>
                <button @click="remove(toast.id)" class="ml-4 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                    <flux:icon icon="x-mark" variant="micro" />
                </button>
            </div>
        </div>
    </template>
</div>
