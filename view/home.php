<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <?php require __DIR__ . '/partials/head.php'; ?>

    <style>
        .img-placeholder {
            width: 100%;
            height: 300px;
            /* altura fixa para simular a imagem */
            border: 2px dashed #6c757d;
            /* cinza tracejado */
            border-radius: 0.5rem;
            /* mesmo arredondado do Bootstrap */
            background-color: #f8f9fa;
            /* cinza claro de fundo */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            /* texto cinza */
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="main-content-wrapper">
        <!-- Navbar superior -->
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <!-- Toggle Studio / Agendamento -->
        <div class="container text-center py-4">
            <?php
            $btn1Id = "btnAbout";
            $btn1Text = "Studio Pilates";
            $btn2Id = "btnScheduling";
            $btn2Text = "Agendamento";
            $conteudo1Id = "contentAbout";
            $conteudo2Id = "contentScheduling";
            $btn1Active = true;

            require __DIR__ . '/partials/btn_toggle.php';
            ?>
        </div>

        <!-- Conteúdo Sobre -->
        <div id="contentAbout" class="container my-5">
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
        <?php require __DIR__ . '/partials/calendar_view.php'; ?>
        
    </div>

    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>