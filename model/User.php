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
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um usuário por ID
    public function getById($id) {
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
    public function create(
        string $name,
        string $email,
        string $password,
        string $birthDate,
        Gender $gender,
        ?Laterality $laterality,
        string $phone,
        string $profession,
        ?string $confirmationCode,
        int $category = Category::NORMAL->value, // padrão NORMAL
        int $status = UserStatus::INACTIVE->value
    ) {
        $stmt = $this->pdo->prepare("
            INSERT INTO users
            (name, email, password, birth_date, gender, laterality, phone, profession, category, confirmation_code, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Sempre hash a senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            $name,
            $email,
            $hashedPassword,
            $birthDate,
            $gender->value,
            $laterality?->value,
            $phone,
            $profession,
            $category,
            $confirmationCode,
            $status
        ]);
    }

    public function update($id, $data) {
        $fields = [
            'name'       => $data['name'],
            'email'      => $data['email'],
            'birth_date' => $data['birth_date'],
            'gender'     => $data['gender'],
            'phone'      => $data['phone'],
            'profession' => $data['profession'],
            'laterality' => $data['laterality'],
        ];

        // Se senha foi preenchida, atualiza também
        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Cria a parte "SET campo = :campo, ..." da query dinamicamente
        $setPart = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));

        // Adiciona o ID ao array, usado no WHERE
        $fields['id'] = $id;

        $stmt = $this->pdo->prepare("UPDATE users SET $setPart WHERE id = :id");
        
        return $stmt->execute($fields);
    }

    // Ativa a conta do usuário após a confirmação pelo e-mail
    public function activateUser(int $id) {
        $stmt = $this->pdo->prepare("
        UPDATE users
        SET status = ?, 
        confirmation_code = NULL
        WHERE id = ?
        ");

        return $stmt->execute([
            UserStatus::ACTIVE->value,
            $id
        ]);
    }

    // Atualiza o código de confirmação, usado no reenvio
    public function updateCode(string $email, string $newCode) {
        $stmt = $this->pdo->prepare("UPDATE users SET confirmation_code = ? WHERE email = ?");
        return $stmt->execute([$newCode, $email]);
    }

    // Deletar usuário
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
