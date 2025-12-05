<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <!-- Toggle Usuários / Horários -->
        <div class="container text-center py-4">
            <?php
            $btn1Id = "btnUsers";
            $btn1Text = "Usuários";
            $btn2Id = "btnClasses";
            $btn2Text = "Horários";
            $conteudo1Id = "contentUsers";
            $conteudo2Id = "contentClasses";
            $btn1Active = true;

            require __DIR__ . '/partials/btn_toggle.php';
            ?>
        </div>

        <div id="contentUsers" class="container my-5">
            <?php require __DIR__ . '/partials/admin_users.php'; ?>
        </div>

        <div id="contentClasses" class="container my-5" style="display:none;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Gerenciar Horários</h4>
                <a href="index.php?action=showScheduleForm" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-lg"></i> Novo (Formulário)
                </a>
            </div>

            <div class="card shadow-sm mb-5 border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-success"><i class="bi bi-calendar3 me-2"></i>Visão Geral (Clique na data para criar)</h5>
                </div>
                <div class="card-body p-0">
                    <?php require __DIR__ . '/partials/calendar_view.php'; ?>
                </div>
            </div>

            <h5 class="mb-3 text-muted">Lista Detalhada</h5>
            <?php require __DIR__ . '/partials/admin_schedules.php'; ?>

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
                                <label class="form-label small text-muted">Minutos</label>
                                <input type="number" class="form-control" name="duration_minutes" value="60" min="15" step="15" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Vagas</label>
                                <input type="number" class="form-control" name="capacity" value="3" min="1" required>
                            </div>
                        </div>

                        <input type="hidden" name="active" value="1">
                        
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success btn-sm">Criar Horário</button>
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
