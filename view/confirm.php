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

                            <div class="mt-3">
                                <small class="text-muted">Não recebeu?</small><br>
                                <a href="index.php?action=resendCode" class="text-decoration-none" style="color: var(--verde-pilates); font-weight: 500; font-size: 0.9rem;">
                                    Reenviar código
                                </a>
                            </div>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Se o código foi reenviado com sucesso
            if (urlParams.get('status') === 'resent') {
                Swal.fire({
                    icon: 'success',
                    title: 'Código Enviado!',
                    text: 'Verifique sua caixa de entrada (e spam).',
                    confirmButtonColor: '#28a745',
                    timer: 3000 // Fecha sozinho em 3s
                }).then(() => {
                    // Limpa a URL
                    window.history.replaceState(null, '', window.location.pathname + '?action=showConfirmForm');
                });
            }

            // Se houve erro no envio
            if (urlParams.get('error') === 'email_fail') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Não foi possível enviar o e-mail. Tente mais tarde.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>                                

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>