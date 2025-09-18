<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Studio Pilates</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-toggle-group .btn {
            border-radius: 0;
        }
        .btn-toggle-group .btn:first-child {
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
        }
        .btn-toggle-group .btn:last-child {
            border-top-right-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .img-placeholder {
            width: 100%;
            height: 300px; /* altura fixa para simular a imagem */
            border: 2px dashed #6c757d; /* cinza tracejado */
            border-radius: 0.5rem; /* mesmo arredondado do Bootstrap */
            background-color: #f8f9fa; /* cinza claro de fundo */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d; /* texto cinza */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar superior -->
    <?php require __DIR__ . '/partials/navbar.php'; ?>

    <!-- Toggle Studio / Agendamento -->
    <header class="py-4">
        <div class="container text-center">
            <div class="btn-group btn-toggle-group" role="group">
                <button id="btnSobre" class="btn btn-primary px-4">Studio Pilates</button>
                <button id="btnAgendamento" class="btn btn-outline-secondary px-4">Agendamento</button>
            </div>
        </div>
    </header>

    <!-- Conteúdo Sobre -->
    <div id="conteudoSobre" class="container my-5">
        <div class="row align-items-center my-5">
            <div class="col-md-6">
                <div class="img-placeholder">Imagem Trabalho</div>
            </div>
            <div class="col-md-6">
                <h2>Nosso Trabalho</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aqui você descreve o trabalho do Studio de Pilates.</p>
            </div>
        </div>
        <div class="row align-items-center my-5 flex-md-row-reverse">
            <div class="col-md-6">
                <div class="img-placeholder">Imagem História</div>
            </div>
            <div class="col-md-6">
                <h2>Nossa História</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aqui você pode contar a história do Studio, missão, valores etc.</p>
            </div>
        </div>
    </div>

    <!-- Conteúdo Agendamento -->
    <div id="conteudoAgendamento" class="container my-5" style="display:none;">
        <h2 class="text-center">Agendamento de Aula</h2>
        <p class="text-center">Aqui futuramente ficará o calendário para marcar aulas.</p>
    </div>

    <!-- Rodapé -->
    <footer class="bg-light py-3 text-center">
        &copy; 2025 Studio Pilates
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Toggle -->
    <script>
        const btnSobre = document.getElementById('btnSobre');
        const btnAgendamento = document.getElementById('btnAgendamento');
        const conteudoSobre = document.getElementById('conteudoSobre');
        const conteudoAgendamento = document.getElementById('conteudoAgendamento');

        btnSobre.addEventListener('click', () => {
            conteudoSobre.style.display = 'block';
            conteudoAgendamento.style.display = 'none';
            btnSobre.classList.replace('btn-outline-secondary','btn-primary');
            btnAgendamento.classList.replace('btn-primary','btn-outline-secondary');
        });

        btnAgendamento.addEventListener('click', () => {
            conteudoSobre.style.display = 'none';
            conteudoAgendamento.style.display = 'block';
            btnAgendamento.classList.replace('btn-outline-secondary','btn-primary');
            btnSobre.classList.replace('btn-primary','btn-outline-secondary');
        });
    </script>
</body>
</html>
