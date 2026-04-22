@extends('layouts.app')

@section('title', 'Relatório de Tarefas - Tasklean')

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
        border-bottom: 2px solid #3182ce;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        border-left: 4px solid #3182ce;
        background: #f8f9fa;
    }
</style>

<div class="no-print mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">
                <i class="bi bi-list-task me-2"></i>Relatório de Tarefas
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
                @if($request->filled('projeto_id'))
                    <strong>Projeto:</strong> {{ \App\Models\Projeto::find($request->projeto_id)->titulo ?? 'N/A' }}
                @else
                    <strong>Todos os projetos</strong>
                @endif
                @if($request->filled('status'))
                    | <strong>Status:</strong> {{ ucfirst($request->status) }}
                @endif
                @if($request->filled('apenas_atrasadas') && $request->apenas_atrasadas)
                    | <strong>Apenas atrasadas</strong>
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
                <h6 class="text-muted mb-2">Total de Tarefas</h6>
                <h3 class="mb-0">{{ $estatisticas['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card" style="border-left-color: #6c757d;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Backlog</h6>
                <h3 class="mb-0 text-secondary">{{ $estatisticas['backlog'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card" style="border-left-color: #ed8936;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Em Desenvolvimento</h6>
                <h3 class="mb-0 text-warning">{{ $estatisticas['em_desenvolvimento'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card" style="border-left-color: #10b981;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Concluídas</h6>
                <h3 class="mb-0 text-success">{{ $estatisticas['concluidas'] }}</h3>
            </div>
        </div>
    </div>
</div>

@if($estatisticas['atrasadas'] > 0)
<div class="alert alert-danger mb-4">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Atenção:</strong> {{ $estatisticas['atrasadas'] }} tarefa(s) atrasada(s) encontrada(s)
</div>
@endif

<!-- Tabela de Tarefas -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Detalhamento das Tarefas</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tarefa</th>
                        <th>Projeto</th>
                        <th>Status</th>
                        <th>Responsável</th>
                        <th>Data Criação</th>
                        <th>Data Vencimento</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarefas as $tarefa)
                        <tr>
                            <td>
                                <strong>{{ $tarefa->titulo }}</strong>
                                @if($tarefa->descricao)
                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($tarefa->descricao, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $tarefa->projeto->titulo ?? '-' }}</td>
                            <td>
                                @php
                                    $statusBadges = [
                                        'backlog' => ['bg' => 'secondary', 'text' => 'Backlog'],
                                        'pendente' => ['bg' => 'warning', 'text' => 'Pendente'],
                                        'em desenvolvimento' => ['bg' => 'info', 'text' => 'Em Desenvolvimento'],
                                        'concluida' => ['bg' => 'success', 'text' => 'Concluída'],
                                    ];
                                    $status = $statusBadges[$tarefa->status] ?? ['bg' => 'secondary', 'text' => ucfirst($tarefa->status)];
                                @endphp
                                <span class="badge bg-{{ $status['bg'] }}">{{ $status['text'] }}</span>
                            </td>
                            <td>{{ $tarefa->responsavel ?? '-' }}</td>
                            <td>{{ $tarefa->data_criacao ? $tarefa->data_criacao->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($tarefa->data_vencimento)
                                    {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($tarefa->isAtrasada())
                                    <span class="badge bg-danger">Atrasada</span>
                                @elseif($tarefa->isParaHoje())
                                    <span class="badge bg-warning">Vence Hoje</span>
                                @elseif($tarefa->isConcluida())
                                    <span class="badge bg-success">Concluída</span>
                                @else
                                    <span class="badge bg-info">Em Andamento</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">Nenhuma tarefa encontrada com os filtros aplicados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Gráfico de Distribuição -->
@if($tarefas->count() > 0)
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
                <h6 class="mb-0">Resumo</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Backlog:</span>
                            <strong>{{ $estatisticas['backlog'] }}</strong>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Pendentes:</span>
                            <strong>{{ $estatisticas['pendentes'] }}</strong>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Em Desenvolvimento:</span>
                            <strong>{{ $estatisticas['em_desenvolvimento'] }}</strong>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Concluídas:</span>
                            <strong class="text-success">{{ $estatisticas['concluidas'] }}</strong>
                        </div>
                    </li>
                    @if($estatisticas['atrasadas'] > 0)
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-danger">Atrasadas:</span>
                            <strong class="text-danger">{{ $estatisticas['atrasadas'] }}</strong>
                        </div>
                    </li>
                    @endif
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
    @if($tarefas->count() > 0)
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Backlog', 'Pendentes', 'Em Desenvolvimento', 'Concluídas'],
                datasets: [{
                    data: [
                        {{ $estatisticas['backlog'] }},
                        {{ $estatisticas['pendentes'] }},
                        {{ $estatisticas['em_desenvolvimento'] }},
                        {{ $estatisticas['concluidas'] }}
                    ],
                    backgroundColor: ['#6c757d', '#ed8936', '#3182ce', '#10b981']
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

