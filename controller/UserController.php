<?php
// controller/UserController.php
// Lida com as ações do CRUD (listar, criar, editar, excluir)

// Carrega model
require_once __DIR__ . '/../model/User.php';

class UserController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    // Listar usuários
    public function index() {
        $users = $this->user->all();
        include __DIR__ . '/../view/index.php';
    }

    // Mostrar formulário de criação
    public function create() {
        include __DIR__ . '/../view/create.php';
    }

    // Salvar novo usuário (POST)
    public function store() {
        $this->user->create(
            $_POST['name'], 
            $_POST['email'],
            $_POST['phone'],
            $_POST['birth_date'], 
            $_POST['category'],
            $_POST['gender'],
            $_POST['laterality'],
            $_POST['profession'],
            $_POST['password'] // por enquanto texto, será criptografado
        ); 
        header("Location: index.php?action=index");
    }

    // Mostrar formulário de edição
    public function edit($id) {
        $user = $this->user->find($id);
        include __DIR__ . '/../view/edit.php';
    }

    // Atualizar usuário (POST)
    public function update($id) {
        $this->user->update(
            $id, 
            $_POST['name'], 
            $_POST['email'], 
            $_POST['phone'], 
            $_POST['birth_date'], 
            $_POST['category'], 
            $_POST['gender'], 
            $_POST['laterality'], 
            $_POST['profession']
            // senha será tratada em um update específico
        );
        header("Location: index.php?action=index");
    }

    // Deletar usuário
    public function delete($id) {
        $this->user->delete($id);
        header("Location: index.php?action=index");
    }
}
