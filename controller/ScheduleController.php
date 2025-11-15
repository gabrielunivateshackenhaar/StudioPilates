<?php
// controller/ScheduleController.php

require_once __DIR__ . '/../model/Enums.php';
require_once __DIR__ . '/../model/Schedule.php'; 

class ScheduleController {
    private $pdo;
    private $scheduleModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->scheduleModel = new Schedule($pdo);
    }

    /**
     * Exibe o formulário para criar/editar um horário.
     * (Acessível apenas por Admins)
     */
    public function showScheduleForm() {
        // Protege a rota: só admin pode ver
        if (!isset($_SESSION['user_id']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            header("Location: index.php");
            exit;
        }

        // --- Lógica de Edição (será usada no futuro) ---
        $schedule = null;
        $id = $_GET['id'] ?? null;
        if ($id) {
            // $schedule = $this->scheduleModel->getById($id); 
            // $formAction = "index.php?action=updateSchedule&id={$id}";
        }
        
        // --- Lógica de Criação
        if (!$id) {
            $formAction = "index.php?action=saveSchedule";
        }

        // Carrega a view do formulário
        require __DIR__ . '/../view/schedule_form.php';
    }

    /**
     * Processa o salvamento do novo horário
     */
    public function saveSchedule() {
        // Protege a rota: só admin e via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            header("Location: index.php");
            exit;
        }

        // Coleta os dados do formulário
        $date = $_POST['date'];
        $time = $_POST['time'];
        $duration_minutes = $_POST['duration_minutes'];
        $capacity = (int)$_POST['capacity'];
        $active = (int)$_POST['active'];
        $adminId = $_SESSION['user_id']; // Pega o ID do admin logado

        // Validação (simples, pode ser melhorada)
        if (empty($date) || empty($time) || $duration_minutes <= 0 || $capacity <= 0) {
            // Em um sistema real, mostraríamos um erro
            header("Location: index.php?action=showScheduleForm");
            exit;
        }

        // Chama o model para salvar no banco
        $this->scheduleModel->create($date, $time, $duration_minutes, $capacity, $active, $adminId);

        // Redireciona de volta para o painel de admin
        header("Location: index.php?action=admin");
        exit;
    }

    // Retorna os horários em formato json para o FullCalendar
    public function getSchedulesJson() {
        
        // Verifica se o usuário logado é um Admin
        $isAdmin = (isset($_SESSION['user_id']) && $_SESSION['user_category'] == Category::ADMIN->value);

        // Busca todos os horários cadastrados
        $schedulesFromDB = $this->scheduleModel->getAll();

        // Array que será convertido em JSON
        $events = [];
        
        // Array temporário para agrupar eventos de fundo por dia
        $daySummary = [];
        
        foreach ($schedulesFromDB as $schedule) {
            
            // Regra de permissão: Alunos (não-admins) não podem ver horários inativos
            if (!$isAdmin && $schedule['active'] == 0) {
                continue; // Pula para o próximo horário
            }

            // --- PARTE 1: Adiciona o evento de BLOCO (para visão Semana/Dia) ---
            $start = $schedule['date'] . 'T' . $schedule['time'];
            $endDate = new DateTime($start);
            $endDate->modify('+' . $schedule['duration_minutes'] . ' minutes');
            $end = $endDate->format('Y-m-d\TH:i:s');

            $title = '';
            $backgroundColor = '';

            if ($isAdmin) {
                // Admin vê a contagem de vagas e cores diferentes
                $title = "Vagas: " . $schedule['capacity'];
                $backgroundColor = ($schedule['active'] == 1) ? '#28a745' : '#6c757d'; // Verde (ativo) ou Cinza (inativo)
            } else {
                // Aluno normal
                $title = 'Horário Disponível';
                $backgroundColor = '#28a745';
            }
            
            // Adiciona o evento de BLOCO formatado ao array
            $events[] = [
                'id'    => $schedule['id'],
                'title' => $title,
                'start' => $start,
                'end'   => $end,
                'backgroundColor' => $backgroundColor
            ];


            // --- PARTE 2: Prepara o evento de FUNDO (para visão Mês) ---
            $date = $schedule['date']; // Apenas a data, ex: '2025-11-14'

            // Inicializa o dia se for a primeira vez
            if (!isset($daySummary[$date])) {
                $daySummary[$date] = [
                    'hasActive' => false,
                    'hasInactive' => false
                    // (Aqui podemos adicionar lógica de 'esgotado' no futuro)
                ];
            }

            // Define o status do dia
            if ($schedule['active'] == 1) {
                $daySummary[$date]['hasActive'] = true;
            } else {
                $daySummary[$date]['hasInactive'] = true;
            }
        }

        // --- PARTE 3: Processa os eventos de FUNDO e adiciona ao array ---
        foreach ($daySummary as $date => $status) {
            
            $backgroundEvent = [
                'start'   => $date,       // Define como evento de dia inteiro
                'allDay'  => true,       // Confirma
                'display' => 'background' // FORÇA a renderização como fundo
            ];

            if ($status['hasActive']) {
                // $backgroundEvent['backgroundColor'] = '#28a745'; // Verde
                // Ao não definir cor, o FullCalendar usará a cor padrão do tema.
            } 
            // else if ($status['isFull']) { // Lógica futura
            //    $backgroundEvent['backgroundColor'] = '#dc3545'; // Vermelho
            // }
            else if ($isAdmin && $status['hasInactive']) {
                $backgroundEvent['backgroundColor'] = '#6c757d'; // Cinza (só admin vê)
            } else {
                continue; // Não gera evento de fundo se não houver nada
            }

            $events[] = $backgroundEvent; // Adiciona o evento de fundo ao array
        }


        // Define o cabeçalho como JSON e imprime o array (com os dois tipos de evento)
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }
}