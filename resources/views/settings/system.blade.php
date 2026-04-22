@extends('layouts.app')

@section('title', 'Configurações do Sistema - Tasklean')
@section('page-title', 'Configurações do Sistema')

@section('content')
<div class="container-fluid">
    @livewire('settings.system-settings')
</div>
@endsection
