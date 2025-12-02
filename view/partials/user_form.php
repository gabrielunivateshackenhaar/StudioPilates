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
<form action="<?= htmlspecialchars($formAction) ?>" method="post" class="needs-validation" novalidate>
  <div class="row g-3">
    <!-- Nome -->
    <div class="col-12 col-md-6">
      <label for="name" class="form-label">Nome</label>
      <input type="text" class="form-control" id="name" name="name"
        placeholder="Digite seu nome"
        value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
      <div class="invalid-feedback">Digite seu nome.</div>
    </div>

    <!-- Email -->
    <div class="col-12 col-md-6">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email"
        placeholder="Digite seu email"
        value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
      <div class="invalid-feedback">Digite um email válido (ex: usuario@email.com).</div>
    </div>

    <!-- Senha -->
    <div class="col-12 col-md-6">
      <label for="password" class="form-label">Senha</label>
      <input type="password" class="form-control" id="password" name="password"
        placeholder="Digite sua senha"
        minlength="8"
        pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$"
        <?= $isEdit ? '' : 'required' ?>>
      <?php if ($isEdit): ?>
        <small class="text-muted">Deixe em branco para manter a senha atual.</small>
      <?php endif; ?>
      <small class="text-muted d-block">Mínimo 8 caracteres, com maiúscula, minúscula e número.</small>
      <div class="invalid-feedback">A senha não atende aos requisitos.</div>

      <?php if ($isAdminRegister): ?>
        <div class="mt-2 d-flex justify-content-between">
          <button type="button" class="btn btn-sm btn-secondary" onclick="generatePassword()">Gerar senha</button>
          <button type="button" id="copyBtn" class="btn btn-sm btn-secondary" style="display:none;" onclick="copyPassword()">Copiar</button>
        </div>

        <div id="generatedPasswordContainer" class="mt-2" style="display:none;">
          <small><strong>Senha gerada:</strong> <span id="generatedPassword"></span></small>
          <div id="copyMessage" class="text-success small mt-1" style="display:none;">Senha copiada!</div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Confirmar senha -->
    <div class="col-12 col-md-6">
      <label for="confirmPassword" class="form-label">Confirme sua senha</label>
      <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
        placeholder="Confirme sua senha" <?= $isEdit ? '' : 'required' ?>>
      <div class="invalid-feedback">As senhas não coincidem.</div>
      <?php if ($isAdminRegister): ?>
        <small class="text-muted d-block mt-1">Recomende ao usuário alterar a senha no primeiro login.</small>
      <?php endif; ?>
    </div>

    <!-- Data de nascimento -->
    <div class="col-12 col-md-6">
      <label for="birth_date" class="form-label">Data de Nascimento</label>
      <input type="date" class="form-control" id="birth_date" name="birth_date"
        value="<?= htmlspecialchars($user['birth_date'] ?? '') ?>" required>
      <div class="invalid-feedback">Informe uma data válida.</div>
    </div>

    <!-- Sexo -->
    <div class="col-12 col-md-6">
      <label for="gender" class="form-label">Sexo</label>
      <select class="form-select" id="gender" name="gender" required>
        <option value="" disabled <?= empty($user['gender']) ? 'selected' : '' ?>>Selecione</option>
        <option value="0" <?= isset($user['gender']) && $user['gender'] == 0 ? 'selected' : '' ?>>Masculino</option>
        <option value="1" <?= isset($user['gender']) && $user['gender'] == 1 ? 'selected' : '' ?>>Feminino</option>
      </select>
      <div class="invalid-feedback">Selecione uma opção.</div>
    </div>

    <!-- Celular -->
    <div class="col-12 col-md-6">
      <label for="phone" class="form-label">Celular</label>
      <input type="tel" class="form-control" id="phone" name="phone"
        placeholder="(99) 99999-9999"
        pattern="\(\d{2}\)\s\d{5}-\d{4}"
        value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
      <small class="text-muted">Formato: (99) 99999-9999</small>
      <div class="invalid-feedback">Digite um telefone válido no formato (99) 99999-9999.</div>
    </div>

    <!-- Categoria (somente admin) -->
    <?php if ($isAdminRegister): ?>
    <div class="col-12 col-md-6">
      <label for="category" class="form-label">Categoria</label>
      <select class="form-select" id="category" name="category">
        <option value="" <?= empty($user['category']) ? 'selected disabled' : 'disabled' ?>>Selecione</option>
        <option value="0" <?= isset($user['category']) && $user['category'] == 0 ? 'selected' : '' ?>>Normal</option>
        <option value="1" <?= isset($user['category']) && $user['category'] == 1 ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>
    <?php endif; ?>
  </div>

  <div class="row mt-4">
    <div class="col-6 ps-2">
      <button type="button" class="btn-register-cancel w-100" onclick="window.history.back(); return false;">
        Cancelar
      </button>
    </div>
    <div class="col-6 pe-2">
      <button type="submit" class="btn-register-submit w-100">
        <?= $isEdit ? 'Salvar Alterações' : 'Cadastrar' ?>
      </button>
    </div>
  </div>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="text-center mt-3">
      <span>Já possui uma conta?</span>
      <a href="index.php?action=login" class="ms-1">Entrar</a>
    </div>
  <?php endif; ?>
