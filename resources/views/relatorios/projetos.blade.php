@extends('layouts.app')

@section('title', 'Relatório de Projetos - Tasklean')

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
    .report-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
</style>

<div class="no-print mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.index') }}" class="text-decoration-none text-muted">Relatórios</a></li>
                    <li class="breadcrumb-item active">Projetos</li>
                </ol>
            </nav>
            <h1 class="h2 mb-1 fw-800 text-dark">Relatório de Projetos</h1>
            <p class="text-muted mb-0">Extração de dados consolidada para análise de performance.</p>
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

<!-- Header do Relatório (Apenas Impressão ou Visualização) -->
<div class="chart-container mb-4 py-4 bg-light bg-opacity-50 border-0">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="task-card-icon primary me-3">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <div>
                    <h5 class="fw-800 text-dark mb-0">Consolidado de Projetos</h5>
                    <p class="text-muted small mb-0">Filtros aplicados: 
                        <strong>{{ $request->status ? ucfirst($request->status) : 'Todos' }}</strong>
                        @if($request->data_inicio) | Início: {{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }} @endif
                        @if($request->data_fim) | Fim: {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }} @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <p class="mb-0 text-muted small">Gerado por <strong>{{ auth()->user()->name }}</strong></p>
            <p class="mb-0 text-muted small">Data: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Métricas de Resumo -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Total Projetos</div>
            <div class="h2 fw-800 text-dark mb-0">{{ $estatisticas['total'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Projetos Ativos</div>
            <div class="h2 fw-800 text-success mb-0">{{ $estatisticas['ativos'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Total Tarefas</div>
            <div class="h2 fw-800 text-info mb-0">{{ $estatisticas['total_tarefas'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4 h-100">
            <div class="text-muted small fw-bold text-uppercase mb-2">Taxa Conclusão</div>
            @php
                $taxa = $estatisticas['total_tarefas'] > 0 ? round(($projetos->sum('tarefas_concluidas') / $estatisticas['total_tarefas']) * 100) : 0;
            @endphp
            <div class="h2 fw-800 text-primary mb-0">{{ $taxa }}%</div>
        </div>
    </div>
</div>

<!-- Tabela Detalhada -->
<div class="chart-container p-0 overflow-hidden mb-5">
    <div class="p-4 border-bottom bg-light bg-opacity-50">
        <h6 class="fw-800 text-dark mb-0">Detalhamento dos Ativos</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50">
                    <th class="ps-4 py-3 fw-800 text-muted small text-uppercase">Título do Projeto</th>
                    <th class="py-3 fw-800 text-muted small text-uppercase">Status</th>
                    <th class="py-3 fw-800 text-muted small text-uppercase">Gestor</th>
                    <th class="py-3 fw-800 text-muted small text-uppercase text-center">Tarefas (C/T)</th>
                    <th class="pe-4 py-3 fw-800 text-muted small text-uppercase">Progresso</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projetos as $projeto)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $projeto->titulo }}</div>
                            <div class="text-muted small">Criado em {{ $projeto->data_criacao->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            <span class="report-badge {{ $projeto->ativo ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }}" style="background: var(--{{ $projeto->ativo ? 'success' : 'secondary' }}-light)">
                                {{ $projeto->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>{{ $projeto->responsavel ?? 'Não definido' }}</td>
                        <td class="text-center">
                            <span class="fw-bold text-primary">{{ $projeto->tarefas_concluidas }}</span>
                            <span class="text-muted">/ {{ $projeto->total_tarefas }}</span>
                        </td>
                        <td class="pe-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: {{ $projeto->percentual_concluido }}%"></div>
                                </div>
                                <span class="fw-bold text-dark small" style="min-width: 35px;">{{ $projeto->percentual_concluido }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="text-muted mb-0">Nenhum registro encontrado para os filtros selecionados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="chart-container">
            <h6 class="fw-800 text-dark mb-4">Volume de Tarefas por Status</h6>
            <div class="activity-list">
                @php
                    $statusMapping = [
                        ['label' => 'Backlog', 'value' => $projetos->sum('tarefas_backlog'), 'color' => 'secondary'],
                        ['label' => 'Pendentes', 'value' => $projetos->sum('tarefas_pendentes'), 'color' => 'warning'],
                        ['label' => 'Em Execução', 'value' => $projetos->sum('tarefas_em_desenvolvimento'), 'color' => 'info'],
                        ['label' => 'Concluídas', 'value' => $projetos->sum('tarefas_concluidas'), 'color' => 'success'],
                    ];
                @endphp
                @foreach($statusMapping as $item)
                    <div class="activity-item bg-light bg-opacity-50 p-3 rounded-4 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="task-card-icon {{ $item['color'] }} bg-opacity-10 me-3" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-circle-fill"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $item['label'] }}</span>
                            </div>
                            <span class="h6 fw-800 text-dark mb-0">{{ $item['value'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6 no-print">
        <div class="chart-container h-100">
            <h6 class="fw-800 text-dark mb-4">Distribuição de Status</h6>
            <div style="height: 250px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>
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
                    backgroundColor: ['#10b981', '#cbd5e1'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
    @endif
</script>
@endsection
