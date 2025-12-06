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

                    <h5 class="mb-3 text-muted">Lista Detalhada</h5>
                    <?php require __DIR__ . '/partials/admin_schedules.php'; ?>
                </div>

                <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                    <?php require __DIR__ . '/partials/admin_users.php'; ?>
                </div>

            </div>
        </div>
    </div>

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

    <!-- Footer -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>