</form>

<!-- CSS para campos opcionais -->
<style>
.was-validated .form-control.optional:valid,
.was-validated .form-select.optional:valid {
  border-color: #ced4da !important;
  box-shadow: none !important;
}
</style>

<!-- Script de validação -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector('.needs-validation');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');

  function validatePasswords() {
    if (!confirmPassword.value) {
      confirmPassword.classList.remove('is-valid', 'is-invalid');
      confirmPassword.setCustomValidity('');
      return;
    }

    if (password.value === confirmPassword.value) {
      confirmPassword.classList.add('is-valid');
      confirmPassword.classList.remove('is-invalid');
      confirmPassword.setCustomValidity('');
    } else {
      confirmPassword.classList.add('is-invalid');
      confirmPassword.classList.remove('is-valid');
      confirmPassword.setCustomValidity('As senhas não coincidem');
    }
  }

  password && password.addEventListener('input', validatePasswords);
  confirmPassword && confirmPassword.addEventListener('input', validatePasswords);

  // Validação em tempo real
  form.querySelectorAll('input, select, textarea').forEach(field => {
    field.addEventListener('input', () => {
      if (field.classList.contains('optional')) {
        if (!field.value.trim()) {
          field.classList.remove('is-valid', 'is-invalid');
          field.setCustomValidity('');
          return;
        }
      }

      if (field.checkValidity()) {
        field.classList.add('is-valid');
        field.classList.remove('is-invalid');
      } else {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
      }
    });
  });

  // Validação final no submit
  if (form) {
    form.addEventListener('submit', event => {
      validatePasswords();

      form.querySelectorAll('.optional').forEach(field => {
        if (!field.value.trim()) {
          field.classList.remove('is-valid', 'is-invalid');
        }
      });

      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');
    }, false);
  }

  // Adição da máscara do telefone
  const phoneInput = document.getElementById("phone");

  phoneInput.addEventListener("input", () => {
    let value = phoneInput.value.replace(/\D/g, ""); // remove tudo que não é número

    if (value.length > 11) value = value.slice(0, 11); // limita em 11 dígitos

    if (value.length > 6) {
      phoneInput.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
    } else if (value.length > 2) {
      phoneInput.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
    } else {
      phoneInput.value = value;
    }
  });
});
</script>

<?php if ($isAdminRegister): // Funções JS apenas no modo admin ?>
<script>
function generatePassword() {
    const lower = "abcdefghijklmnopqrstuvwxyz";
    const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const numbers = "0123456789";
    let password = "";

    for (let i = 0; i < 3; i++) password += lower[Math.floor(Math.random() * lower.length)];
    for (let i = 0; i < 3; i++) password += upper[Math.floor(Math.random() * upper.length)];
    for (let i = 0; i < 2; i++) password += numbers[Math.floor(Math.random() * numbers.length)];

    password = password.split('').sort(() => 0.5 - Math.random()).join('');

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
