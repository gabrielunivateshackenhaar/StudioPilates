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
        <div id="contentScheduling" class="container my-5" style="display:none;">

            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-center gap-3">
                    
                    <div class="d-flex align-items-center">
                        <span class="badge" style="background-color: #28a745; width: 20px; height: 20px;">&nbsp;</span>
                        <span class="ms-2 text-muted">Horários disponíveis</span>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="badge" style="background-color: #dc3545; width: 20px; height: 20px;">&nbsp;</span>
                        <span class="ms-2 text-muted">Horários Esgotados</span>
                    </div>
                
                </div>
            </div>

            <div id="calendar-container" class="card shadow-sm">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Aguarda o DOM (estrutura HTML) ser totalmente carregado
        document.addEventListener('DOMContentLoaded', function() {

            // Referências aos elementos HTML
            var calendarEl = document.getElementById('calendar');
            var scheduleButton = document.getElementById('btnScheduling'); // Botão "Agendamento"
            var calendar; // Variável para a instância do calendário

            // Inicializa o FullCalendar
            calendar = new FullCalendar.Calendar(calendarEl, {

                themeSystem: 'bootstrap5', // Aplica o tema visual do Bootstrap

                // Configuração dos botões do cabeçalho
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                initialView: 'dayGridMonth', // Visão inicial de "Mês"
                locale: 'pt-br', // Tradução para português

                // Textos dos botões de visualização
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia'
                },

                // Fonte dos eventos, busca os dados da URL (endpoint JSON)
                events: 'index.php?action=getSchedulesJson',

                // Função chamada ao clicar em um dia VAZIO no calendário
                dateClick: function(info) {
                    // Se estiver na visão "Mês", muda para a visão "Dia"
                    if (calendar.view.type === 'dayGridMonth') {
                        calendar.changeView('timeGridDay', info.dateStr);
                    }
                },

                // Função chamada ao clicar em um EVENTO (o fundo colorido)
                eventClick: function(info) {
                    // Se estiver na visão "Mês", muda para a visão "Dia"
                    if (calendar.view.type === 'dayGridMonth') {
                        // info.event.startStr contém a data (ex: '2025-11-14')
                        calendar.changeView('timeGridDay', info.event.startStr);
                    }
                },
            });

            // Lógica para renderizar o calendário
            // O calendário só é desenhado na primeira vez que a aba "Agendamento" é clicada
            scheduleButton.addEventListener('click', function() {
                setTimeout(function() {
                    calendar.render();
                    calendar.updateSize(); // Ajusta o tamanho
                }, 10); // Pequeno delay para garantir que o <div> está visível
            }, {
                once: true
            }); // O 'once: true' garante que isso rode apenas uma vez
        });
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>