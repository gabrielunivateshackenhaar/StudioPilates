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
                            <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button">
                                <i class="bi bi-calendar-check me-2"></i>Agendamentos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                                <i class="bi bi-clock-history me-2"></i>Histórico
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="health-tab" data-bs-toggle="tab" data-bs-target="#health" type="button">
                                <i class="bi bi-heart-pulse me-2"></i>Ficha de Saúde
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button">
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
                                        <span class="badge bg-success rounded-pill">Confirmado</span>
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
                                            <tr><th>Data</th><th>Horário</th><th>Status</th></tr>
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

                        <div class="tab-pane fade" id="health" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Anamnese Inicial</h4>
                                    <p class="text-muted mb-4">Preencha os dados abaixo para auxiliar no seu atendimento.</p>
                                    
                                    <form action="index.php?action=saveAnamnesis" method="post">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Profissão</label>
                                                <input type="text" class="form-control" name="profession" 
                                                    value="<?= htmlspecialchars($assessment['profession'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Lateralidade</label>
                                                <select class="form-select" name="laterality">
                                                    <option value="" selected disabled>Selecione</option>
                                                    <option value="0" <?= (isset($assessment['laterality']) && $assessment['laterality'] == 0) ? 'selected' : '' ?>>Destro</option>
                                                    <option value="1" <?= (isset($assessment['laterality']) && $assessment['laterality'] == 1) ? 'selected' : '' ?>>Canhoto</option>
                                                    <option value="2" <?= (isset($assessment['laterality']) && $assessment['laterality'] == 2) ? 'selected' : '' ?>>Ambidestro</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Altura (m)</label>
                                                <input type="text" class="form-control" name="height" placeholder="Ex: 1.70"
                                                    value="<?= htmlspecialchars($assessment['height'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Peso (kg)</label>
                                                <input type="text" class="form-control" name="weight" placeholder="Ex: 65.5"
                                                    value="<?= htmlspecialchars($assessment['weight'] ?? '') ?>">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Diagnóstico Clínico</label>
                                                <textarea class="form-control" name="diagnosis" rows="2" 
                                                    placeholder="Possui algum diagnóstico médico? (Ex: Hérnia, Escoliose...)"><?= htmlspecialchars($assessment['diagnosis'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Queixa Principal / Objetivos</label>
                                                <textarea class="form-control" name="complaint" rows="3" 
                                                    placeholder="O que te trouxe ao Pilates? Onde sente dor?"><?= htmlspecialchars($assessment['complaint'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn-register-submit w-100">Salvar Ficha</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
            
            // Alertas de Sucesso
            if (urlParams.get('status') === 'success') {
                Swal.fire({ icon: 'success', title: 'Perfil Atualizado!', confirmButtonColor: '#28a745' });
            } else if (urlParams.get('status') === 'anamnesis_saved') {
                Swal.fire({ icon: 'success', title: 'Ficha Salva!', text: 'Suas informações de saúde foram atualizadas.', confirmButtonColor: '#28a745' });
            }

            // Reabrir a aba correta se vier da URL (ex: &tab=health)
            const activeTab = urlParams.get('tab');
            if (activeTab) {
                const tabTrigger = document.querySelector(`#${activeTab}-tab`);
                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
            
            // Limpar URL
            if (urlParams.has('status') || urlParams.has('tab')) {
                const newUrl = window.location.pathname + '?action=profile';
                window.history.replaceState(null, '', newUrl);
            }
        });
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>