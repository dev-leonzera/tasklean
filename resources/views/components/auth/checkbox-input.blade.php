@props([
    'name',
    'label',
    'wireModel' => '',
    'value' => false,
    'disabled' => false,
    'id' => null
])

@php
    $inputId = $id ?? $name;
@endphp

<div class="form-check">
    <input 
        class="form-check-input" 
        type="checkbox" 
        id="{{ $inputId }}"
        name="{{ $name }}"
        @if($wireModel) wire:model="{{ $wireModel }}" @endif
        @if($value) checked @endif
        @if($disabled) disabled @endif
        style="border-radius: 4px;"
    >
    <label class="form-check-label fw-medium" for="{{ $inputId }}" style="color: var(--sidebar-text);">
        {{ $label }}
    </label>
</div>
