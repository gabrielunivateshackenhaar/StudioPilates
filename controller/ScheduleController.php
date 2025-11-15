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

    // Retorna os horários em formato json para o FullCalendar
    public function getSchedulesJson() {
        
        // Verifica se o usuário logado é um Admin
        $isAdmin = (isset($_SESSION['user_id']) && $_SESSION['user_category'] == Category::ADMIN->value);

        // Busca todos os horários cadastrados, com o nome do admin (via JOIN)
        $schedulesFromDB = $this->scheduleModel->getAll();

        // Array que será convertido em JSON
        $events = [];
        
        foreach ($schedulesFromDB as $schedule) {
            
            // Regra de permissão: Alunos (não-admins) não podem ver horários inativos
            if (!$isAdmin && $schedule['active'] == 0) {
                continue; // Pula para o próximo horário
            }

            // Formata o início (ex: '2025-11-14T08:00:00')
            $start = $schedule['date'] . 'T' . $schedule['time'];
            
            // Calcula o fim com base na duração (ex: 08:00 + 60 min = 09:00)
            $endDate = new DateTime($start);
            $endDate->modify('+' . $schedule['duration_minutes'] . ' minutes');
            $end = $endDate->format('Y-m-d\TH:i:s');

            // Define o título e a cor com base no tipo de usuário
            $title = '';
            $backgroundColor = '';

            if ($isAdmin) {
                // Admin vê a contagem de vagas e cores diferentes
                $title = "Vagas: " . $schedule['capacity'];
                $backgroundColor = ($schedule['active'] == 1) ? '#28a745' : '#6c757d'; // Verde (ativo) ou Cinza (inativo)
            } else {
                $title = 'Horário Disponível';
                $backgroundColor = '#28a745';
            }
            
            // Adiciona o evento formatado ao array
            $events[] = [
                'id'    => $schedule['id'],
                'title' => $title,
                'start' => $start,
                'end'   => $end,
                'backgroundColor' => $backgroundColor
            ];
        }

        // Define o cabeçalho como JSON e imprime o array
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }
}