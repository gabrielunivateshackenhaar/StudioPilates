<?php
// view/login.php
?>
<!DOCTYPE html>
<html>

<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>

<body>
    <div id="main-content-wrapper">
        <!-- Navbar -->
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container login-container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-card">
                        <div class="card-body">
                            <h4 class="login-title">Login</h4>

                            <?php if (!empty($errorMessage)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($errorMessage); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <!-- Formulário de Login -->
                            <form action="index.php?action=login" method="post" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" 
                                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                                    <div class="invalid-feedback">Digite um e-mail válido (ex: usuario@email.com).</div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Senha</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" 
                                        value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i id="eyeIcon" class="bi bi-eye"></i>
                                        </button>
                                        <div class="invalid-feedback">Informe sua senha.</div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-login-submit w-100">Entrar</button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <span>Ainda não possui uma conta?</span><br>
                                <a href="index.php?action=register" class="link-register">Cadastre-se</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

    <style>
        .was-validated .form-control:valid,
        .form-control.is-valid {
            border-color: #ced4da !important;
            background-image: none !important;
            box-shadow: none !important;
        }
    </style>

    <script>
        const toggleButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleButton.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            const forms = document.querySelectorAll('.needs-validation');

            forms.forEach(form => {
                form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
                }, false);
            });
        });
    </script>

</body>

</html>