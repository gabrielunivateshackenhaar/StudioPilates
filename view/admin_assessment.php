<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <div id="main-content-wrapper">
        <?php require __DIR__ . '/partials/navbar.php'; ?>

        <div class="container my-5">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Avaliação Postural</h2>
                    <h5 class="text-muted">Aluno: <?= htmlspecialchars($targetUser['name']) ?></h5>
                </div>
                <a href="index.php?action=admin" class="btn btn-outline-secondary">Voltar</a>
            </div>

            <form action="index.php?action=saveAdminAssessment" method="post">
                <input type="hidden" name="user_id" value="<?= $userId ?>">
                <input type="hidden" name="assessment_id" value="<?= $assessmentId ?>">

                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#anamnese" type="button">Anamnese</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#anterior" type="button">Vista Anterior</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#posterior" type="button">Vista Posterior</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#lateral" type="button">Vista Lateral</button></li>
                </ul>

                <div class="tab-content p-4 border border-top-0 bg-white rounded-bottom shadow-sm">
                    
                    <div class="tab-pane fade show active" id="anamnese" role="tabpanel">
                        <?php require __DIR__ . '/partials/anamnesis_form.php'; ?>
                    </div>

                    <?php
                        function renderRadioGroup($title, $name, $options, $selected) {
                            echo "<h5 class='mb-3 text-success border-bottom pb-2 mt-4'>{$title}</h5>";
                            echo "<div class='mb-3'>";

                            foreach ($options as $value => $label) {
                                $checked = ($selected === $value) ? 'checked' : '';
                                echo "
                                    <div class='form-check form-check-inline'>
                                        <input class='form-check-input' type='radio' name='{$name}' value='{$value}' {$checked}>
                                        <label class='form-check-label'>{$label}</label>
                                    </div>
                                ";
                            }

                            echo "</div>";
                        }
                    ?>

                    <div class="tab-pane fade" id="anterior">

                        <?php 
                            $ant = $postural['anterior'] ?? []; 

                            renderRadioGroup(
                                "Cabeça",
                                "postural[anterior][cabeca]",
                                [
                                    "alinhada"     => "Alinhada",
                                    "inclinada_d"  => "Inclinada à D",
                                    "inclinada_e"  => "Inclinada à E",
                                    "rodada_d"     => "Rodada à D",
                                    "rodada_e"     => "Rodada à E",
                                ],
                                $ant['cabeca'] ?? null
                            );

                            renderRadioGroup(
                                "Altura dos Ombroms",
                                "postural[anterior][ombros]",
                                [
                                    "simetricos" => "Simétricos",
                                    "direito_alto" => "Direito + alto",
                                    "esquerdo_alto" => "Esquerdo + alto",
                                ],
                                $ant['ombros'] ?? null
                            );

                            renderRadioGroup(
                                "Altura das Mãos",
                                "postural[anterior][maos]",
                                [
                                    "simetricos" => "Simétricos",
                                    "direito_alto" => "Direito + alto",
                                    "esquerdo_alto" => "Esquerdo + alto",
                                ],
                                $ant['maos'] ?? null
                            );

                            renderRadioGroup(
                                "Rotação do Tronco",
                                "postural[anterior][tronco]",
                                [
                                    "a_esquerda" => "À esquerda",
                                    "a_direita" => "À direita",
                                    "ausente" => "Ausente",
                                ],
                                $ant['tronco'] ?? null
                            );

                            renderRadioGroup(
                                "Ângulo de Tales",
                                "postural[anterior][tales]",
                                [
                                    "simetrico" => "Simétrico",
                                    "maior_direita" => "Maior à direita",
                                    "maior_esquerda" => "Maior à esquerda",
                                ],
                                $ant['tales'] ?? null
                            );

                            renderRadioGroup(
                                "Cicatriz Umbilical",
                                "postural[anterior][umbilical]",
                                [
                                    "alinhada" => "Alinhada",
                                    "desvio_direita" => "Desvio à direita",
                                    "desvio_esquerda" => "Desvio à esquerda",
                                ],
                                $ant['umbilical'] ?? null
                            );

                            renderRadioGroup(
                                "Altura das C. Ilíacas",
                                "postural[anterior][iliacas]",
                                [
                                    "simetricos" => "Simétricos",
                                    "direito_alto" => "Direito + alto",
                                    "esquerdo_alto" => "Esquerdo + alto",
                                ],
                                $ant['iliacas'] ?? null
                            );

                            renderRadioGroup(
                                "Joelhos",
                                "postural[anterior][joelhos]",
                                [
                                    "valgo" => "Valgo",
                                    "varo" => "Varo",
                                    "normal" => "Normal",
                                ],
                                $ant['joelhos'] ?? null
                            );

                            renderRadioGroup(
                                "Tornozelos",
                                "postural[anterior][tornozelos]",
                                [
                                    "valgo" => "Valgo",
                                    "varo" => "Varo",
                                    "normal" => "Normal",
                                ],
                                $ant['tornozelos'] ?? null
                            );

                            renderRadioGroup(
                                "Pés",
                                "postural[anterior][pes]",
                                [
                                    "planos" => "Planos",
                                    "cavos" => "Cavos",
                                    "normal" => "Normal",
                                ],
                                $ant['pes'] ?? null
                            );
                        ?>

                    </div>

                    <div class="tab-pane fade" id="posterior">

                    <?php 

                        $post = $postural['posterior'] ?? [];

                        renderRadioGroup(
                            "Altura das Escápulas",
                            "postural[posterior][altura_escapulas]",
                            [
                                "simetricos" => "Simetricos",
                                "direito_alto" => "Direito + alto",
                                "esquerdo_alto" => "Esquerdo + alto",
                            ],
                            $post['altura_escapulas'] ?? null
                        );

                        renderRadioGroup(
                            "Escápula Alada",
                            "postural[posterior][escapula_alada]",
                            [
                                "a_direita" => "À direita",
                                "a_esquerda" => "À esquerda",
                                "ausente" => "Ausente",
                            ],
                            $post['escapula_alada'] ?? null
                        );

                        renderRadioGroup(
                            "Gibosidade Torácica",
                            "postural[posterior][gibosidade]",
                            [
                                "a_direita" => "À direita",
                                "a_esquerda" => "À esquerda",
                                "ausente" => "Ausente",
                                "bilateral_direita" => "Bilateral > à direita",
                                "bilateral_esquerda" => "Bilateral > à esquerda",
                            ],
                            $post['gibosidade'] ?? null
                        );

                        renderRadioGroup(
                            "Pregas Glúteas",
                            "postural[posterior][pregas_gluteas]",
                            [
                                "simetricos" => "Simétricos",
                                "direito_alto" => "Direito + alto",
                                "esquerdo_alto" => "Esquerdo + alto",
                                "profunda_direita" => "+ profunda à direita",
                                "profunda_esquerda" => "+ profunda à esquerda",
                            ],
                            $post['pregas_gluteas'] ?? null
                        );

                        renderRadioGroup(
                            "Pregas Poplíteas",
                            "postural[posterior][pregas_popliteas]",
                            [
                                "simetricos" => "Simétricos",
                                "direito_alto" => "Direito + alto",
                                "esquerdo_alto" => "Esquerdo + alto",
                                "profunda_direita" => "+ profunda à direita",
                                "profunda_esquerda" => "+ profunda à esquerda",
                            ],
                            $post['pregas_popliteas'] ?? null
                        );

                        renderRadioGroup(
                            "Coluna Lombar com Concavidade",
                            "postural[posterior][lombar_concavidade]",
                            [
                                "a_direita" => "À direita",
                                "a_esquerda" => "À esquerda",
                                "ausente" => "Ausente",
                            ],
                            $post['lombar_concavidade'] ?? null
                        );

                        renderRadioGroup(
                            "Coluna Torácia com Concavidade",
                            "postural[posterior][toracica_concavidade]",
                            [
                                "a_direita" => "À direita",
                                "a_esquerda" => "À esquerda",
                                "ausente" => "Ausente",
                            ],
                            $post['toracica_concavidade'] ?? null
                        );

                        renderRadioGroup(
                            "Coluna Cervical com Concavidade",
                            "postural[posterior][cervical_concavidade]",
                            [
                                "a_direita" => "À direita",
                                "a_esquerda" => "À esquerda",
                                "ausente" => "Ausente",
                            ],
                            $post['cervical_concavidade'] ?? null
                        );
                    ?>

                    </div>

                    <div class="tab-pane fade" id="lateral">
                         
                        <?php

                            $lat = $postural['lateral_direita'] ?? [];

                            renderRadioGroup(
                                "Cabeça",
                                "postural[lateral_direita][cabeca]",
                                [
                                    "anteriorizada" => "Anteriorizada",
                                    "posteriorizada" => "Posteriorizada",
                                    "normal" => "Normal",
                                ],
                                $lat['cabeca'] ?? null
                            );

                            renderRadioGroup(
                                "Coluna Cervical",
                                "postural[lateral_direita][coluna_cervical]",
                                [
                                    "hiperlordose" => "Hiperlordose",
                                    "retificada" => "Retificada",
                                    "normal" => "Normal",
                                ],
                                $lat['coluna_cervical'] ?? null
                            );

                            renderRadioGroup(
                                "Ombro",
                                "postural[lateral_direita][ombro]",
                                [
                                    "protusos" => "Protusos",
                                    "retraidos" => "Retraídos",
                                    "normal" => "Normal",
                                ],
                                $lat['ombro'] ?? null
                            );

                            renderRadioGroup(
                                "Membro Superior Dir.",
                                "postural[lateral_direita][membro_sup_dir]",
                                [
                                    "anteriorizado" => "Anteriorizado",
                                    "posteriorizado" => "Posteriorizado",
                                    "normal" => "Normal",
                                ],
                                $lat['membro_sup_dir'] ?? null
                            );

                            renderRadioGroup(
                                "Coluna torácia",
                                "postural[lateral_direita][coluna_toracica]",
                                [
                                    "curvo" => "Curvo",
                                    "plano" => "Plano",
                                    "normal" => "Normal",
                                ],
                                $lat['coluna_toracica'] ?? null
                            );

                            renderRadioGroup(
                                "Rotação de Tronco",
                                "postural[lateral_direita][rotacao_tronco]",
                                [
                                    "direita" => "Direita",
                                    "esquerda" => "Esquerda",
                                    "normal" => "Normal",
                                ],
                                $lat['rotacao_tronco'] ?? null
                            );

                            renderRadioGroup(
                                "Abdomen",
                                "postural[lateral_direita][abdomen]",
                                [
                                    "protuso" => "Protuso",
                                    "ptose" => "Ptose",
                                ],
                                $lat['abdomen'] ?? null
                            );

                            renderRadioGroup(
                                "Coluna Lombar",
                                "postural[lateral_direita][coluna_lombar]",
                                [
                                    "hiperlordose" => "Hiperlordose",
                                    "retificada" => "Retificada",
                                    "normal" => "Normal",
                                ],
                                $lat['coluna_lombar'] ?? null
                            );

                            renderRadioGroup(
                                "Pelve",
                                "postural[lateral_direita][pelve]",
                                [
                                    "antevertida" => "Antevertida",
                                    "retrovertida" => "Retrovertida",
                                    "normal" => "Normal",
                                ],
                                $lat['pelve'] ?? null
                            );

                            renderRadioGroup(
                                "Quadril",
                                "postural[lateral_direita][quadril]",
                                [
                                    "fletido" => "Fletido",
                                    "normal" => "Normal",
                                ],
                                $lat['quadril'] ?? null
                            );

                            renderRadioGroup(
                                "Joelho",
                                "postural[lateral_direita][joelho]",
                                [
                                    "fletido" => "Fletido",
                                    "normal" => "Normal",
                                ],
                                $lat['joelho'] ?? null
                            );

                        ?>

                    </div>

                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Salvar Avaliação</button>
                </div>

            </form>
        </div>
    </div>
    
    <script>
        <?php if (isset($_GET['status']) && $_GET['status'] == 'saved'): ?>
            Swal.fire({ icon: 'success', title: 'Salvo!', text: 'Avaliação atualizada com sucesso.', timer: 2000 });
        <?php endif; ?>
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>