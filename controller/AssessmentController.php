<?php
// controller/AssessmentController.php

require_once __DIR__ . '/../model/Assessment.php';

class AssessmentController {
    private $pdo;
    private $assessmentModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->assessmentModel = new Assessment($pdo);
    }

    // Processa o salvamento da Anamnese (Ficha de Saúde) preenchida pelo aluno.
    public function saveAnamnesis() {
        // Garante sessão
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se está logado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=showLogin");
            exit;
        }

        $userId = $_SESSION['user_id'];

        // Coleta e trata os dados do POST
        // (?? '' evita erros se o campo não vier)
        $data = [
            'profession' => $_POST['profession'] ?? '',
            'laterality' => isset($_POST['laterality']) && $_POST['laterality'] !== '' ? (int)$_POST['laterality'] : null,
            
            // Troca vírgula por ponto para salvar números decimais corretamente (ex: 1,75 -> 1.75)
            'height'     => !empty($_POST['height']) ? (float)str_replace(',', '.', $_POST['height']) : null,
            'weight'     => !empty($_POST['weight']) ? (float)str_replace(',', '.', $_POST['weight']) : null,
            
            'diagnosis'  => $_POST['diagnosis'] ?? '',
            'complaint'  => $_POST['complaint'] ?? ''
        ];

        // Chama o model para salvar (Criar ou Atualizar)
        $this->assessmentModel->saveAnamnesis($userId, $data);

        // Redireciona de volta para o perfil com feedback
        // O parâmetro &tab=health ajuda a reabrir a aba certa via JS
        header("Location: index.php?action=profile&status=anamnesis_saved&tab=health");
        exit;
    }
}