<?php
// view/partials/calendar_view.php
// Contém o HTML da legenda e do container do calendário
$user_id = $_SESSION['user_id'] ?? null;

// Verifica se é admin (Categoria 1)
$is_admin = (isset($_SESSION['user_category']) && $_SESSION['user_category'] == 1);

// Verifica se é painel do admin
$is_admin_page = (isset($_GET['action']) && $_GET['action'] === 'admin');

$enable_admin_mode = ($is_admin && $is_admin_page);
?>

<script>
    const SESSION_USER_ID = <?= json_encode($user_id) ?>;
    const IS_ADMIN = <?= json_encode($enable_admin_mode) ?>;
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

<div class="card shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-center gap-3">

        <div class="d-flex align-items-center">
            <span class="badge" style="background-color: #28a745; width: 20px; height: 20px;">&nbsp;</span>
            <span class="ms-2 text-muted">Horários disponíveis</span>
        </div>

        <div class="d-flex align-items-center">
            <span class="badge" style="background-color: #adb5bd; width: 20px; height: 20px;">&nbsp;</span>
            <span class="ms-2 text-muted">Horários Esgotados</span>
        </div>
    </div>
</div>

<div id="calendar-container" class="card shadow-sm">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
