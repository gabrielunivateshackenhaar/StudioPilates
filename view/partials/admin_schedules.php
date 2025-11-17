<?php
// partials/admin_schedules.php
// Exibe a lista de horários cadastrados (somente para admin)

$schedules = $schedules ?? []; 
?>

<div class="card shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="mb-0">Gerenciar Horários</h5>
            
            <a href="index.php?action=showScheduleForm" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Novo Horário
            </a>
        </div>

        <?php if (count($schedules) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Duração</th> 
                            <th>Vagas</th>
                            <th>Status</th>
                            <th>Criado por</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $s): ?>
                            <tr>
                                <td><?= (new DateTime($s['date']))->format('d/m/Y') ?></td>
                                <td><?= (new DateTime($s['time']))->format('H:i') ?></td>
                                <td><?= htmlspecialchars($s['duration_minutes']) ?> min</td> 
                                <td>
                                    <?php 
                                    $bookings_count = (int)($s['bookings_count'] ?? 0);
                                    $vagas_restantes = $s['capacity'] - $bookings_count;
                                    echo "{$vagas_restantes} / {$s['capacity']}";
                                    ?>
                                </td>
                                <td>
                                    <?php if ($s['active'] == 1): ?>
                                        <span class="badge bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($s['admin_name'] ?? '-') ?></td>
                                <td class="text-center">
                                    <a href="index.php?action=editSchedule&id=<?= $s['id'] ?>"
                                        class="text-secondary" title="Editar">
                                        <i class="bi bi-pencil me-2"></i></a>

                                    <a href="#" class="text-secondary" title="Excluir"
                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal<?= $s['id'] ?>">
                                        <i class="bi bi-trash"></i></a>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Nenhum horário cadastrado até o momento.
            </div>
        <?php endif; ?>
    </div>
</div>