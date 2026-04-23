@extends('layouts.app')

@section('title', 'Relatório de Tarefas - Tasklean')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .chart-container { 
            box-shadow: none !important; 
            border: 1px solid #eee !important;
            margin-bottom: 2rem !important;
        }
        .metric-card { border: 1px solid #eee !important; }
    }
</style>

<div class="no-print mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.index') }}" class="text-decoration-none text-muted">Relatórios</a></li>
                    <li class="breadcrumb-item active">Tarefas</li>
                </ol>
            </nav>
            <h1 class="h2 mb-1 fw-800 text-dark">Relatório de Tarefas</h1>
            <p class="text-muted mb-0">Listagem analítica e detalhada do backlog e progresso.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill shadow-sm">
                <i class="bi bi-printer me-2"></i> Imprimir / PDF
            </button>
            <a href="{{ route('relatorios.index') }}" class="btn btn-secondary px-4 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i> Voltar
            </a>
        </div>
    </div>
</div>

<!-- Header do Relatório -->
<div class="chart-container mb-4 py-4 bg-light bg-opacity-50 border-0">
    <div class="row align-items-center">
        <div class="col-md-7">
            <div class="d-flex align-items-center">
                <div class="task-card-icon info me-3">
                    <i class="bi bi-list-columns-reverse"></i>
                </div>
                <div>
                    <h5 class="fw-800 text-dark mb-0">Inventário de Atividades</h5>
                    <p class="text-muted small mb-0">Filtros: 
                        <strong>{{ $request->projeto_id ? \App\Models\Projeto::find($request->projeto_id)->titulo : 'Todos Projetos' }}</strong>
                        @if($request->status) | Status: {{ ucfirst($request->status) }} @endif
                        @if($request->apenas_atrasadas) | <span class="text-danger fw-bold">Apenas Atrasadas</span> @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end">
            <p class="mb-0 text-muted small">Gerado por <strong>{{ auth()->user()->name }}</strong></p>
            <p class="mb-0 text-muted small">Data: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Métricas -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Total Tarefas</div>
            <div class="h2 fw-800 text-dark mb-0">{{ $estatisticas['total'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Backlog / Pendentes</div>
            <div class="h2 fw-800 text-warning mb-0">{{ $estatisticas['backlog'] + $estatisticas['pendentes'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Concluídas</div>
            <div class="h2 fw-800 text-success mb-0">{{ $estatisticas['concluidas'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4 h-100 border-danger bg-soft-danger bg-opacity-10">
            <div class="text-danger small fw-bold text-uppercase mb-2">Atrasadas</div>
            <div class="h2 fw-800 text-danger mb-0">{{ $estatisticas['atrasadas'] }}</div>
        </div>
    </div>
</div>

<!-- Tabela -->
<div class="chart-container p-0 overflow-hidden mb-5">
    <div class="p-4 border-bottom bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
        <h6 class="fw-800 text-dark mb-0">Listagem Detalhada</h6>
        @if($estatisticas['atrasadas'] > 0)
            <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Atenção: {{ $estatisticas['atrasadas'] }} em atraso
            </span>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50">
                    <th class="ps-4 py-3 fw-800 text-muted small text-uppercase">Título / Descrição</th>
                    <th class="py-3 fw-800 text-muted small text-uppercase">Projeto</th>
                    <th class="py-3 fw-800 text-muted small text-uppercase">Status</th>
                    <th class="py-3 fw-800 text-muted small text-uppercase">Responsável</th>
                    <th class="pe-4 py-3 fw-800 text-muted small text-uppercase text-end">Vencimento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarefas as $tarefa)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $tarefa->titulo }}</div>
                            @if($tarefa->descricao)
                                <div class="text-muted small text-truncate" style="max-width: 300px;">{{ $tarefa->descricao }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $tarefa->projeto->titulo ?? '-' }}</span>
                        </td>
                        <td>
                            @php
                                $statusColor = match($tarefa->status) {
                                    'backlog' => 'secondary',
                                    'pendente' => 'warning',
                                    'em desenvolvimento' => 'info',
                                    'concluida' => 'success',
                                    default => 'primary'
                                };
                            @endphp
                            <span class="badge-premium {{ $statusColor }} py-1 px-3" style="font-size: 0.65rem;">
                                {{ strtoupper($tarefa->status) }}
                            </span>
                        </td>
                        <td>{{ $tarefa->responsavel ?? 'Não atribuída' }}</td>
                        <td class="pe-4 text-end">
                            @if($tarefa->data_vencimento)
                                <div class="fw-bold {{ $tarefa->isAtrasada() ? 'text-danger' : 'text-dark' }}">
                                    {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                </div>
                                @if($tarefa->isAtrasada())
                                    <span class="badge bg-soft-danger text-danger rounded-pill px-2 py-0.5" style="font-size: 0.6rem;">ATRASADA</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="text-muted mb-0">Nenhuma tarefa encontrada para os critérios informados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6 no-print">
        <div class="chart-container h-100">
            <h6 class="fw-800 text-dark mb-4">Volume por Status</h6>
            <div style="height: 250px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-container">
            <h6 class="fw-800 text-dark mb-4">Resumo Executivo</h6>
            <div class="activity-list">
                @php
                    $summaryItems = [
                        ['label' => 'Aguardando Início', 'count' => $estatisticas['backlog'] + $estatisticas['pendentes'], 'color' => 'warning'],
                        ['label' => 'Em Produção', 'count' => $estatisticas['em_desenvolvimento'], 'color' => 'info'],
                        ['label' => 'Finalizadas', 'count' => $estatisticas['concluidas'], 'color' => 'success'],
                        ['label' => 'Críticas (Atrasadas)', 'count' => $estatisticas['atrasadas'], 'color' => 'danger'],
                    ];
                @endphp
                @foreach($summaryItems as $item)
                    <div class="activity-item bg-light bg-opacity-50 p-3 rounded-4 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">{{ $item['label'] }}</span>
                            <span class="h6 fw-800 text-{{ $item['color'] }} mb-0">{{ $item['count'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($tarefas->count() > 0)
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Backlog', 'Pendente', 'Em Dev', 'Concluída'],
                datasets: [{
                    data: [
                        {{ $estatisticas['backlog'] }},
                        {{ $estatisticas['pendentes'] }},
                        {{ $estatisticas['em_desenvolvimento'] }},
                        {{ $estatisticas['concluidas'] }}
                    ],
                    backgroundColor: ['#cbd5e1', '#fbbf24', '#38bdf8', '#34d399'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
    @endif
</script>
@endsection
