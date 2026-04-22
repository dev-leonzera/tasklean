@php
    $notifications = session('notifications', []);
    $userSettings = \App\Models\UserSettings::getForUser(auth()->id());
@endphp

@if(count($notifications) > 0 && request()->routeIs('dashboard') && $userSettings->notifications_enabled)
    <div id="notifications-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        @foreach($notifications as $notification)
            <div class="toast show mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header {{ $notification['type'] === 'warning' ? 'bg-warning text-dark' : ($notification['type'] === 'danger' ? 'bg-danger text-white' : ($notification['type'] === 'success' ? 'bg-success text-white' : 'bg-info text-white')) }}">
                    <i class="bi {{ $notification['icon'] ?? 'bi-bell' }} me-2"></i>
                    <strong class="me-auto">{{ $notification['title'] ?? 'Notificação' }}</strong>
                    <small class="text-muted">{{ now()->format('H:i') }}</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ $notification['message'] }}
                    @if(isset($notification['action']))
                        <div class="mt-2">
                            <a href="{{ $notification['action']['url'] }}" class="btn btn-sm {{ $notification['action']['class'] ?? 'btn-primary' }}">
                                {{ $notification['action']['text'] }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Notificações em tempo real -->
<div id="realtime-notifications" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
