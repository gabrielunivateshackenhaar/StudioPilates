<?php
// db.php - Conexão com o banco Postgres
try {
    // Caminho para o arquivo do banco
    $pdo = new PDO("sqlite:" . __DIR__ . "/pilates.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
