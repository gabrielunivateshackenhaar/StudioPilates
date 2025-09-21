<?php
// index.php - Ponto de entrada da aplicação
require_once __DIR__ . '/init.php';

// Inicia sessão para gerenciar login
session_start();

// Pega a ação da URL (ex.: ?action=login)
$action = $_GET['action'] ?? 'home';

switch ($action) {
    // Home
    case 'home':
        $controller->home();
        break;

    // Login
    case 'showLogin':
        $controller->loginForm();
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->loginForm();
        }
        break;

    // Registro
    case 'showRegister':
        $controller->registerForm();
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->registerForm();
        }
        break;

    // Logout
    case 'logout':
        $controller->logout();
        break;

    // Admin
    case 'admin':
        $controller->adminPanel();
        break;

    // Editar (exibe formulário)
    case 'editUser':
        $controller->editUser();
        break;
        
    // Editar (processa atualização)    
    case 'updateUser':
        $controller->updateUser();
        break;

    // Excluir
    case 'deleteUser':
        $controller->deleteUser();
        break;

    // Default: redireciona para home
    default:
        $controller->home();
        break;
}
