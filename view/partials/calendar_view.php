<?php
// view/partials/calendar_view.php
// Contém o HTML da legenda e do container do calendário
$user_id = $_SESSION['user_id'] ?? null;

// Verifica se é admin (Categoria 1)
$is_admin = (isset($_SESSION['user_category']) && $_SESSION['user_category'] == 1);

// Verifica se é painel do admin
$is_admin_page = (isset($_GET['action']) && $_GET['action'] === 'admin');

$enable_admin_mode = ($is_admin && $is_admin_page);

// SE FOR ADMIN: Busca lista simplificada de alunos para o seletor
$all_users_json = '[]';
if ($enable_admin_mode) {
    // Precisamos acessar o banco aqui rapidinho ou usar o controller. 
    // Como é um partial, vamos usar o $pdo global que já existe no escopo.
    global $pdo;
    if ($pdo) {
        $stmt = $pdo->query("SELECT id, name FROM users WHERE category = 0 ORDER BY name ASC");
        $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $all_users_json = json_encode($all_users);
    }
}
?>

<script>
    const SESSION_USER_ID = <?= json_encode($user_id) ?>;
    const IS_ADMIN = <?= json_encode($enable_admin_mode) ?>;
    const ALL_USERS = <?= $all_users_json ?>;
</script>

<?php 
// A variável $hasAssessment vem do UserController::home()
if (isset($_SESSION['user_id']) && isset($hasAssessment) && !$hasAssessment && ($_SESSION['user_category'] ?? 0) != 1):
?>
    <div class="fade-in-down mb-4">
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert" style="background-color: #fff3cd; color: #856404; border-left: 5px solid #ffc107 !important;">
            <i class="bi bi-clipboard-pulse fs-3 me-3"></i>
            <div class="flex-grow-1">
                <strong>Complete seu perfil!</strong> 
                <span class="d-none d-md-inline">Para um acompanhamento personalizado,</span> 
                preencha sua Ficha de Saúde.
            </div>
            <a href="index.php?action=profile&tab=health" class="btn btn-sm btn-warning text-dark fw-bold ms-3">
                Preencher
            </a>
        </div>
    </div>
<?php endif; ?>

<?php if ($is_admin && isset($_GET['action']) && $_GET['action'] === 'admin'): ?>
    <div class="d-flex justify-content-end align-items-center mb-2 gap-2">
        
        <button type="button" 
                class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" 
                style="width: 32px; height: 32px;"
                data-bs-toggle="tooltip" 
                data-bs-html="true"
                data-bs-placement="left"
                title="• Clique em uma data vazia para criar um horário individual.<br>• Clique em um horário existente para gerenciar.">
            <i class="bi bi-info-lg"></i>
        </button>

        <button class="btn btn-success btn-sm fw-bold shadow-sm d-flex align-items-center" 
                style="height: 32px;"
                data-bs-toggle="modal" 
                data-bs-target="#bulkScheduleModal" >
            <i class="bi bi-calendar-plus me-1"></i> Gerar Grade
        </button>

    </div>
<?php endif; ?>

<div id="calendar-container" class="card shadow-sm">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center flex-wrap gap-4 text-secondary small">
    
    <div class="d-flex align-items-center">
        <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #28a745;"></span>
        <span>Disponíveis</span>
    </div>

    <div class="d-flex align-items-center">
        <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #adb5bd;"></span>
        <span>Esgotados</span>
    </div>

    <div class="d-flex align-items-center">
        <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #17a2b8;"></span>
        <span>Seus Agendamentos</span>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

</div>