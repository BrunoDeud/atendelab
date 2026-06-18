<?php

// Importa a conexão com o banco de dados
require_once __DIR__ . '/../../config/database.php';
// Importa funções auxiliares de autenticação e sessão
require_once __DIR__ . "/../Middleware/auth.php";

class AuthController
{
    // Armazena a conexão PDO
    private PDO $pdo;

    public function __construct()
    {
        // Recupera a coenxãp criada em database.php
        global $pdo;

        // Disponibiliza a conexão para os métodos da classe
        $this->pdo = $pdo;
    }

    public function exibirLogin(): void
    {
        // Se o usuario ja estiver logado, redireciona para o dashboard
        if (usuarioAutenticado()) {
            header('location: ?controller=auth&action=dashboard');
            exit;
        }

        //Recupera mensagens temporarias da sessao
        $erro = $_SESSION['erro_login'] ?? null;
        $mensage = $_SESSION['mensagem'] ?? null;

        // Remove as mensagens para que aparecam somente uma vez
        unset($_SESSION['erro_login'], $_SESSION['mensagem']);

        // Carrega a tela de login
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function entrar(): void
    {
        // Permite executar o login somente por requisicao POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('location: ?controller=auth&action=login');
            exit;
        }

        // Recupera os dados enviados pelo formulario
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        // Verifica se os campos obrigatorios foram preenchidos.
        if ($email === '' || $senha === '') {
            $_SESSION['erro_login'] = 'Informe o e-mail e senha.';

            header('Location: ?controller=auth&action=login');
            exit;
        }

        // Verifica se o e-mail possui um formato valido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro_login'] = 'Informe um e-mail valido.';

            header('Location: ?controller=auth&action=login');
            exit;
        }

        // Busca o usuario por e-mail
        $sql = 'SELECT id, nome, email, senha, perfil, status
                FROM usuarios
                WHERE email = :email
                LIMIT 1';

        // Prepara a consulta para envitar SQL injection
        $stmt = $this->pdo->prepare($sql);

        // Substitui o parametro :email pelo valor informado
        $stmt->bindValue(':email', $email);

        // Executa a consulta
        $stmt->execute();

        // Recupera o usuário encontrado
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Valida usuario existente, status ativo e senha correta
        if (!$usuario || $usuario['status'] !== 'ativo' || !password_verify($senha, $usuario['senha'])) {
            $_SESSION['erro_login'] = 'E-mail ou senha inválidos.';

            header('Location: ?controller=auth&action=login');
            exit;
        }

        // Gera um novo ID de sessão por seguranca
        session_regenerate_id(true);

        // Salva na sessão apenas os dados necessários do usuário
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'perfil' => $usuario['perfil'],
        ];


        // Redireciona para o dashboard
        header('Location: ?controller=auth&action=dashboard');
        exit;
    }

    public function dashboard(): void
    {
        //Bloqueia o acesso caso o usuário não esteja logado
        exigirAutenticacao();

        // Recupera os dados do usuário autenticado
        $usuario = usuarioAtual();

        // Carrega a página interna
        require __DIR__ . '/../Views/dashboard/index.php';
    }

    public function logout(): void
    {
        //Remove os dados armazenados na sessão
        $_SESSION = [];

        // Remove o cookie da sessão, caso esteja sendo utilizado
        if (ini_get('session.use.cookie')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Encerra a sessão atual
        session_destroy();

        // Inicia nova sessão apenas para enviar a mensagem de retorno
        session_start();

        // Mensagem exibida após o logout
        $_SESSION['mensagem'] = 'Sessão encerrada com sucesso.';

        header('Location: ?controller=auth&action=login');
        exit;
    }
}