<?php
// view/partials/btn_toggle.php
// Recebe: $btn1Id, $btn1Text, $btn2Id, $btn2Text, $conteudo1Id, $conteudo2Id, $btn1Active (true/false)

$btn1Active = $btn1Active ?? true; // padrão: primeiro botão ativo
?>

<style>
    .btn-toggle-group .btn {
        border-radius: 0;
    }

    .btn-toggle-group .btn:first-child {
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
    }

    .btn-toggle-group .btn:last-child {
        border-top-right-radius: 50px;
        border-bottom-right-radius: 50px;
    }
</style>

<div class="btn-group btn-toggle-group" role="group">
    <button id="<?= htmlspecialchars($btn1Id) ?>"
        class="btn <?= $btn1Active ? 'btn-primary' : 'btn-outline-secondary' ?> px-4">
        <?= htmlspecialchars($btn1Text) ?>
    </button>
    <button id="<?= htmlspecialchars($btn2Id) ?>"
        class="btn <?= $btn1Active ? 'btn-outline-secondary' : 'btn-primary' ?> px-4">
        <?= htmlspecialchars($btn2Text) ?>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn1 = document.getElementById('<?= $btn1Id ?>');
        const btn2 = document.getElementById('<?= $btn2Id ?>');
        const conteudo1 = document.getElementById('<?= $conteudo1Id ?>');
        const conteudo2 = document.getElementById('<?= $conteudo2Id ?>');

        btn1.addEventListener('click', () => {
            conteudo1.style.display = 'block';
            conteudo2.style.display = 'none';
            btn1.classList.replace('btn-outline-secondary', 'btn-primary');
            btn2.classList.replace('btn-primary', 'btn-outline-secondary');
        });

        btn2.addEventListener('click', () => {
            conteudo1.style.display = 'none';
            conteudo2.style.display = 'block';
            btn2.classList.replace('btn-outline-secondary', 'btn-primary');
            btn1.classList.replace('btn-primary', 'btn-outline-secondary');
        });
    });
</script>