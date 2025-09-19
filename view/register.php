<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Studio Pilates</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <?php require __DIR__ . '/partials/navbar.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Cadastro de Usuário</h3>

                        <form action="index.php?action=register" method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="confirmPassword" class="form-label">Confirme sua senha</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirme sua senha" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Sexo</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="" selected disabled>Selecione</option>
                                        <option value="0">Masculino</option>
                                        <option value="1">Feminino</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Celular</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="(99) 99999-9999" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="profession" class="form-label">Profissão (opcional)</label>
                                    <input type="text" class="form-control" id="profession" name="profession" placeholder="Sua profissão">
                                </div>

                                <div class="col-md-6">
                                    <label for="laterality" class="form-label">Lateralidade (opcional)</label>
                                    <select class="form-select" id="laterality" name="laterality">
                                        <option value="" selected disabled>Selecione</option>
                                        <option value="0">Destro</option>
                                        <option value="1">Canhoto</option>
                                        <option value="2">Ambidestro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                            </div>

                            <!-- Link para login -->
                            <div class="mt-3 text-center">
                                <small>Já possui uma conta?</small>
                                <a href="index.php?action=login" class="btn btn-link p-0">Entrar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-3 text-center">
        &copy; 2025 Studio Pilates
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
