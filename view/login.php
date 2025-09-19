<?php
// view/login.php
?>
<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <!-- Navbar -->
    <?php require __DIR__ . '/partials/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Login</h4>

                        <!-- Formulário de Login -->
                        <form action="index.php?action=login" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <p>Ainda não possui uma conta?</p>
                            <a href="index.php?action=register" class="btn btn-link">Cadastre-se</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
