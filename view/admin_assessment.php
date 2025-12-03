<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container my-5">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Avaliação Postural</h2>
                    <h5 class="text-muted">Aluno: <?= htmlspecialchars($targetUser['name']) ?></h5>
                </div>
                <a href="index.php?action=admin" class="btn btn-outline-secondary">Voltar</a>
            </div>

            <form action="index.php?action=saveAdminAssessment" method="post">
                <input type="hidden" name="user_id" value="<?= $userId ?>">
                <input type="hidden" name="assessment_id" value="<?= $assessmentId ?>">

                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#anamnese" type="button">Anamnese</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#anterior" type="button">Vista Anterior</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#posterior" type="button">Vista Posterior</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#lateral" type="button">Vista Lateral</button></li>
                </ul>

                <div class="tab-content p-4 border border-top-0 bg-white rounded-bottom shadow-sm">
                    
                    // Dados Gerais
                    <div class="tab-pane fade show active" id="anamnese" role="tabpanel">
                        <?php require __DIR__ . '/partials/anamnesis_form.php'; ?>
                    </div>

                    <div class="tab-pane fade" id="anterior">
                        <p>Implementar campos da Vista Posterior...</p>
                    </div>

                    <div class="tab-pane fade" id="posterior">
                         <p>Implementar campos da Vista Posterior...</p>
                    </div>

                    <div class="tab-pane fade" id="lateral">
                         <p>Implementar campos da Vista Lateral...</p>
                    </div>

                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Salvar Avaliação</button>
                </div>

            </form>
        </div>
    </div>
    
    <script>
        <?php if (isset($_GET['status']) && $_GET['status'] == 'saved'): ?>
            Swal.fire({ icon: 'success', title: 'Salvo!', text: 'Avaliação atualizada com sucesso.', timer: 2000 });
        <?php endif; ?>
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>