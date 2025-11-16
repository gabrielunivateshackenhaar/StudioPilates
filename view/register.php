<?php
// view/register.php
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>

<body>
    <div id="main-content-wrapper">

        <!-- NAVBAR -->
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <!-- CONTEÚDO -->
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">

                    <!-- CARD PRINCIPAL -->
                    <div class="register-card">

                        <!-- TÍTULO -->
                        <h3 class="register-title">Cadastro de Usuário</h3>

                        <!-- FORM -->
                        <?php
                            // Não passar $user nem $formAction para manter o comportamento padrão do registro
                            require __DIR__ . '/partials/user_form.php';
                        ?>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
