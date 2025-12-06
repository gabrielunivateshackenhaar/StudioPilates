<?php
// model/Schedule.php
// Classe responsável por interagir com a tabela schedules

class Schedule {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; // Conexão com o banco
    }

    /**
     * Cria um novo horário no banco
     */
    public function create(string $date, string $time, int $duration_minutes, int $capacity, int $active, int $adminId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO schedules 
            (date, time, duration_minutes, capacity, active, created_by) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $date,
            $time,
            $duration_minutes,
            $capacity,
            $active,
            $adminId
        ]);
    }

    /**
     * Busca todos os horários, trazendo o nome do admin e a contagem de agendados
     */
    public function getAll() {
        // Usamos um Sub-SELECT para contar os agendamentos (bookings)
        // para cada horário (schedule), filtrando apenas por status = 0 (Confirmado)
        $stmt = $this->pdo->query("
            SELECT 
                s.*, 
                u.name as admin_name,
                (SELECT COUNT(*) 
                 FROM bookings b 
                 WHERE b.schedule_id = s.id AND b.status = 0) as bookings_count
            FROM schedules s
            LEFT JOIN users u ON s.created_by = u.id
            ORDER BY s.date DESC, s.time DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Exclui um horário e seus agendamentos vinculados.
     */
    public function delete(int $id) {
        // 1. Remove agendamentos deste horário (limpeza)
        $stmtBookings = $this->pdo->prepare("DELETE FROM bookings WHERE schedule_id = ?");
        $stmtBookings->execute([$id]);

        // 2. Remove o horário
        $stmtSchedule = $this->pdo->prepare("DELETE FROM schedules WHERE id = ?");
        return $stmtSchedule->execute([$id]);
    }
}