<?php
// model/User.php
// Classe responsável por interagir com a tabela users

require_once __DIR__ . '/Enums.php';

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

    // Buscar usuário por email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar login
    public function verifyLogin($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Criar novo usuário
    public function create($name, $email, $password, $birthDate, $gender, $laterality, $phone, $profession ) {
        $stmt = $this->pdo->prepare("
            INSERT INTO users
            (name, email, password, birth_date, gender, laterality, phone, profession, category)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Sempre hash a senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Category NORMAL por padrão
        $category = Category::NORMAL->value;

        return $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $birthDate,
            $gender->value,
            $laterality->value,
            $phone,
            $profession,
            $category
        ]);
    }

    // // Atualizar usuário
    // public function update($id, $name, $email, $phone, $birth_date, $category, $gender, $laterality, $profession) {
    //     $stmt = $this->pdo->prepare("
    //     UPDATE users 
    //     SET name = ?, email = ?, phone = ?, birth_date = ?, category = ?, gender = ?, lateralit = ?, profession = ? 
    //     WHERE id = ?");
    //     return $stmt->execute([$name, $email, $phone, $birth_date, $category, $gender, $laterality, $profession, $id]);
    // }

    // // Deletar usuário
    // public function delete($id) {
    //     $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
    //     return $stmt->execute([$id]);
    // }
}
