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

     // Exibe a ficha completa para o Admin preencher.
    public function adminAssessment() {
        $this->checkAdmin(); // Método auxiliar para segurança

        $userId = $_GET['userId'] ?? null;
        if (!$userId) {
            header("Location: index.php?action=admin");
            exit;
        }

        // Busca dados do usuário (Nome, idade, etc)
        // Precisamos instanciar o User model aqui ou passar via injeção, 
        // mas vamos assumir que você pode pegar pelo ID rápido:
        global $pdo; // Usando global para simplificar neste exemplo
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Busca a avaliação (se existir)
        $assessment = $this->assessmentModel->getByUserId($userId);

        // Decodifica o JSON postural para preencher os checkboxes (se já houver dados)
        $postural = [];
        if (!empty($assessment['postural_data'])) {
            $postural = json_decode($assessment['postural_data'], true);
        }

        // Variável para a view saber se é criação ou edição
        $assessmentId = $assessment['id'] ?? null;

        require __DIR__ . '/../view/admin_assessment.php';
    }

    // Processa o salvamento da parte técnica (Admin).
    public function saveAdminAssessment() {
        $this->checkAdmin();

        $userId = $_POST['user_id'];
        $assessmentId = $_POST['assessment_id'] ?? null;

        // 1. Dados Gerais (que o Admin também pode editar/completar)
        $generalData = [
            'profession' => $_POST['profession'] ?? '',
            'laterality' => isset($_POST['laterality']) ? (int)$_POST['laterality'] : null,
            'height'     => !empty($_POST['height']) ? (float)str_replace(',', '.', $_POST['height']) : null,
            'weight'     => !empty($_POST['weight']) ? (float)str_replace(',', '.', $_POST['weight']) : null,
            'diagnosis'  => $_POST['diagnosis'] ?? '',
            'complaint'  => $_POST['complaint'] ?? ''
        ];

        // Primeiro salvamos/criamos a ficha básica (garante que o ID exista)
        $this->assessmentModel->saveAnamnesis($userId, $generalData);

        // Se não tínhamos o ID (era nova), buscamos agora
        if (!$assessmentId) {
            $newAss = $this->assessmentModel->getByUserId($userId);
            $assessmentId = $newAss['id'];
        }

        // 2. Dados Posturais (O "Coração" da ficha)
        // Pegamos o array 'postural' que vem do formulário
        $posturalData = $_POST['postural'] ?? [];
        
        // Transformamos em JSON
        $json = json_encode($posturalData, JSON_UNESCAPED_UNICODE);

        // Salvamos no campo postural_data
        $this->assessmentModel->savePosturalData($assessmentId, $json);

        header("Location: index.php?action=adminAssessment&userId=$userId&status=saved");
        exit;
    }

    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_category']) || $_SESSION['user_category'] != 1) {
            die("Acesso negado.");
        }
    }
}