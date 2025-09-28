<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">Editar Usuário</h3>

                            <?php require __DIR__ . '/partials/user_form.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
