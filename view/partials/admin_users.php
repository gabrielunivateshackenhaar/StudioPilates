<?php
// partials/admin_users.php
// Exibe a lista de usuários cadastrados (somente para admin)
?>

<div class="card shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="mb-0">Usuários</h5>
            <a href="index.php?action=showRegisterUser" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Novo Usuário
            </a>
        </div>

        <?php if (count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Data de Nascimento</th>
                            <th>Categoria</th>
                            <th>Gênero</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                                <td>
                                    <?= isset($u['birth_date'])
                                        ? (new DateTime($u['birth_date']))->format('d/m/Y')
                                        : '-' ?>
                                </td>
                                <td>
                                    <?php if ($u['category'] == Category::ADMIN->value): ?>
                                        <span class="badge bg-warning text-dark">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= match ($u['gender']) {
                                        Gender::MALE->value => 'Masculino',
                                        Gender::FEMALE->value => 'Feminino',
                                        default => '-'
                                    } ?>
                                </td>
                                <td class="text-center">
                                    <a href="index.php?action=adminAssessment&userId=<?= $u['id'] ?>" 
                                        class="text-secondary" title="Avaliação Postural">
                                        <i class="bi bi-clipboard-pulse me-2"></i></a>
                                    
                                    <a href="index.php?action=editUser&id=<?= $u['id'] ?>"
                                        class="text-secondary" title="Editar">
                                        <i class="bi bi-pencil me-2"></i></a>

                                    <a href="#" class="text-secondary btn-delete-user" 
                                       data-id="<?= $u['id'] ?>" 
                                       data-name="<?= htmlspecialchars($u['name']) ?>"
                                       title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Nenhum usuário cadastrado até o momento.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteUserBtns = document.querySelectorAll('.btn-delete-user');

    deleteUserBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
                title: 'Tem certeza?',
                text: `Deseja realmente excluir o usuário "${name}"? Essa ação não pode ser desfeita.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `index.php?action=deleteUser&id=${id}`;
                }
            });
        });
    });
});
</script>