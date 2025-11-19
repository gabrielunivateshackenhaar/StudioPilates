<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container my-5">
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="text-center mb-4">Olá, <?= htmlspecialchars($user['name']) ?></h2>

                    <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                                <i class="bi bi-calendar-check me-2"></i>Agendamentos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                                <i class="bi bi-clock-history me-2"></i>Histórico
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">
                                <i class="bi bi-person-gear me-2"></i>Meus Dados
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        
                        <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                            <h4 class="mb-3">Próximas Aulas</h4>
                            <?php if (count($futureBookings) > 0): ?>
                                <div class="list-group">
                                    <?php foreach ($futureBookings as $b): 
                                        $dateObj = new DateTime($b['date']);
                                        $timeObj = new DateTime($b['time']);
                                    ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1 text-primary">
                                                <?= $dateObj->format('d/m/Y') ?> às <?= $timeObj->format('H:i') ?>
                                            </h5>
                                            <small class="text-muted">Duração: <?= $b['duration_minutes'] ?> min</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-success rounded-pill">Confirmado</span>
                                            </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light text-center border">
                                    Você não tem agendamentos futuros. <br>
                                    <a href="index.php#contentScheduling" class="btn btn-sm btn-outline-primary mt-2">Agendar agora</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <h4 class="mb-3">Aulas Realizadas</h4>
                            <?php if (count($pastBookings) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data</th>
                                                <th>Horário</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pastBookings as $b): 
                                                $dateObj = new DateTime($b['date']);
                                                $timeObj = new DateTime($b['time']);
                                            ?>
                                            <tr>
                                                <td><?= $dateObj->format('d/m/Y') ?></td>
                                                <td><?= $timeObj->format('H:i') ?></td>
                                                <td><span class="badge bg-secondary">Concluída</span></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Nenhum histórico encontrado.</p>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="data" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <?php 
                                        // $user já vem do controller
                                        // $formAction já vem do controller
                                        require __DIR__ . '/partials/user_form.php'; 
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica os parâmetros da URL
            const urlParams = new URLSearchParams(window.location.search);
            
            if (urlParams.get('status') === 'success') {
                // Dispara o alerta
                Swal.fire({
                    icon: 'success',
                    title: 'Perfil Atualizado!',
                    text: 'Suas informações foram salvas com sucesso.',
                    confirmButtonColor: '#28a745', // Verde Pilates
                    confirmButtonText: 'Ok'
                }).then(() => {
                    // (Opcional) Limpa a URL para remover o ?status=success
                    // Assim, se o usuário atualizar a página, o alerta não aparece de novo
                    const newUrl = window.location.pathname + '?action=profile';
                    window.history.replaceState(null, '', newUrl);
                });
            }
        });
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>