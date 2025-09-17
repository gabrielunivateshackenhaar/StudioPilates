<?php
// controller/StudentController.php
// Lida com as ações do CRUD (listar, criar, editar, excluir)

// Carrega model
require_once __DIR__ . '/../model/Student.php';

class StudentController {
    private $student;

    public function __construct($pdo) {
        $this->student = new Student($pdo);
    }

    // Listar alunos
    public function index() {
        $students = $this->student->all();
        include __DIR__ . '/../view/index.php';
    }

    // Mostrar formulário de criação
    public function create() {
        include __DIR__ . '/../view/create.php';
    }

    // Salvar novo aluno (POST)
    public function store() {
        $this->student->create($_POST['name'], $_POST['email'], $_POST['phone']);
        header("Location: index.php?action=index");
    }

    // Mostrar formulário de edição
    public function edit($id) {
        $student = $this->student->find($id);
        include __DIR__ . '/../view/edit.php';
    }

    // Atualizar aluno (POST)
    public function update($id) {
        $this->student->update($id, $_POST['name'], $_POST['email'], $_POST['phone']);
        header("Location: index.php?action=index");
    }

    // Deletar aluno
    public function delete($id) {
        $this->student->delete($id);
        header("Location: index.php?action=index");
    }
}
