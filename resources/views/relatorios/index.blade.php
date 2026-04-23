@extends('layouts.app')

@section('title', 'Relatórios - Tasklean')
@section('page-title', 'Relatórios')

@section('content')
<!-- Page Header -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    Central de Inteligência 📊
                </h1>
                <p class="text-muted mb-0">Extraia insights, analise o desempenho e gere documentos oficiais.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Relatório de Projetos -->
    <div class="col-lg-6">
        <div class="chart-container h-100 d-flex flex-column p-0 overflow-hidden border-0 shadow-sm card-premium">
            <div class="p-5 text-center flex-grow-1">
                <div class="task-card-icon success mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h4 class="fw-800 text-dark mb-3">Relatório de Projetos</h4>
                <p class="text-muted mb-4 px-lg-5">Visão consolidada da saúde dos seus projetos, cronogramas e taxas de conclusão.</p>
            </div>
            <div class="p-4 bg-light border-top text-center">
                <button class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProjetos">
                    <i class="bi bi-gear-fill me-2"></i> Configurar e Gerar
                </button>
            </div>
        </div>
    </div>

    <!-- Relatório de Tarefas -->
    <div class="col-lg-6">
        <div class="chart-container h-100 d-flex flex-column p-0 overflow-hidden border-0 shadow-sm card-premium">
            <div class="p-5 text-center flex-grow-1">
                <div class="task-card-icon info mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-list-check"></i>
                </div>
                <h4 class="fw-800 text-dark mb-3">Relatório de Tarefas</h4>
                <p class="text-muted mb-4 px-lg-5">Análise granular de produtividade, gargalos e prazos de entrega individuais.</p>
            </div>
            <div class="p-4 bg-light border-top text-center">
                <button class="btn btn-info px-5 py-2 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTarefas">
                    <i class="bi bi-gear-fill me-2"></i> Configurar e Gerar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros Projetos -->
<div class="modal fade" id="modalProjetos" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('relatorios.projetos') }}" method="GET">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 text-dark mb-0">Parâmetros de Projetos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Status do Projeto</label>
                        <select class="form-select border-0 bg-light p-3" name="status">
                            <option value="">Todos (Ativos e Inativos)</option>
                            <option value="ativo">Apenas Ativos</option>
                            <option value="inativo">Apenas Inativos</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Data Inicial</label>
                            <input type="date" class="form-control border-0 bg-light p-3" name="data_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Data Final</label>
                            <input type="date" class="form-control border-0 bg-light p-3" name="data_fim">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-file-earmark-pdf me-2"></i> Gerar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Filtros Tarefas -->
<div class="modal fade" id="modalTarefas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('relatorios.tarefas') }}" method="GET">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 text-dark mb-0">Parâmetros de Tarefas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Filtrar por Projeto</label>
                            <select class="form-select border-0 bg-light p-3" name="projeto_id">
                                <option value="">Todos os Projetos</option>
                                @foreach($projetos as $projeto)
                                    <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Status da Tarefa</label>
                            <select class="form-select border-0 bg-light p-3" name="status">
                                <option value="">Todos os Status</option>
                                <option value="backlog">Backlog</option>
                                <option value="pendente">Pendente</option>
                                <option value="em desenvolvimento">Em Desenvolvimento</option>
                                <option value="concluida">Concluída</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Período de Criação (Início)</label>
                            <input type="date" class="form-control border-0 bg-light p-3" name="data_inicio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Período de Criação (Fim)</label>
                            <input type="date" class="form-control border-0 bg-light p-3" name="data_fim">
                        </div>
                        <div class="col-12">
                            <div class="activity-item bg-light border-0 p-4 rounded-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Apenas Tarefas Atrasadas</h6>
                                        <p class="text-muted small mb-0">Filtrar apenas itens que ultrapassaram o prazo de vencimento.</p>
                                    </div>
                                    <div class="form-check form-switch p-0 m-0">
                                        <input class="form-check-input ms-0 mt-1" type="checkbox" name="apenas_atrasadas" value="1" id="apenasAtrasadas" style="width: 3rem; height: 1.5rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
