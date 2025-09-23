<?php
// view/partials/user_form.php
// Partial do formulário de usuário (usado por register e edit_user).
// Variáveis opcionais que o caller pode definir antes do require:
// - $formAction (string) : URL do action do form. default: 'index.php?action=register'
// - $user (array) : dados do usuário para edição. Se definido, o form ficará no modo "editar"
// - $isAdminRegister (bool) : se true, mostra recursos extras de admin

$formAction = $formAction ?? 'index.php?action=register';
$isEdit = isset($user) && !empty($user['id']);
$isAdminRegister = $isAdminRegister ?? false;   // padrão: false

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

            <?php if ($isAdminRegister): // Apenas admin pode gerar/copiar senha ?>
                <!-- Botões Gerar e Copiar -->
                <div class="mt-2 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="generatePassword()">Gerar senha</button>
                    <button type="button" id="copyBtn" class="btn btn-sm btn-secondary" style="display:none;" onclick="copyPassword()">Copiar</button>
                </div>

                <!-- Senha gerada -->
                <div id="generatedPasswordContainer" class="mt-2" style="display:none;">
                    <small><strong>Senha gerada:</strong> <span id="generatedPassword"></span></small>
                    <div id="copyMessage" class="text-success small mt-1" style="display:none;">Senha copiada!</div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label for="confirmPassword" class="form-label">Confirme sua senha</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                   placeholder="Confirme sua senha" <?= $isEdit ? '' : 'required' ?>>
            <?php if ($isAdminRegister): ?>
                <small class="text-muted d-block mt-1">Recomende ao usuário alterar a senha no primeiro login.</small>
            <?php endif; ?>
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

        <?php if ($isAdminRegister): // Campo categoria só para admin ?>
        <div class="col-md-6">
            <label for="category" class="form-label">Categoria</label>
            <select class="form-select" id="category" name="category">
                <option value="" <?= empty($user['category']) ? 'selected disabled' : 'disabled' ?>>Selecione</option>
                <option value="0" <?= isset($user['category']) && $user['category'] == 0 ? 'selected' : '' ?>>Normal</option>
                <option value="1" <?= isset($user['category']) && $user['category'] == 1 ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary w-100">
            <?= $isEdit ? 'Salvar Alterações' : 'Cadastrar' ?>
        </button>
    </div>

    <?php if (!$isEdit): // Link só para cadastro normal ?>
    <div class="mt-3 text-center">
        <small>Já possui uma conta?</small>
        <a href="index.php?action=login" class="btn btn-link p-0">Entrar</a>
    </div>
    <?php endif; ?>
</form>

<?php if ($isAdminRegister): // Funções JS apenas no modo admin ?>
<script>
function generatePassword() {
    const letters = "abcdefghijklmnopqrstuvwxyz";
    const numbers = "0123456789";
    let password = "";

    for (let i = 0; i < 4; i++) password += letters[Math.floor(Math.random() * letters.length)];
    for (let i = 0; i < 4; i++) password += numbers[Math.floor(Math.random() * numbers.length)];

    document.getElementById("password").value = password;
    document.getElementById("confirmPassword").value = password;

    document.getElementById("generatedPassword").textContent = password;
    document.getElementById("generatedPasswordContainer").style.display = "block";

    // Mostra botão copiar
    document.getElementById("copyBtn").style.display = "inline-block";
}

function copyPassword() {
    const password = document.getElementById("generatedPassword").textContent;
    navigator.clipboard.writeText(password).then(() => {
        document.getElementById("copyMessage").style.display = "block";
        setTimeout(() => {
            document.getElementById("copyMessage").style.display = "none";
        }, 2000);
    });
}
</script>
<?php endif; ?>
