<?php
// model/Booking.php
// Classe responsável por interagir com a tabela bookings

class Booking {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; // Conexão com o banco
    }

    /** 
     * Cria um novo agendamento no banco com o horário atual e status "confirmado"
     */
    public function create(int $user_id, int $schedule_id) {

        $stmt = $this->pdo->prepare("
            INSERT INTO bookings
            (user_id, schedule_id, created_at, status)
            VALUES (?, ?, datetime('now'), ?)
        ");

        return $stmt->execute([
            $user_id,
            $schedule_id,
            0
        ]);
    }

    /**
     * Busca todos os atendimentos
     */
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM bookings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca todos os agendamentos de um usuário específico,
     * trazendo também os dados do horário (schedule).
     */
    public function getByUserId(int $userId) {
        // JOIN para pegar a data e hora da tabela schedules
        $sql = "
            SELECT 
                b.id as booking_id,
                b.status as booking_status,
                b.created_at,
                s.date,
                s.time,
                s.duration_minutes
            FROM bookings b
            JOIN schedules s ON b.schedule_id = s.id
            WHERE b.user_id = ?
            ORDER BY s.date DESC, s.time DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}