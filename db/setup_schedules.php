<?php
// db/setup_schedules.php
// Executar antes de rodar a aplicação, comando: php db/setup_schedules.php
// Script para criar as tabelas schedules e bookings (agendamentos)

require_once __DIR__ . '/db.php'; // Conexão com o banco

try {
    /*
     * Tabela schedules
     * -----------------
     * Armazena todos os horários disponíveis criados pelo administrador.
     * Cada registro representa um dia e horário específico onde até N alunos podem reservar.
     *
     * Campos:
     *  - id: chave primária
     *  - date: data da sessão (YYYY-MM-DD)
     *  - time: hora da sessão (HH:MM)
     *  - duration_minutes: duração da aula em minutos (ex:60)
     *  - capacity: capacidade máxima
     *  - active: 1 = ativo para agendamentos, 0 = desativado
     *  - created_by: id do usuário admin que criou o horário
     */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS schedules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            date TEXT NOT NULL,
            time TEXT NOT NULL,
            duration_minutes INTEGER NOT NULL,
            capacity INTEGER NOT NULL,
            active INTEGER NOT NULL,
            created_by INTEGER,
            FOREIGN KEY (created_by) REFERENCES users(id)
        )
    ");

    /*
     * Tabela bookings
     * ---------------
     * Armazena todas as reservas feitas por usuários.
     * Cada reserva aponta para um horário (schedule).
     *
     * Campos:
     *  - id: chave primária
     *  - user_id: usuário que fez a reserva
     *  - schedule_id: horário reservado
     *  - created_at: timestamp automático
     *  - status: CONFIRMED (padrão), CANCELLED, etc.
     */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            schedule_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            status INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (schedule_id) REFERENCES schedules(id)
        )
    ");

    echo "Tabelas schedules e bookings criadas com sucesso!";

} catch (PDOException $e) {
    die("Erro ao criar tabelas de agendamento: " . $e->getMessage());
}
