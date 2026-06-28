<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ... restante do seu routes.php original
require_once __DIR__ . '/app/Controllers/FrontendController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentos.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    case 'auth':
        $authController = new AuthController();

        switch ($action) {
            case 'login':
                $authController->exibirLogin();
                break;
            case 'entrar':
                $authController->entrar();
                break;
            case 'dashboard':
                $authController->dashboard();
                break;
            case 'logout':
                $authController->logout();
                break;

            default:

                http_response_code(404);
                echo 'Ação de Autenticação não encontrada.';
                break;
        }
        break;

    case 'dashboard':
        exigirAutenticacao();
        $dashboardController = new DashboardController();

        switch ($action) {
            case 'resumo':
            $dashboardController->resumo();
            break;
      default:
        http_response_code(404);
        echo 'Ação de dashboard não encontrada.';
    }
    break;

    case 'frontend':
        exigirAutenticacao();
        $frontendController = new FrontendController();

        switch ($action) {
            case 'pessoas':
                $frontendController->pessoas();
                break;
            case 'tipos':
                $frontendController->tipos();
                break;
            case 'atendimentos':
                $frontendController->atendimentos();
                break;
            default:
                http_response_code(404);
                echo 'Página visual não encontrada.';
                break;
        }
        break;

    case 'usuarios':
        $usuariosController = new UsuariosController();

        switch ($action) {
            case 'listar':
                $usuariosController->listar();
                break;
            case 'buscar':
                $usuariosController->buscarPorId();
                break;
            case 'criar':
                $usuariosController->criar();
                break;
            case 'atualizar':
                $usuariosController->atualizar();
                break;
            case 'excluir':
                $usuariosController->excluir();
                break;
            default:

                http_response_code(404);
                echo 'Ação de usuários não encontrada.';
                break;
        }
        break;

    case 'pessoas':
        $pessoasController = new PessoasController();

        switch ($action) {
            case 'listar':
                $pessoasController->listar();
                break;
            case 'buscar':
                $pessoasController->buscarPorId();
                break;
            case 'criar':
            case 'cadastrar':
                $pessoasController->cadastrar();
                break;
            case 'atualizar':
                $pessoasController->atualizar();
                break;
            case 'excluir':
                $pessoasController->excluir();
                break;
            case 'inativar':
                $pessoasController->inativar();
                break;
            default:

                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;
    case 'tipos':
    case 'tiposatendimentos':
        exigirAutenticacao();
        $tiposAtendimentos = new TiposAtendimentos();

        switch ($action) {
            case 'criarTipoAtendimento':
                $tiposAtendimentos->criarTipoAtendimento();
                break;
            case 'listarTipoAtendimento':
                $tiposAtendimentos->listarTipoAtendimento();
                break;
            case 'buscarAtendimento':
                $tiposAtendimentos->buscarAtendimento();
                break;
            case 'atualizarAtendimento':
                $tiposAtendimentos->atualizarAtendimento();
                break;
            case 'excluirAtendimento':
                $tiposAtendimentos->excluirAtendimento();
                break;
            case 'inativar':
                $tiposAtendimentos->inativar();
                break;
            default:

                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;

    case 'atendimentos':
        exigirAutenticacao();
        $atendimentosController = new AtendimentosController();

        switch ($action) {
            case 'listarAtendimentos':
                $atendimentosController->listarAtendimentos();
                break;
            case 'buscarAtendimento':
                $atendimentosController->buscarAtendimento();
                break;
            case 'criar':
            case 'criarNovoAtendimento':
                $atendimentosController->criarNovoAtendimento();
                break;
            case 'atualizarAtendimento':
                $atendimentosController->atualizarAtendimento();
                break;
            case 'excluirAtendimento':
                $atendimentosController->excluirAtendimento();
                break;
            case 'alterarStatus':
                $atendimentosController->alterarStatus();
                break;
            default:

                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;

    default:
        http_response_code(404);
        echo 'Controller não encontrado.';
        break;
}