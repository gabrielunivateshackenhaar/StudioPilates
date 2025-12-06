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

    // Atualizar Perfil (processa o formulário)
    case 'updateProfile':
        $userController->updateProfile();
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

    // Excluir horário (Tabela)
    case 'deleteSchedule':
        $scheduleController->deleteSchedule();
        break;

    // Excluir horário (Calendário JS)
    case 'deleteScheduleAjax':
        $scheduleController->deleteScheduleAjax();
        break;

    case 'getSchedulesJson':
        $scheduleController->getSchedulesJson();
        break;

    // Salvar horário rápido (Admin)
    case 'saveQuickSchedule':
        $scheduleController->saveQuickSchedule();
        break;

    // Processar agendamento (chamado via AJAX pelo calendar.js)
    case 'saveBooking':
        $bookingController->saveBooking();
        break;

    // Exibir formulário de confirmação
    case 'showConfirmForm':
        $userController->showConfirmForm();
        break;

    // Validar o código de confirmação
    case 'confirmCode':
        $userController->confirmCode();
        break;

    // Reenviar código de confirmação
    case 'resendCode':
        $userController->resendCode();
        break;

    // Salvar ficha de Saúde
    case 'saveAnamnesis':
        $assessmentController->saveAnamnesis();
        break;
    
    // Admin: Abrir Avaliação
    case 'adminAssessment':
        $assessmentController->adminAssessment();
        break;

    // Admin: Salvar Avaliação
    case 'saveAdminAssessment':
        $assessmentController->saveAdminAssessment();
        break;

    // Default: redireciona para home
    default:
        $userController->home();
        break;
}
