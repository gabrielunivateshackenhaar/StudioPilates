<?php
// Recebe: $btn1Id, $btn1Text, $btn2Id, $btn2Text, $conteudo1Id, $conteudo2Id, $btn1Active (true/false)
$btn1Active = $btn1Active ?? true; 
?>

<style>
/* ==== TOGGLE WRAPPER ==== */
.toggle-wrapper {
    display: inline-flex;
    background: #ffffff;
    border-radius: 45px;
    padding: 4px;
    border: 2px solid #d9eae5;
    gap: 4px;
    box-shadow: 0 2px 14px rgba(0,0,0,0.05);
}

/* ==== BUTTONS ==== */
.toggle-btn {
    padding: 10px 28px;
    border: none;
    cursor: pointer;
    border-radius: 45px;
    background: transparent;
    font-size: 0.95rem;
    color: #0F3F36;
    font-weight: 500;
    transition: all .28s ease;
}

/* ==== ACTIVE BUTTON ==== */
.toggle-btn.active {
    background: var(--verde-pilates, #0F3F36);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.13);
}

/* ==== HOVER ==== */
.toggle-btn:hover:not(.active) {
    background: #eef7f4;
}

/* ==== RESPONSIVO ==== */
@media (max-width: 600px) {
    .toggle-btn {
        padding: 8px 18px;
        font-size: 0.85rem;
    }
}
</style>


<div class="toggle-wrapper">
    <button id="<?= htmlspecialchars($btn1Id) ?>" 
        class="toggle-btn <?= $btn1Active ? 'active' : '' ?>">
        <?= htmlspecialchars($btn1Text) ?>
    </button>

    <button id="<?= htmlspecialchars($btn2Id) ?>" 
        class="toggle-btn <?= $btn1Active ? '' : 'active' ?>">
        <?= htmlspecialchars($btn2Text) ?>
    </button>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn1 = document.getElementById('<?= $btn1Id ?>');
    const btn2 = document.getElementById('<?= $btn2Id ?>');
    const content1 = document.getElementById('<?= $conteudo1Id ?>');
    const content2 = document.getElementById('<?= $conteudo2Id ?>');

    function toggle(active, inactive, showContent, hideContent) {
        active.classList.add("active");
        inactive.classList.remove("active");
        showContent.style.display = "block";
        hideContent.style.display = "none";
    }

    btn1.addEventListener('click', () => {
        toggle(btn1, btn2, content1, content2);
    });

    btn2.addEventListener('click', () => {
        toggle(btn2, btn1, content2, content1);
    });
});
</script>
