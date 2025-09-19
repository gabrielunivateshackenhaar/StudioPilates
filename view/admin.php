<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/partials/navbar.php'; ?>

    <!-- Toggle Studio / Agendamento -->
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
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted">
                <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                <p class="mt-2">Gerenciamento de aulas disponível em breve.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
