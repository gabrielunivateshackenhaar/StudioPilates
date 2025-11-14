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
        $userController->home();
        break;

    // Login
    case 'showLogin':
        $userController->loginForm();
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->login();
        } else {
            $userController->loginForm();
        }
        break;

    // Registro
    case 'showRegister':
        $userController->registerForm();
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->register();
        } else {
            $userController->registerForm();
        }
        break;

    // Logout
    case 'logout':
        $userController->logout();
        break;

    // Admin
    case 'admin':
        $userController->adminPanel();
        break;

    // Editar - Admin (exibe formulário)
    case 'editUser':
        $userController->editUser();
        break;
        
    // Editar - Admin (processa atualização)    
    case 'updateUser':
        $userController->updateUser();
        break;

    // Excluir - Admin
    case 'deleteUser':
        $userController->deleteUser();
        break;

    // Perfil do usuário logado (exibe)
    case 'profile':
        $userController->profile();
        break;

     // Registro de usuário pelo admin
    case 'showRegisterUser':
        $userController->registerUserAdminForm();
        break;
    
    // Exibir formulário de novo horário
    case 'showScheduleForm':
        $scheduleController->showScheduleForm();
        break;

    // Processar formulário de novo horário
    case 'saveSchedule':
        $scheduleController->saveSchedule();
        break;

    // Default: redireciona para home
    default:
        $controller->home();
        break;
}
