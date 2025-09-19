<?php
// controller/UserController.php
// Lida com as ações do CRUD (listar, criar, editar, excluir)

// Carrega model
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Enums.php';

class UserController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    // Tela inicial (home)
    public function home() {
        include __DIR__ . '/../view/home.php';
    }

    // Exibir formulário de login
    public function loginForm() {
        include __DIR__ . '/../view/login.php';
    }

    // Processar login
    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $loggedUser = $this->user->verifyLogin($email, $password);

        if ($loggedUser) {
            session_start();
            $_SESSION['user_id'] = $loggedUser['id'];
            $_SESSION['user_name'] = $loggedUser['name'];
            $_SESSION['user_category'] = $loggedUser['category'];

            header("Location: index.php");
            exit;
        } else {
            $errorMessage = "E-mail ou senha inválidos!";
            include __DIR__ . '/../view/login.php';
        }
    }

    // Exibir formulário de registro
    public function registerForm() {
        include __DIR__ . '/../view/register.php';
    }

    // Processar registro
    public function register() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $birthDate = $_POST['birth_date'];
        $gender = Gender::from($_POST['gender']);
        $laterality = isset($_POST['laterality']) ? Laterality::from($_POST['laterality']) : null;
        $phone = $_POST['phone'];
        $profession = $_POST['profession'];

        // Validações
        if ($password !== $confirmPassword) {
            $errorMessage = "As senhas não coincidem!";
            include __DIR__ . '/../view/register.php';
            return;
        }

        // Chama a model para criar o usuário
        $this->user->create($name, $email, $password, $birthDate, $gender, $laterality, $phone, $profession);

        // Redireciona para a tela de login
        header("Location: index.php?action=loginForm");
        exit;
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // // Mostrar formulário de edição
    // public function edit($id) {
    //     $user = $this->user->find($id);
    //     include __DIR__ . '/../view/edit.php';
    // }

    // // Atualizar usuário (POST)
    // public function update($id) {
    //     $this->user->update(
    //         $id, 
    //         $_POST['name'], 
    //         $_POST['email'], 
    //         $_POST['phone'], 
    //         $_POST['birth_date'], 
    //         $_POST['category'], 
    //         $_POST['gender'], 
    //         $_POST['laterality'], 
    //         $_POST['profession']
    //         // senha será tratada em um update específico
    //     );
    //     header("Location: index.php?action=index");
    // }

    // // Deletar usuário
    // public function delete($id) {
    //     $this->user->delete($id);
    //     header("Location: index.php?action=index");
    // }
}
