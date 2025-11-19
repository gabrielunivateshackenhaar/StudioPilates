<?php
// controller/UserController.php
// Lida com as ações do CRUD (listar, criar, editar, excluir)

// Carrega model
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Enums.php';
require_once __DIR__ . '/../model/Schedule.php';
require_once __DIR__ . '/../model/Booking.php';

class UserController {
    private $user;
    private $scheduleModel;
    private $bookingModel;

    public function __construct($pdo) {
        $this->user = new User($pdo);
        $this->scheduleModel = new Schedule($pdo);
        $this->bookingModel = new Booking($pdo);
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

        // Categoria definida pelo admin
        $category = isset($_POST['category']) ? (int)$_POST['category'] : Category::NORMAL->value;

        // Chama a model para criar o usuário
        $this->user->create($name, $email, $password, $birthDate, $gender, $laterality, $phone, $profession, $category);

        // Decide para onde redirecionar
        if (isset($_SESSION['user_id']) && $_SESSION['user_category'] == Category::ADMIN->value) {
            // Admin cadastrando novo usuário
            header("Location: index.php?action=admin");
        } else {
            // Fluxo normal
            header("Location: index.php?action=loginForm");
        }
        exit;
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // Exibir página de admin
    public function adminPanel() {
        // Verifica se está logado e se é admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            header("Location: index.php");
            exit;
        }

        // Carrega dados dos usuários
        $users = $this->user->getAll();

        //Carrega dados dos horários
        $schedules = $this->scheduleModel->getAll();
        
        include __DIR__ . '/../view/admin.php';
    }

    // Exibir formulário de edição do usuário
    public function editUser() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=admin");
            exit;
        }

        $user = $this->user->getById($id);
        if (!$user) {
            header("Location: index.php?action=admin");
            exit;
        }

        // Define para onde o form vai enviar os dados
        $formAction = "index.php?action=updateUser&id={$id}";

        // Carrega a view de edição
        require __DIR__ . '/../view/edit_user.php';
    }

    // Processar atualização
    public function updateUser() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=admin");
            exit;
        }

        // Monta array de dados vindo do POST
        $data = [
            'name'       => $_POST['name'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'password'   => $_POST['password'] ?? null,
            'birth_date' => $_POST['birth_date'] ?? '',
            'gender'     => $_POST['gender'] ?? '',
            'phone'      => $_POST['phone'] ?? '',
            'profession' => $_POST['profession'] ?? '',
            'laterality' => $_POST['laterality'] ?? '',
        ];

        $this->user->update($id, $data);

        header("Location: index.php?action=admin");
        exit;
    }

    // Deletar usuário
    public function deleteUser() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->user->delete($id); // chama o método da model
        }
        header("Location: index.php?action=admin");
        exit;
    }

    // Exibe o formulário com os dados do usuário logado e seus agendamentos
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=showLogin");
            exit;
        }

        $id = $_SESSION['user_id'];

        // 1. Busca dados do usuário
        $user = $this->user->getById($id);
        if (!$user) {
            header("Location: index.php");
            exit;
        }

        // 2. Busca agendamentos
        $allBookings = $this->bookingModel->getByUserId($id);

        // 3. Separa em Futuros e Passados
        $futureBookings = [];
        $pastBookings = [];
        $now = new DateTime(); // Data/Hora atual

        foreach ($allBookings as $b) {
            // Cria um objeto DateTime com a data/hora da aula
            $classDateTime = new DateTime($b['date'] . ' ' . $b['time']);
            
            // Adiciona duração para saber quando a aula ACABA
            // (opcional, mas ajuda se a aula estiver acontecendo agora)
            $classEndTime = clone $classDateTime;
            $classEndTime->modify("+{$b['duration_minutes']} minutes");

            if ($classDateTime >= $now) {
                $futureBookings[] = $b;
            } else {
                $pastBookings[] = $b;
            }
        }

        // Define variáveis para o partial de form (para edição de dados)
        $formAction = "index.php?action=updateProfile";
        $isProfile = true; // Flag para saber que é perfil
        
        // Exibe a view de perfil
        require __DIR__ . '/../view/profile.php';
    }

    // Processar a atualização do PRÓPRIO perfil
    public function updateProfile() {
        // Verifica se está logado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=showLogin");
            exit;
        }

        $id = $_SESSION['user_id']; // O ID vem da sessão (segurança)

        // Coleta os dados do formulário
        $data = [
            'name'       => $_POST['name'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'password'   => $_POST['password'] ?? null, // Se vazio, a model ignora
            'birth_date' => $_POST['birth_date'] ?? '',
            'gender'     => $_POST['gender'] ?? '',
            'phone'      => $_POST['phone'] ?? '',
            'profession' => $_POST['profession'] ?? '',
            'laterality' => $_POST['laterality'] ?? '',
        ];

        // Atualiza no banco
        $this->user->update($id, $data);

        // Atualiza o nome na sessão (caso o usuário tenha mudado o nome)
        $_SESSION['user_name'] = $data['name'];

        // Redireciona de volta para o perfil com o parâmetro de sucesso
        header("Location: index.php?action=profile&status=success");
        exit;
    }

    // Exibir formulário de registro pelo Admin
    public function registerUserAdminForm() {
        // Verifica se está logado e se é admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_category'] != Category::ADMIN->value) {
            header("Location: index.php");
            exit;
        }

        include __DIR__ . '/../view/admin_register_user.php';
    }
}
