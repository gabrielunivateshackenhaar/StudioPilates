<?php
// controller/ScheduleController.php

require_once __DIR__ . '/../model/Enums.php';
require_once __DIR__ . '/../model/Schedule.php'; 

class ScheduleController {
    private $pdo;
    private $scheduleModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->scheduleModel = new Schedule($pdo);
    }

    /**
     * Exibe o formulário para criar/editar um horário.
     * (Acessível apenas por Admins)
     */
    public function showScheduleForm() {
        // Protege a rota: só admin pode ver
        if (!isset($_SESSION['user_id']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            header("Location: index.php");
            exit;
        }

        // --- Lógica de Edição (será usada no futuro) ---
        $schedule = null;
        $id = $_GET['id'] ?? null;
        if ($id) {
            // $schedule = $this->scheduleModel->getById($id); 
            // $formAction = "index.php?action=updateSchedule&id={$id}";
        }
        
        // --- Lógica de Criação
        if (!$id) {
            $formAction = "index.php?action=saveSchedule";
        }

        // Carrega a view do formulário
        require __DIR__ . '/../view/schedule_form.php';
    }

    /**
     * Processa o salvamento do novo horário
     */
    public function saveSchedule() {
        // Protege a rota: só admin e via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            header("Location: index.php");
            exit;
        }

        // Coleta os dados do formulário
        $date = $_POST['date'];
        $time = $_POST['time'];
        $duration_minutes = $_POST['duration_minutes'];
        $capacity = (int)$_POST['capacity'];
        $active = (int)$_POST['active'];
        $adminId = $_SESSION['user_id']; // Pega o ID do admin logado

        // Validação (simples, pode ser melhorada)
        if (empty($date) || empty($time) || $duration_minutes <= 0 || $capacity <= 0) {
            // Em um sistema real, mostraríamos um erro
            header("Location: index.php?action=showScheduleForm");
            exit;
        }

        // Chama o model para salvar no banco
        $this->scheduleModel->create($date, $time, $duration_minutes, $capacity, $active, $adminId);

        // Redireciona de volta para o painel de admin
        header("Location: index.php?action=admin");
        exit;
    }
}