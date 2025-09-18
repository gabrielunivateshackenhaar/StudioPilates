<!-- view/partials/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">

        <!-- Marca / Logo à esquerda -->
        <a class="navbar-brand" href="index.php">Studio Pilates</a>

        <!-- Tudo alinhado à direita -->
        <div class="ms-auto d-flex align-items-center gap-2">

            <!-- Saudação usuário (placeholder por enquanto) -->
            <span class="me-2">
                Olá, Usuário
            </span>

            <!-- Ícone de home -->
            <a href="index.php" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Página Inicial">
                <i class="bi bi-house-door"></i>
            </a>

            <!-- Ícone de login -->
            <a href="index.php?action=login" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Fazer login">
                <i class="bi bi-person-circle"></i>
            </a>

            <!-- Botão de logout -->
            <form action="index.php?action=logout" method="post" class="m-0" data-bs-toggle="tooltip" title="Sair">
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <!-- Ícone de configuração -->
            <a href="index.php?action=admin" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Gerenciar">
                <i class="bi bi-gear"></i>
            </a>

        </div>
    </div>
</nav>
