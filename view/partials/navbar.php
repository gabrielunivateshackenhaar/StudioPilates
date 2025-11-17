<!-- view/partials/navbar.php -->
<nav class="navbar custom-navbar">
    <div class="container navbar-wrapper">

        <!-- Espaço esquerdo para manter alinhamento -->
        <div class="navbar-space"></div>

        <!-- Logo centralizada -->
        <a class="navbar-brand custom-logo" href="index.php">
            <img src="img/logo/logo_principal.png" alt="Logo Pilates e Bem-estar" class="logo-img">
        </a>

        <div class="navbar-right d-flex align-items-center gap-2">

            <!-- Ícone Home -->
            <a href="index.php" class="btn-login" title="Página Inicial">
                <i class="bi bi-house-door"></i>
            </a>

            <!-- Perfil do Usuário (se estiver logado) -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=profile" class="btn-login" title="Meu perfil">
                    <i class="bi bi-person"></i>
                </a>
            <?php else: ?>
                <!-- Faxer login (se não estiver logado) -->
                <a href="index.php?action=showLogin" class="btn-login" title="Fazer login">
                    <i class="bi bi-person"></i>
                </a>
            <?php endif; ?>

            <!-- Configurações do administrador -->
            <?php if (isset($_SESSION['user_category']) && $_SESSION['user_category'] == 1): // 1 = Categoria::ADMIN ?>
                <a href="index.php?action=admin" class="btn-login" title="Gerenciar">
                    <i class="bi bi-gear"></i>
                </a>
            <?php endif; ?>

            <!-- Ícone para sair (logout) -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="index.php?action=logout" method="post" class="m-0" title="Sair">
                    <button type="submit" class="btn-login"> 
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            <?php endif; ?>

        </div>

    </div>
</nav>
