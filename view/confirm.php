<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container login-container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-card">
                        <div class="card-body text-center">
                            <h4 class="login-title mb-3">Confirmação</h4>
                            
                            <p class="text-muted mb-4">
                                Entre com o código enviado para o e-mail <strong><?= htmlspecialchars($_SESSION['email_pendente'] ?? '') ?></strong> para concluir o cadastro.
                            </p>

                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger">
                                    <?= htmlspecialchars($errorMessage); ?>
                                </div>
                            <?php endif; ?>

                            <form action="index.php?action=confirmCode" method="post">
                                <div class="mb-4">
                                    <input type="text" class="form-control text-center fs-4 letter-spacing-4" 
                                           name="code" maxlength="6" required autofocus>
                                </div>
                                <button type="submit" class="btn-login-submit w-100">Confirmar</button>
                            </form>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>