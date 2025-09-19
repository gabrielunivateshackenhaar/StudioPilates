<?php
// partials/admin_users.php
// Exibe a lista de usuários cadastrados (somente para admin)
?>

<div class="card shadow-sm">
    <div class="card-body">

        <?php if (count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Profissão</th>
                            <th>Data de Nascimento</th>
                            <th>Categoria</th>
                            <th>Gênero</th>
                            <th>Lateralidade</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($u['profession'] ?? '-') ?></td>
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
                                <td>
                                    <?= match ($u['laterality']) {
                                        Laterality::RIGHT->value => 'Destro',
                                        Laterality::LEFT->value => 'Canhoto',
                                        Laterality::AMBIDEXTROUS->value => 'Ambidestro',
                                        default => '-'
                                    } ?>
                                </td>
                                <td class="text-center">
                                    <a href="index.php?action=editUser&id=<?= $u['id'] ?>"
                                        class="text-secondary" title="Editar">
                                        <i class="bi bi-pencil me-2"></i></a>

                                    <!-- Botão para abrir o modal -->
                                    <a href="#" class="text-secondary" title="Excluir"
                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal<?= $u['id'] ?>">
                                        <i class="bi bi-trash"></i></a>

                                    <!-- Modal de confirmação -->
                                    <div class="modal fade" id="confirmDeleteModal<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar exclusão</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza que deseja excluir o usuário <strong><?= htmlspecialchars($u['name']) ?></strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <a href="index.php?action=deleteUser&id=<?= $u['id'] ?>" class="btn btn-danger">Excluir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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