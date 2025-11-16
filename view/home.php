<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>

<body>

    <div id="main-content-wrapper">

        <!-- NAVBAR -->
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <!-- TOGGLE STUDIO / AGENDAMENTO -->
        <div class="container text-center py-4">
            <?php
                $btn1Id = "btnAbout";
                $btn1Text = "Sobre";
                $btn2Id = "btnScheduling";
                $btn2Text = "Agendamento";
                $conteudo1Id = "contentAbout";
                $conteudo2Id = "contentScheduling";
                $btn1Active = true;
                require __DIR__ . '/partials/btn_toggle.php';
            ?>
        </div>

        <!-- HOME CONTENT  -->
        <div id="contentAbout">

            <div class="container section-home">

                <!-- SEÇÃO 1 — TRABALHO E FORMAÇÃO -->
                <section class="secao-sobre-talya">
                    <div class="sobre-wrapper">

                        <div class="col-md-6">
                            <div class="card-imagem">
                                <img src="img/img_talya/principal02.jpg" alt="Foto Talya Portaluppi" class="foto-talya">
                            </div>
                        </div>

                        <div class="col-md-6 sobre-texto">
                            <h2 class="section-title">Sobre Talya Portaluppi</h2>
                            <p class="section-text">
                                Sou Talya Portaluppi, tenho 28 anos e sou natural de Guaporé. Trabalho com Pilates há quatro anos e, desde então, encontrei na área uma forma de unir movimento, cuidado e autoestima. Minha atuação é baseada em um olhar individualizado, prezando pela segurança, pela técnica e pela evolução gradual e consciente de cada aluno.
                            </p>
                            <p class="section-text">
                                Ao longo da minha trajetória, busquei formações e cursos complementares para ampliar minha visão e oferecer um trabalho mais completo. Acredito que o Pilates vai muito além de exercício físico: ele envolve presença, equilíbrio, autoconhecimento e bem-estar. Meu propósito é proporcionar um ambiente acolhedor e motivador, onde cada aluno se sinta respeitado, confiante e capaz de atingir seus objetivos.
                            </p>
                        </div>

                    </div>
                </section>

            </div>

            <!-- SEÇÃO 2 — HISTÓRIA -->
            <section class="secao-faixa-verde historia-talya">

                <!-- Onda topo -->
                <div class="onda-superior">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none"><path fill="#ffffffff" d="M0,64L40,69.3C80,75,160,85,240,80C320,75,400,53,480,48C560,43,640,53,720,58.7C800,64,880,64,960,58.7C1040,53,1120,43,1200,42.7C1280,43,1360,53,1400,58.7L1440,64V0H1400C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0H0Z"></path></svg>
                </div>

                <div class="container bloco-historia">
                    <div class="row align-items-center flex-md-row-reverse">

                        <div class="col-md-6">
                            <div class="card-imagem">
                                <img src="img/img_talya/principal01.jpg" alt="Foto Talya – Formação" class="foto-formacao">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h2 class="titulo-secao">Minha Formação</h2>
                            <p class="texto-secao">
                                Sou formada em <strong>Educação Física pela Universidade do Vale do Taquari (Univates)</strong>, em Lajeado - RS, instituição onde tive a oportunidade de aprofundar meus estudos em movimento humano, reabilitação, biomecânica e qualidade de vida. Durante minha graduação, encontrei no Pilates uma área que unia técnica, propósito e cuidado, o que me motivou a buscar especialização e atuação profissional direcionada.
                            </p>
                            <p class="texto-secao">
                                Meu registro profissional é <strong>CREF 034164-G/RS</strong>, garantindo minha atuação dentro dos padrões éticos e legais da profissão. Desde então, sigo estudando, participando de cursos, workshops e atualizações para aprimorar meu olhar clínico e oferecer um atendimento humanizado, seguro e baseado em evidências.
                            </p>
                            <p class="texto-secao">
                                Acredito que a formação não termina com o diploma; ela continua diariamente na prática, na troca com os alunos e no compromisso com o desenvolvimento físico, mental e emocional de cada pessoa que confia no meu trabalho.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Onda inferior -->
                <div class="onda-inferior">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none"><path fill="#ffffffff" d="M0,0L40,5.3C80,11,160,21,240,21.3C320,21,400,11,480,16C560,21,640,43,720,53.3C800,64,880,64,960,58.7C1040,53,1120,43,1200,42.7C1280,43,1360,53,1400,58.7L1440,64V120H1400C1360,120,1280,120,1200,120C1120,120,1040,120,960,120C880,120,800,120,720,120C640,120,560,120,480,120C400,120,320,120,240,120C160,120,80,120,40,120H0Z"></path></svg>
                </div>

            </section>

            <!-- SEÇÃO 3 — PARA QUEM É O PILATES -->
            <div class="container">
                <section class="home-section home-section-publico text-center">

                    <h2 class="section-title mb-4">Para quem é o Pilates?</h2>
                    <p class="section-text mb-5">
                        O Pilates é uma prática acessível e recomendada para diferentes fases, perfis e necessidades. Seja para quem busca iniciar uma rotina de exercícios com segurança, aliviar dores, melhorar condicionamento físico ou acompanhar processos de reabilitação, o método oferece fortalecimento, consciência corporal, estabilidade, mobilidade e bem-estar integral. O foco é sempre respeitar os limites individuais e evoluir de forma progressiva e personalizada.
                    </p>

                    <div class="publico-cards">

                        <div class="publico-card-wrapper">
                            <div class="publico-card">
                                <i class="bi bi-person icon-publico"></i>
                                <h4>Iniciantes</h4>
                                <p>Pessoas que desejam iniciar atividade física guiada e segura.</p>
                            </div>
                        </div>

                        <div class="publico-card-wrapper">
                            <div class="publico-card">
                                <i class="bi bi-people icon-publico"></i>
                                <h4>Quem sente dores</h4>
                                <p>Indicado para quem sente desconfortos articulares e musculares.</p>
                            </div>
                        </div>

                        <div class="publico-card-wrapper">
                            <div class="publico-card">
                                <i class="bi bi-heart-pulse icon-publico"></i>
                                <h4>Condicionamento</h4>
                                <p>Melhora da postura, força, flexibilidade e respiração.</p>
                            </div>
                        </div>

                        <div class="publico-card-wrapper">
                            <div class="publico-card">
                                <i class="bi bi-activity icon-publico"></i>
                                <h4>Reabilitação</h4>
                                <p>Apoio durante retorno a atividades mediante liberação profissional.</p>
                            </div>
                        </div>

                    </div>
                </section>
            </div>

            <!-- CTA FINAL PREMIUM COM ONDAS -->
            <section class="secao-faixa-verde cta-final">

                <div class="onda-superior">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none"><path fill="#ffffffff" d="M0,64L40,69.3C80,75,160,85,240,80C320,75,400,53,480,48C560,43,640,53,720,58.7C800,64,880,64,960,58.7C1040,53,1120,43,1200,42.7C1280,43,1360,53,1400,58.7L1440,64V0H1400C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0H0Z"></path></svg>
                </div>

                <div class="container text-center bloco-cta">
                    <h2 class="titulo-secao">Pronta para começar?</h2>
                    <p class="texto-secao">Agende sua primeira aula e conheça o Studio Pilates.</p>
                    <a href="index.php?action=showLogin" class="cta-btn">Quero agendar minha aula</a>
                </div>

                <div class="onda-inferior">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none"><path fill="#ffffffff" d="M0,0L40,5.3C80,11,160,21,240,21.3C320,21,400,11,480,16C560,21,640,43,720,53.3C800,64,880,64,960,58.7C1040,53,1120,43,1200,42.7C1280,43,1360,53,1400,58.7L1440,64V120H1400C1360,120,1280,120,1200,120C1120,120,1040,120,960,120C880,120,800,120,720,120C640,120,560,120,480,120C400,120,320,120,240,120C160,120,80,120,40,120H0Z"></path></svg>
                </div>

            </section>

        </div>

        <!-- CALENDÁRIO -->
        <?php require __DIR__ . '/partials/calendar_view.php'; ?>

    </div>

    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
