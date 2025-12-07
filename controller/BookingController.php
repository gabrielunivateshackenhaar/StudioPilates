<?php
// controller/BookingController.php

require_once __DIR__ . '/../model/Enums.php';
require_once __DIR__ . '/../model/Booking.php'; 

class BookingController {
    private $pdo;
    private $bookingModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->bookingModel = new Booking($pdo);
    }

    public function saveBooking() {
        // Verifica se a sessão já existe antes de iniciar
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["user_id"])) {
            echo json_encode(["status" => "erro", "msg" => "Usuário não logado"]);
            exit;
        }

        $user_id     = $_SESSION["user_id"];
        $schedule_id = $_POST['schedule_id'] ?? null;

        if (!$schedule_id) {
            echo json_encode(["status" => "erro", "msg" => "schedule_id não enviado"]);
            exit;
        }

        $ok = $this->bookingModel->create($user_id, $schedule_id);

        if ($ok) {
            echo json_encode(["status" => "ok"]);
            exit;
        } else {
            echo json_encode(["status" => "erro", "msg" => "Erro ao salvar"]);
            exit;
        }
    }
    
    // Método para Admin adicionar aluno manualmente (usando a mesma lógica de criar)
    public function adminAddStudent() {
        // Aproveita a lógica existente, só muda a validação de sessão
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Verifica permissão de Admin
        if (!isset($_SESSION['user_category']) || $_SESSION['user_category'] != 1) {
            echo json_encode(["status" => "erro", "msg" => "Acesso negado"]);
            exit;
        }

        $user_id = $_POST['user_id'];
        $schedule_id = $_POST['schedule_id'];

        // Validação de duplicidade
        $userBookings = $this->bookingModel->getBookedScheduleIds($user_id);
        
        if (in_array($schedule_id, $userBookings)) {
            echo json_encode(["status" => "erro", "msg" => "Este aluno já está inscrito nesta aula."]);
            exit;
        }
        
        if ($this->bookingModel->create($user_id, $schedule_id)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "erro", "msg" => "Erro ao adicionar"]);
        }
        exit;
    }

    // Adicionar horários recorrentes
    public function saveRecurringBooking() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Segurança: Apenas Admin
        if (!isset($_SESSION['user_category']) || $_SESSION['user_category'] != 1) {
            echo json_encode(["status" => "erro", "msg" => "Acesso negado"]);
            exit;
        }

        $userId   = $_POST['user_id'];
        $days     = $_POST['days'] ?? []; // Array [1, 3, 5]
        $time     = $_POST['time'];
        $startDate = new DateTime($_POST['start_date']);
        $quantity = (int)$_POST['quantity'];

        if (empty($days)) {
            echo json_encode(["status" => "erro", "msg" => "Selecione os dias da semana"]);
            exit;
        }

        // Instancia o ScheduleModel para checar vagas
        require_once __DIR__ . '/../model/Schedule.php';
        $scheduleModel = new Schedule($this->pdo);

        // PREPARAÇÃO: Busca agendamentos que o aluno JÁ TEM
        // Isso evita consultas desnecessárias dentro do loop
        $existingUserBookings = $this->bookingModel->getBookedScheduleIds($userId);

        // IDENTIFICAR AS DATAS ALVO
        // Vamos encontrar as próximas N datas que batem com os dias da semana
        $targetSchedules = [];
        $currentDate = clone $startDate;
        $foundCount = 0;
        $safetyLoop = 0;

        // Inicia Transação (Tudo ou Nada)
        $this->pdo->beginTransaction();

        try {
            while ($foundCount < $quantity && $safetyLoop < 365) {
                $safetyLoop++;

                // Se hoje é um dos dias escolhidos (1=Seg, 7=Dom)
                if (in_array($currentDate->format('N'), $days)) {
                    
                    $dateStr = $currentDate->format('Y-m-d');
                    
                    // Verifica disponibilidade
                    $slot = $scheduleModel->findOpenSlot($dateStr, $time);

                    // Verificação se aula existe
                    if (!$slot) {
                        throw new Exception("Não há horário criado/ativo para o dia " . $currentDate->format('d/m/Y') . " às " . $time);
                    }

                    // Verificação de disponibilidade de vagas
                    if (!$slot['has_space']) {
                        throw new Exception("O horário do dia " . $currentDate->format('d/m/Y') . " já está lotado.");
                    }

                    // Verificação de aluno já possui agendamento
                    if (in_array($slot['id'], $existingUserBookings)) {
                        throw new Exception("O aluno já possui agendamento confirmado para o dia " . $currentDate->format('d/m/Y') . ".");
                    }

                    // Se passou, adiciona na fila de gravação
                    $targetSchedules[] = $slot['id'];
                    $foundCount++;
                }

                // Avança para o próximo dia
                $currentDate->modify('+1 day');
            }

            // Se saiu do loop sem encontrar a quantidade (ex: pediu 100 aulas mas o limite do loop acabou)
            if ($foundCount < $quantity) {
                 throw new Exception("Não foi possível encontrar datas suficientes no calendário.");
            }

            // EFETIVAR AGENDAMENTOS
            // Se chegou aqui, todas as datas existem e têm vaga. Pode gravar.
            foreach ($targetSchedules as $scheduleId) {
                if (!$this->bookingModel->create($userId, $scheduleId)) {
                    throw new Exception("Erro ao salvar agendamento.");
                }
            }

            // Confirma tudo
            $this->pdo->commit();
            echo json_encode(["status" => "ok", "count" => $foundCount]);

        } catch (Exception $e) {
            // Se qualquer erro aconteceu no caminho, desfaz tudo
            $this->pdo->rollBack();
            echo json_encode([
                "status" => "erro", 
                "msg" => "Não foi possível marcar as aulas por indisponibilidade de horários, verifique.<br><small class='text-muted'>" . $e->getMessage() . "</small>"
            ]);
        }
        exit;
    }

    public function deleteBooking() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Só admin pode deletar reserva dos outros assim
        if (!isset($_SESSION['user_category']) || $_SESSION['user_category'] != 1) { 
            echo json_encode(["status" => "erro", "msg" => "Sem permissão"]);
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? null;
        
        if ($booking_id && $this->bookingModel->delete($booking_id)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "erro", "msg" => "Erro ao excluir"]);
        }
        exit;
    }
}