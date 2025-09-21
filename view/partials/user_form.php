<?php
// view/partials/user_form.php
// Partial do formulário de usuário (usado por register e edit_user).
// Variáveis opcionais que o caller pode definir antes do require:
// - $formAction (string) : URL do action do form. default: 'index.php?action=register'
// - $user (array) : dados do usuário para edição. Se definido, o form ficará no modo "editar"

$formAction = $formAction ?? 'index.php?action=register';
$isEdit = isset($user) && !empty($user['id']);
?>
<form action="<?= htmlspecialchars($formAction) ?>" method="post">
    <div class="row g-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name"
                   placeholder="Digite seu nome"
                   value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="Digite seu email"
                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="Digite sua senha" <?= $isEdit ? '' : 'required' ?>>
            <?php if ($isEdit): ?>
                <small class="text-muted">Deixe em branco para manter a senha atual.</small>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label for="confirmPassword" class="form-label">Confirme sua senha</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                   placeholder="Confirme sua senha" <?= $isEdit ? '' : 'required' ?>>
        </div>

        <div class="col-md-6">
            <label for="birth_date" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date"
                   value="<?= htmlspecialchars($user['birth_date'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="gender" class="form-label">Sexo</label>
            <select class="form-select" id="gender" name="gender" required>
                <option value="" <?= empty($user['gender']) ? 'selected disabled' : 'disabled' ?>>Selecione</option>
                <option value="0" <?= isset($user['gender']) && $user['gender'] == 0 ? 'selected' : '' ?>>Masculino</option>
                <option value="1" <?= isset($user['gender']) && $user['gender'] == 1 ? 'selected' : '' ?>>Feminino</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="phone" class="form-label">Celular</label>
            <input type="tel" class="form-control" id="phone" name="phone"
                   placeholder="(99) 99999-9999"
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="profession" class="form-label">Profissão (opcional)</label>
            <input type="text" class="form-control" id="profession" name="profession"
                   placeholder="Sua profissão"
                   value="<?= htmlspecialchars($user['profession'] ?? '') ?>">
        </div>

        <div class="col-md-6">
            <label for="laterality" class="form-label">Lateralidade (opcional)</label>
            <select class="form-select" id="laterality" name="laterality">
                <option value="" <?= empty($user['laterality']) ? 'selected disabled' : 'disabled' ?>>Selecione</option>
                <option value="0" <?= isset($user['laterality']) && $user['laterality'] == 0 ? 'selected' : '' ?>>Destro</option>
                <option value="1" <?= isset($user['laterality']) && $user['laterality'] == 1 ? 'selected' : '' ?>>Canhoto</option>
                <option value="2" <?= isset($user['laterality']) && $user['laterality'] == 2 ? 'selected' : '' ?>>Ambidestro</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary w-100">
            <?= $isEdit ? 'Salvar Alterações' : 'Cadastrar' ?>
        </button>
    </div>

    <?php if (! $isEdit): ?>
    <div class="mt-3 text-center">
        <small>Já possui uma conta?</small>
        <a href="index.php?action=login" class="btn btn-link p-0">Entrar</a>
    </div>
    <?php endif; ?>
</form>
