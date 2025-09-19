<!-- view/partials/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Studio Pilates</a>

        <div class="ms-auto d-flex align-items-center gap-2">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Se logado -->
                <span class="me-2">
                    Olá, <?= htmlspecialchars($_SESSION['user_name']); ?>
                </span>

                <!-- Logout -->
                <form action="index.php?action=logout" method="post" class="m-0" data-bs-toggle="tooltip" title="Sair">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>

                <!-- Se for admin -->
                <?php if ($_SESSION['user_category'] == Category::ADMIN->value): ?>
                    <a href="index.php?action=admin" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Gerenciar">
                        <i class="bi bi-gear"></i>
                    </a>
                <?php endif; ?>

            <?php else: ?>
                <!-- Se NÃO logado -->
                <a href="index.php?action=showLogin" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Fazer login">
                    <i class="bi bi-person-circle"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
