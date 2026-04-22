@props([
    'type' => 'text',
    'name',
    'label',
    'placeholder' => '',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => '',
    'icon' => '',
    'wireModel' => '',
    'error' => null,
    'value' => '',
    'disabled' => false,
    'class' => '',
    'id' => null
])

@php
    $inputId = $id ?? $name;
    $hasError = $error !== null;
    $inputClass = 'form-control form-control-lg ' . ($hasError ? 'is-invalid' : '') . ' ' . $class;
    $borderColor = $hasError ? '#e53e3e' : '#e2e8f0';
@endphp

<div class="mb-4">
    <label for="{{ $inputId }}" class="form-label fw-semibold" style="color: var(--sidebar-text);">
        @if($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <input 
        type="{{ $type }}" 
        class="{{ $inputClass }}" 
        id="{{ $inputId }}"
        name="{{ $name }}"
        @if($wireModel) wire:model="{{ $wireModel }}" @endif
        @if($value) value="{{ $value }}" @endif
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($autofocus) autofocus @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($disabled) disabled @endif
        style="border-radius: 12px; border: 2px solid {{ $borderColor }}; padding: 12px 16px; transition: all 0.3s ease;"
        onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 0.2rem rgba(16, 185, 129, 0.25)'"
        onblur="this.style.borderColor='{{ $borderColor }}'; this.style.boxShadow='none'"
    >
    
    @if($hasError)
        <div class="invalid-feedback">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $error }}
        </div>
    @endif
</div>
