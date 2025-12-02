<?php
// db/setup_database.php
// Script unificado para criar toda a estrutura do banco de dados
// Execute: php db/setup_database.php

require_once __DIR__ . '/db.php'; // Conexão com o banco
require_once __DIR__ . '/../model/enums.php'; // Enums

try {
    // ===================================================================================
    // 1. TABELA USERS
    // ===================================================================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            birth_date DATE,
            category INTEGER,
            email TEXT,
            gender INTEGER,
            name TEXT,
            password TEXT,
            phone TEXT,
            status INTEGER DEFAULT 0,
            confirmation_code TEXT
        )
    ");

    // Inserir usuários padrão (se a tabela estiver vazia)
    $checkUser = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($checkUser == 0) {
        echo "Inserindo usuários padrão...\n";
        
        $stmt = $pdo->prepare("
            INSERT INTO users 
            (birth_date, category, email, gender, name, password, phone, status, confirmation_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Usuário normal (Já nasce ATIVO = 1 para testes)
        $stmt->execute([
            '1990-01-01',             // birth_date
            Category::NORMAL->value,  // category
            'user1@test.com',         // email
            Gender::MALE->value,      // gender
            'User One',               // name
            password_hash('1', PASSWORD_DEFAULT), // password
            '(99) 99999-9999',        // phone
            1,                        // status: ATIVO
            null                      // code
        ]);

        // Usuário admin (Já nasce ATIVO = 1)
        $stmt->execute([
            '1985-01-01',           // birth_date
            Category::ADMIN->value, // category
            'admin1@test.com',      // email
            Gender::FEMALE->value,  // gender
            'Admin One',             // name
            password_hash('1', PASSWORD_DEFAULT), // password
            '(88) 88888-8888',       // phone
            1,                       // status: ATIVO
            null                     // code
        ]);
    }

    // ===================================================================================
    // 2. TABELA SCHEDULES (Horários)
    // ===================================================================================
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

    // ===================================================================================
    // 3. TABELA BOOKINGS (Agendamentos)
    // ===================================================================================
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

    echo "Banco de dados configurado com sucesso (Tabelas Users, Schedules e Bookings)!";

} catch (PDOException $e) {
    die("Erro ao configurar o banco de dados: " . $e->getMessage());
}