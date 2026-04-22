@extends('layouts.app')

@section('title', 'Relatório de Projetos - Tasklean')

@section('content')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background: white;
        }
        .card {
            border: none;
            box-shadow: none;
        }
    }
    .report-header {
        border-bottom: 2px solid #10b981;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        border-left: 4px solid #10b981;
        background: #f8f9fa;
    }
</style>

<div class="no-print mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">
                <i class="bi bi-folder me-2"></i>Relatório de Projetos
            </h1>
            <p class="text-muted mb-0">Gerado em {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary me-2">
                <i class="bi bi-printer me-1"></i> Imprimir
            </button>
            <a href="{{ route('relatorios.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>
</div>

<!-- Cabeçalho do Relatório -->
<div class="report-header">
    <div class="row">
        <div class="col-md-8">
            <p class="text-muted mb-0">
                @if($request->filled('status'))
                    <strong>Status:</strong> {{ $request->status === 'ativo' ? 'Ativos' : 'Inativos' }}
                @else
                    <strong>Todos os projetos</strong>
                @endif
                @if($request->filled('data_inicio') || $request->filled('data_fim'))
                    | <strong>Período:</strong> 
                    {{ $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : 'Início' }}
                    até
                    {{ $request->data_fim ? \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') : 'Fim' }}
                @endif
            </p>
        </div>
        <div class="col-md-4 text-end">
            <p class="mb-0"><strong>Usuário:</strong> {{ auth()->user()->name }}</p>
            <p class="mb-0"><strong>Data:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total de Projetos</h6>
                <h3 class="mb-0">{{ $estatisticas['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card" style="border-left-color: #10b981;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Projetos Ativos</h6>
                <h3 class="mb-0 text-success">{{ $estatisticas['ativos'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card" style="border-left-color: #6c757d;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Projetos Inativos</h6>
                <h3 class="mb-0 text-secondary">{{ $estatisticas['inativos'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card" style="border-left-color: #3182ce;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total de Tarefas</h6>
                <h3 class="mb-0 text-info">{{ $estatisticas['total_tarefas'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Projetos -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Detalhamento dos Projetos</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Projeto</th>
                        <th>Status</th>
                        <th>Responsável</th>
                        <th>Data Criação</th>
                        <th class="text-center">Tarefas</th>
                        <th class="text-center">Concluídas</th>
                        <th class="text-center">Progresso</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projetos as $projeto)
                        <tr>
                            <td>
                                <strong>{{ $projeto->titulo }}</strong>
                            </td>
                            <td>
                                @if($projeto->ativo)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>{{ $projeto->responsavel ?? '-' }}</td>
                            <td>{{ $projeto->data_criacao->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $projeto->total_tarefas }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $projeto->tarefas_concluidas }}</span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $projeto->percentual_concluido }}%"
                                         aria-valuenow="{{ $projeto->percentual_concluido }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $projeto->percentual_concluido }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">Nenhum projeto encontrado com os filtros aplicados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detalhamento por Status -->
@if($projetos->count() > 0)
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Distribuição por Status</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Tarefas por Status</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Backlog:</span>
                            <strong>{{ $projetos->sum('tarefas_backlog') }}</strong>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Pendentes:</span>
                            <strong>{{ $projetos->sum('tarefas_pendentes') }}</strong>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Em Desenvolvimento:</span>
                            <strong>{{ $projetos->sum('tarefas_em_desenvolvimento') }}</strong>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Concluídas:</span>
                            <strong class="text-success">{{ $projetos->sum('tarefas_concluidas') }}</strong>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($projetos->count() > 0)
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Ativos', 'Inativos'],
                datasets: [{
                    data: [{{ $estatisticas['ativos'] }}, {{ $estatisticas['inativos'] }}],
                    backgroundColor: ['#10b981', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    @endif
</script>
@endsection

