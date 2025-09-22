<!-- view/partials/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Marca / Logo à esquerda -->
        <a class="navbar-brand" href="index.php">Studio Pilates</a>

        <!-- Alinhamento à direita -->
        <div class="d-flex align-items-center gap-2">

            <?php if (isset($_SESSION['user_name'])): ?>
                <!-- Saudação usuário logado -->
                <span class="me-2">
                    Olá, <?= htmlspecialchars($_SESSION['user_name']) ?>
                </span>
            <?php endif; ?>

            <!-- Ícone de home: sempre visível -->
            <a href="index.php" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Página Inicial">
                <i class="bi bi-house-door"></i>
            </a>

            <!-- Ícone de usuário -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Edição do usuário -->
                <a href="index.php?action=profile" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Meu perfil">
                    <i class="bi bi-person-circle"></i>
                </a>
            <?php else: ?>
                <!-- Se não estiver logado, exibe login -->
                <a href="index.php?action=showLogin" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Fazer login">
                    <i class="bi bi-person-circle"></i>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_name'])): ?>
                <!-- Botão de logout -->
                <form action="index.php?action=logout" method="post" class="m-0" data-bs-toggle="tooltip" title="Sair">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>

                <!-- Ícone de configuração: apenas admins -->
                <?php if (isset($_SESSION['user_category']) && $_SESSION['user_category'] == 1): ?>
                    <a href="index.php?action=admin" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Gerenciar">
                        <i class="bi bi-gear"></i>
                    </a>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</nav>
