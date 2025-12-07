<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container my-5">
            
            <h2 class="text-center mb-4">Painel Administrativo</h2>

            <ul class="nav nav-tabs mb-4" id="adminTab" role="tablist">
                
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="schedules-tab" data-bs-toggle="tab" data-bs-target="#schedules" type="button" role="tab" aria-controls="schedules" aria-selected="true">
                        <i class="bi bi-calendar-week me-2"></i>Gestão de Horários
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">
                        <i class="bi bi-people me-2"></i>Alunos Cadastrados
                    </button>
                </li>

            </ul>

            <div class="tab-content" id="adminTabContent">
                
                <div class="tab-pane fade show active" id="schedules" role="tabpanel" aria-labelledby="schedules-tab">
                    
                    <div class="card shadow-sm mb-5 border-0">
                        <div class="card-body p-0">
                            <?php require __DIR__ . '/partials/calendar_view.php'; ?>
                        </div>
                    </div>

                    <!-- <h5 class="mb-3 text-muted">Lista Detalhada</h5> Removido horários em listagem -->
                    <!-- <?php require __DIR__ . '/partials/admin_schedules.php'; ?> -->
                </div>

                <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                    <?php require __DIR__ . '/partials/admin_users.php'; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Criação de horários rápido -->
    <div class="modal fade" id="createScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fs-6">Novo Horário Rápido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickScheduleForm">
                        <div class="mb-2">
                            <label class="form-label small text-muted">Data</label>
                            <input type="date" class="form-control" id="quickDate" name="date" required readonly style="background-color: #e9ecef;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">Hora</label>
                            <input type="time" class="form-control" id="quickTime" name="time" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small text-muted">Duração</label>
                                <input type="number" class="form-control" name="duration_minutes" value="60" min="15" step="15" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Vagas</label>
                                <input type="number" class="form-control" name="capacity" value="3" min="1" required>
                            </div>
                        </div>
                        <input type="hidden" name="active" value="1">
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success btn-sm">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gerenciamento de horários -->
    <div class="modal fade" id="manageScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Gerenciar Horário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h4 class="text-success mb-0" id="manageTimeDisplay">--:--</h4>
                        <small class="text-muted text-uppercase fw-bold" id="manageDateDisplay">---</small>
                    </div>

                    <h6 class="text-muted border-bottom pb-2 mb-3">Lista de Presença</h6>
                    <div id="manageSlotsList" class="d-flex flex-column gap-2">
                        </div>
                </div>

                <div class="modal-footer justify-content-center border-top-0 pt-0">
                    <button type="button" id="btnDeleteEntireSchedule" class="btn btn-link text-danger text-decoration-none btn-sm">
                        <i class="bi bi-trash me-1"></i> Excluir este horário
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Seletor de usuários -->
    <div class="modal fade" id="selectStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Selecionar Aluno</h5>
                    <button type="button" class="btn-close btn-close-white" id="btnBackToManage" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="input-group mb-3 sticky-top bg-white pb-2" style="top: -1rem;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 bg-light" placeholder="Buscar aluno..." id="searchStudentInput">
                    </div>

                    <div id="studentSelectionList" class="d-flex flex-column gap-2">
                        </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Geração de grade de horários -->
    <div class="modal fade" id="bulkScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Gerar Grade de Horários</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bulkScheduleForm">
                        
                        <h6 class="text-success border-bottom pb-2">Período</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small text-muted">Início</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Fim</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                        </div>

                        <h6 class="text-success border-bottom pb-2">Dias da Semana</h6>
                        <div class="mb-3 d-flex flex-wrap gap-3 pt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="1" id="seg">
                                <label class="form-check-label" for="seg">Seg</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="2" id="ter">
                                <label class="form-check-label" for="ter">Ter</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="3" id="qua">
                                <label class="form-check-label" for="qua">Qua</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="4" id="qui">
                                <label class="form-check-label" for="qui">Qui</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="5" id="sex">
                                <label class="form-check-label" for="sex">Sex</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="6" id="sab">
                                <label class="form-check-label" for="sab">Sáb</label>
                            </div>
                        </div>

                        <h6 class="text-success border-bottom pb-2">Horários</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small text-muted">Primeira Aula</label>
                                <input type="time" class="form-control" name="start_time" value="07:00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Última Aula (Início)</label>
                                <input type="time" class="form-control" name="end_time" value="20:00" required>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small text-muted">Duração (min)</label>
                                <input type="number" class="form-control" name="duration" value="60" step="15" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Vagas</label>
                                <input type="number" class="form-control" name="capacity" value="3" min="1" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success">Gerar Grade</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Aulas recorrentes -->
    <div class="modal fade" id="recurringBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Agendamento Recorrente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="recurringBookingForm">
                        
                        <div class="mb-3">
                            <label class="form-label small text-muted">Aluno</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="recurringStudentName" placeholder="Selecione um aluno..." readonly required style="background-color: #fff;">
                                <input type="hidden" name="user_id" id="recurringStudentId">
                                <button class="btn btn-outline-primary" type="button" id="btnOpenUserSelector">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small text-muted">Data de Início</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Horário da Aula</label>
                                <input type="time" class="form-control" name="time" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Dias da Semana</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php 
                                $dias = [1=>'Seg', 2=>'Ter', 3=>'Qua', 4=>'Qui', 5=>'Sex', 6=>'Sáb'];
                                foreach($dias as $val => $label): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="days[]" value="<?= $val ?>" id="r_day_<?= $val ?>">
                                        <label class="form-check-label" for="r_day_<?= $val ?>"><?= $label ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Quantidade de Aulas</label>
                            <input type="number" class="form-control" name="quantity" value="4" min="1" max="50" required>
                            <div class="form-text">O sistema buscará os próximos horários disponíveis para agendar.</div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success">Confirmar Agendamentos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>