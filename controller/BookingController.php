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
}