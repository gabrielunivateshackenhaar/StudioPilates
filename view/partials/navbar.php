<!-- view/partials/navbar.php -->
<nav class="navbar custom-navbar">
    <div class="container navbar-wrapper">

        <!-- Espaço esquerdo para manter alinhamento -->
        <div class="navbar-space"></div>

        <!-- Logo centralizada -->
        <a class="navbar-brand custom-logo" href="index.php">
            <img src="img/logo/logo_principal.png" alt="Logo Pilates e Bem-estar" class="logo-img">
        </a>

        <!-- Ícone de login à direita -->
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=profile" class="btn-login" title="Meu perfil">
                    <i class="bi bi-person"></i>
                </a>
            <?php else: ?>
                <a href="index.php?action=showLogin" class="btn-login" title="Fazer login">
                    <i class="bi bi-person"></i>
                </a>
            <?php endif; ?>
        </div>

    </div>
</nav>
