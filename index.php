<?php
// index.php - ponto de entrada da aplicação
require_once __DIR__ . '/init.php';

// Pega a ação da URL (ex.: ?action=login)
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
        include __DIR__ . '/view/login.php';
        break;
    case 'register':
        include __DIR__ . '/view/register.php';
        break;
    default:
        include __DIR__ . '/view/home.php';
        break;
}