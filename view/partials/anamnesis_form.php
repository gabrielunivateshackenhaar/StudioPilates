<?php
// view/partials/anamnesis_form.php
// Partial reutilizável para dados de saúde/anamnese
// Espera a variável $assessment (array) com os dados
?>

<div class="row g-3">
    
    <div class="col-md-6">
        <label class="form-label fw-bold">Profissão</label>
        <input type="text" class="form-control" name="profession" 
            value="<?= htmlspecialchars($assessment['profession'] ?? '') ?>" 
            placeholder="Ex: Dentista, Motorista...">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Lateralidade</label>
        <select class="form-select" name="laterality">
            <option value="" selected disabled>Selecione...</option>
            <option value="0" <?= (isset($assessment['laterality']) && $assessment['laterality'] == 0) ? 'selected' : '' ?>>Destro</option>
            <option value="1" <?= (isset($assessment['laterality']) && $assessment['laterality'] == 1) ? 'selected' : '' ?>>Canhoto</option>
            <option value="2" <?= (isset($assessment['laterality']) && $assessment['laterality'] == 2) ? 'selected' : '' ?>>Ambidestro</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Altura (m)</label>
        <input type="text" class="form-control" name="height" 
            value="<?= htmlspecialchars($assessment['height'] ?? '') ?>" 
            placeholder="Ex: 1.70">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Peso (kg)</label>
        <input type="text" class="form-control" name="weight" 
            value="<?= htmlspecialchars($assessment['weight'] ?? '') ?>" 
            placeholder="Ex: 65.5">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">Diagnóstico Clínico</label>
        <textarea class="form-control" name="diagnosis" rows="2" 
            placeholder="Possui algum diagnóstico médico? (Ex: Hérnia de disco, Escoliose...)"><?= htmlspecialchars($assessment['diagnosis'] ?? '') ?></textarea>
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">Queixa Principal / Objetivos</label>
        <textarea class="form-control" name="complaint" rows="3" 
            placeholder="Onde sente dor? Qual o objetivo com o Pilates?"><?= htmlspecialchars($assessment['complaint'] ?? '') ?></textarea>
    </div>

</div>