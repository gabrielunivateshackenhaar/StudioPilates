<?php
// view/schedule_form.php
// $formAction será definido pelo controller (ex: saveSchedule ou updateSchedule)
// $schedule (array) será definido pelo controller em caso de edição
$formAction = $formAction ?? 'index.php?action=saveSchedule';
$isEdit = isset($schedule) && !empty($schedule['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">
                                <?= $isEdit ? 'Editar Horário' : 'Novo Horário' ?>
                            </h3>

                            <form action="<?= htmlspecialchars($formAction) ?>" method="post">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="date" class="form-label">Data</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?= htmlspecialchars($schedule['date'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="time" class="form-label">Hora</label>
                                        <input type="time" class="form-control" id="time" name="time"
                                            value="<?= htmlspecialchars($schedule['time'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="duration_minutes" class="form-label">Duração (minutos)</label>
                                        <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                                            min="15" step="15"
                                            value="<?= htmlspecialchars($schedule['duration_minutes'] ?? '60') ?>" required>
                                        <small class="text-muted">Padrão: 60 minutos (1 hora).</small>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="capacity" class="form-label">Vagas</label>
                                        <input type="number" class="form-control" id="capacity" name="capacity"
                                            min="1" max="20"
                                            value="<?= htmlspecialchars($schedule['capacity'] ?? '3') ?>" required>
                                        <small class="text-muted">Capacidade máx. de alunos.</small>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="active" class="form-label">Status</label>
                                        <select class="form-select" id="active" name="active" required>
                                            <option value="1" <?= (isset($schedule['active']) && $schedule['active'] == 1) ? 'selected' : '' ?>>
                                                Ativo (visível p/ alunos)
                                            </option>
                                            <option value="0" <?= (isset($schedule['active']) && $schedule['active'] == 0) ? 'selected' : '' ?>>
                                                Inativo
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-6 ps-2">
                                        <a href="index.php?action=admin" class="btn btn-secondary w-100">
                                            Cancelar
                                        </a>
                                    </div>
                                    <div class="col-6 pe-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <?= $isEdit ? 'Salvar Alterações' : 'Salvar Horário' ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>