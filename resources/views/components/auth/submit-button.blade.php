@props([
    'type' => 'submit',
    'text',
    'icon' => '',
    'loading' => false,
    'disabled' => false,
    'class' => '',
    'onclick' => '',
    'wireClick' => ''
])

@php
    $buttonClass = 'btn w-100 py-3 fw-bold text-white border-0 mb-4 ' . $class;
    $isLoading = $loading || $disabled;
@endphp

<button 
    type="{{ $type }}" 
    class="{{ $buttonClass }} {{ $isLoading ? 'loading' : '' }}" 
    @if($onclick) onclick="{{ $onclick }}" @endif
    @if($wireClick) wire:click="{{ $wireClick }}" @endif
    @if($disabled) disabled @endif
    style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border-radius: 12px; font-size: 1.1rem; transition: all 0.3s ease;"
    onmouseover="if(!this.disabled) { this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.3)'; }"
    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
>
    @if($icon && !$isLoading)
        <i class="{{ $icon }} me-2"></i>
    @endif
    {{ $text }}
</button>
