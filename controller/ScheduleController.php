<?php
// controller/ScheduleController.php

require_once __DIR__ . '/../model/Enums.php';
require_once __DIR__ . '/../model/Schedule.php'; 
require_once __DIR__ . '/../model/Booking.php';

class ScheduleController {
    private $pdo;
    private $scheduleModel;
    private $bookingModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->scheduleModel = new Schedule($pdo);
        $this->bookingModel = new Booking($pdo);
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
        
        // Pega o ID do usuário logado (se houver)
        $userId = $_SESSION['user_id'] ?? null; 

        // Busca todos os horários cadastrados
        $schedulesFromDB = $this->scheduleModel->getAll();

        // Se tiver usuário logado, busca os agendamentos dele para saber quais já reservou
        $userBookedScheduleIds = [];
        if ($userId) {
            $userBookedScheduleIds = $this->bookingModel->getBookedScheduleIds($userId);
        }

        $events = [];
        $daySummary = [];

        // Define o momento atual
        $now = new DateTime();
        
        foreach ($schedulesFromDB as $schedule) {

            // Filtro de passado (remover horários)
            // Cria um objeto DateTime com a data e hora do horário
            $scheduleDateTime = new DateTime($schedule['date'] . ' ' . $schedule['time']);

            // Se o horário for menor (anterior) ao momento atual, pula.
            if ($scheduleDateTime < $now) {
                continue; 
            }

            // Remover inativos (por enquanto não é utilizado)
            if ($schedule['active'] == ScheduleStatus::INACTIVE->value) {
                continue;
            } 

            // Calcula vagas
            $bookingsCount = (int)$schedule['bookings_count'];
            // Vagas Restantes
            $remainingSpots = $schedule['capacity'] - $bookingsCount;
            $isFull = $remainingSpots <= 0;

            // Verifica se o usuário JÁ reservou este horário
            $alreadyBooked = in_array($schedule['id'], $userBookedScheduleIds);
            
            // --- PARTE 1: Adiciona o evento de BLOCO (para visão Semana/Dia) ---
            $start = $schedule['date'] . 'T' . $schedule['time'];
            $endDate = new DateTime($start);
            $endDate->modify('+' . $schedule['duration_minutes'] . ' minutes');
            $end = $endDate->format('Y-m-d\TH:i:s');

            $title = '';
            $backgroundColor = '';

            // Lógica de prioridade para Título e Cor
            if ($alreadyBooked) {
                // Prioridade 1: Se o usuário já reservou
                $title = "Reservado por você!";
                $backgroundColor = '#17a2b8';
            } elseif ($isFull) {
                // Prioridade 2: Se está cheio
                $title = "Esgotado";
                $backgroundColor = '#adb5bd';
            } else {
                // Prioridade 3: Disponível
                $title = "Vagas: {$remainingSpots}";
                $backgroundColor = '#28a745';
            }
            
            // Adiciona o evento de BLOCO formatado ao array
            $events[] = [
                'id'    => $schedule['id'],
                'title' => $title,
                'start' => $start,
                'end'   => $end,
                'backgroundColor' => $backgroundColor,
                'extendedProps' => [
                    'isBooked' => $alreadyBooked
                ]
            ];

            // --- PARTE 2: Prepara o evento de FUNDO (para visão Mês) ---
            $date = $schedule['date'];
            if (!isset($daySummary[$date])) {
                $daySummary[$date] = [
                    'hasAvailable' => false, // Se tem pelo menos 1 vaga ativa
                    'allSlotsFull' => true,  // Começa presumindo que está cheio
                ];
            }

            // Se chegamos aqui, o horário é ATIVO (pois os inativos foram pulados lá em cima)
            if (!$isFull) {
                $daySummary[$date]['hasAvailable'] = true; 
                $daySummary[$date]['allSlotsFull'] = false;
            }
        }

        // --- PARTE 3: Processa os eventos de FUNDO e adiciona ao array ---
        foreach ($daySummary as $date => $status) {
            
            $backgroundEvent = [
                'start'   => $date,       // Define como evento de dia inteiro
                'allDay'  => true,        // Confirma
                'display' => 'background' // FORÇA a renderização como fundo
            ];

            if ($status['hasAvailable']) {
                $backgroundEvent['backgroundColor'] = '#28a745'; // Verde (Tem vaga)
            } else if ($status['allSlotsFull']) {
                $backgroundEvent['backgroundColor'] = '#adb5bd'; // Cinza (Tudo cheio)
            } else {
                continue;
            }

            $events[] = $backgroundEvent;
        }

        // Define o cabeçalho como JSON e imprime o array (com os dois tipos de evento)
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }

    // Salva novo horário, chamado pelo modal do admin
    public function saveQuickSchedule() {
        // 1. Verificações de Segurança
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Garante que é ADMIN
        if (!isset($_SESSION['user_category']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            echo json_encode(["status" => "erro", "msg" => "Acesso negado"]);
            exit;
        }

        // 2. Coleta dos Dados
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $duration = $_POST['duration_minutes'] ?? 60;
        $capacity = $_POST['capacity'] ?? 3;
        $active = 1; // Padrão ativo
        $adminId = $_SESSION['user_id'];

        // 3. Validação Básica
        if (empty($date) || empty($time)) {
            echo json_encode(["status" => "erro", "msg" => "Data e Hora são obrigatórias"]);
            exit;
        }

        // 4. Salva no Banco
        try {
            $this->scheduleModel->create($date, $time, $duration, $capacity, $active, $adminId);
            echo json_encode(["status" => "ok"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "erro", "msg" => "Erro ao salvar: " . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Exclusão via TABELA (Redireciona)
     */
    public function deleteSchedule() {
        $this->checkAdmin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->scheduleModel->delete($id);
        }

        header("Location: index.php?action=admin&status=deleted");
        exit;
    }

    /**
     * Exclusão via CALENDÁRIO/AJAX (Retorna JSON)
     */
    public function deleteScheduleAjax() {
        $this->checkAdmin();

        $id = $_POST['id'] ?? null;
        
        if ($id && $this->scheduleModel->delete($id)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "erro", "msg" => "Erro ao excluir"]);
        }
        exit;
    }

    // Método auxiliar para não repetir código de verificação
    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_category']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            // Se for AJAX, retorna JSON, se for normal, redireciona
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                 echo json_encode(["status" => "erro", "msg" => "Acesso negado"]);
                 exit;
            }
            header("Location: index.php");
            exit;
        }
    }
}