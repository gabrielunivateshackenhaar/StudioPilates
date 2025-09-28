<?php
// db/setup_users.php
// Executar antes de rodar a aplicação, comando: php db/setup_users.php
// Script para criar a tabela users e inserir usuários automáticos

require_once __DIR__ . '/db.php'; // Conexão com o banco
require_once __DIR__ . '/../model/enums.php'; // Enums

try {
    // Criar tabela users se não existir
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            birth_date DATE,
            category INTEGER,
            email TEXT,
            gender INTEGER,
            laterality INTEGER,
            name TEXT,
            password TEXT,
            phone TEXT,
            profession TEXT
        )
    ");

    // Inserir usuários automáticos (se não existirem)
    $checkUser = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($checkUser == 0) {
        $stmt = $pdo->prepare("
            INSERT INTO users 
            (birth_date, category, email, gender, laterality, name, password, phone, profession) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Usuário normal
        $stmt->execute([
            '1990-01-01',             // birth_date
            Category::NORMAL->value,  // category: normal
            'user1@test.com',         // email
            Gender::MALE->value,      // gender: male
            Laterality::RIGHT->value, // laterality: right
            'User One',               // name
            password_hash('123456', PASSWORD_DEFAULT), // password
            '(99) 99999-9999',        // phone
            'Student'                 // profession
        ]);

        // Usuário admin
        $stmt->execute([
            '1985-01-01',           // birth_date
            Category::ADMIN->value, // category: admin
            'admin1@test.com',      // email
            Gender::FEMALE->value,  // gender: female
            Laterality::LEFT->value, // laterality: left
            'Admin One',             // name
            password_hash('admin123', PASSWORD_DEFAULT), // password
            '(88) 88888-8888',       // phone
            'Administrator'          // profession
        ]);
    }

    echo "Tabela users criada com sucesso e usuários inseridos!";
} catch (PDOException $e) {
    die("Erro ao criar tabela ou inserir usuários: " . $e->getMessage());
}
