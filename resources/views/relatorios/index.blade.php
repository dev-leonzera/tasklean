@extends('layouts.app')

@section('title', 'Relatórios - Tasklean')
@section('page-title', 'Relatórios')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-file-earmark-text me-2"></i>Relatórios
                </h1>
                <p class="text-muted mb-0">Gere relatórios detalhados dos seus projetos e tarefas</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Relatório de Projetos -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-folder text-success" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title">Relatório de Projetos</h5>
                <p class="card-text text-muted">Análise detalhada dos seus projetos com estatísticas e progresso</p>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProjetos">
                    <i class="bi bi-funnel me-1"></i> Gerar Relatório
                </button>
            </div>
        </div>
    </div>

    <!-- Relatório de Tarefas -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-list-task text-info" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title">Relatório de Tarefas</h5>
                <p class="card-text text-muted">Relatório completo das tarefas com filtros avançados</p>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalTarefas">
                    <i class="bi bi-funnel me-1"></i> Gerar Relatório
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros Projetos -->
<div class="modal fade" id="modalProjetos" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('relatorios.projetos') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-folder me-2"></i>Filtros - Relatório de Projetos
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Todos</option>
                            <option value="ativo">Apenas Ativos</option>
                            <option value="inativo">Apenas Inativos</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Início</label>
                            <input type="date" class="form-control" name="data_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Fim</label>
                            <input type="date" class="form-control" name="data_fim">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-file-earmark-text me-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Filtros Tarefas -->
<div class="modal fade" id="modalTarefas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('relatorios.tarefas') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-list-task me-2"></i>Filtros - Relatório de Tarefas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Projeto</label>
                            <select class="form-select" name="projeto_id">
                                <option value="">Todos os Projetos</option>
                                @foreach($projetos as $projeto)
                                    <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Todos</option>
                                <option value="backlog">Backlog</option>
                                <option value="pendente">Pendente</option>
                                <option value="em desenvolvimento">Em Desenvolvimento</option>
                                <option value="concluida">Concluída</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Criação - Início</label>
                            <input type="date" class="form-control" name="data_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Criação - Fim</label>
                            <input type="date" class="form-control" name="data_fim">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Vencimento - Início</label>
                            <input type="date" class="form-control" name="data_vencimento_inicio">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Vencimento - Fim</label>
                            <input type="date" class="form-control" name="data_vencimento_fim">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Responsável</label>
                        <input type="text" class="form-control" name="responsavel" placeholder="Nome do responsável">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="apenas_atrasadas" value="1" id="apenasAtrasadas">
                        <label class="form-check-label" for="apenasAtrasadas">
                            Apenas tarefas atrasadas
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-file-earmark-text me-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

