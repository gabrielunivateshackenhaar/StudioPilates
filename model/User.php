<?php
// model/User.php
// Classe responsável por interagir com a tabela users

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; // Conexão com o banco
    }

    // Buscar todos os usuários
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um usuário por ID
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar novo usuário
    public function create($name, $email, $phone, $birth_date, $category, $gender, $laterality, $profession, $password) {
        $stmt = $this->pdo->prepare("
        INSERT INTO students (name, email, phone, birth_date, category, gender, laterality, profession, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $birth_date, $category, $gender, $laterality, $profession, $password]);
    }

    // Atualizar usuário
    public function update($id, $name, $email, $phone, $birth_date, $category, $gender, $laterality, $profession) {
        $stmt = $this->pdo->prepare("
        UPDATE users 
        SET name = ?, email = ?, phone = ?, birth_date = ?, category = ?, gender = ?, lateralit = ?, profession = ? 
        WHERE id = ?");
        return $stmt->execute([$name, $email, $phone, $birth_date, $category, $gender, $laterality, $profession, $id]);
    }

    // Deletar usuário
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
