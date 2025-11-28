<?php
// db/update_db.php
// Execute este script para adicionar as novas colunas na tabela de usuários
// Comando: php db/update_users.php

require_once __DIR__ . '/db.php';

try {
    echo "Iniciando atualização do banco de dados...\n";

    // 1. Adiciona a coluna 'status'
    // Definimos DEFAULT 1 para que todos os usuários atuais
    // já fiquem ativos e não sejam bloqueados no login.
    $pdo->exec("ALTER TABLE users ADD COLUMN status INTEGER DEFAULT 1");
    echo "- Coluna 'status' adicionada (padrão: 1/Ativo).\n";

    // 2. Adiciona a coluna 'confirmation_code'
    $pdo->exec("ALTER TABLE users ADD COLUMN confirmation_code TEXT DEFAULT NULL");
    echo "- Coluna 'confirmation_code' adicionada.\n";

    echo "Sucesso! O banco de dados foi atualizado.";

} catch (PDOException $e) {
    // O erro mais comum aqui é "duplicate column name" se você rodar duas vezes.
    echo "Aviso/Erro: " . $e->getMessage();
}