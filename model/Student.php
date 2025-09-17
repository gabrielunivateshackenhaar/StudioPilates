<?php
// model/Student.php
// Classe responsável por interagir com a tabela students

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; // Conexão com o banco
    }

    // Buscar todos os alunos
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um aluno por ID
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar novo aluno
    public function create($name, $email, $phone) {
        $stmt = $this->pdo->prepare("INSERT INTO students (name, email, phone) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $phone]);
    }

    // Atualizar aluno
    public function update($id, $name, $email, $phone) {
        $stmt = $this->pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $phone, $id]);
    }

    // Deletar aluno
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
