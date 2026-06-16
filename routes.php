<?php

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

    case 'tiposatendimentos':
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
            default:

                http_response_code(404);
                echo 'Ação não encontrada.';
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
            case 'cadastrar':
                $pessoasController->cadastrar();
                break;
            case 'atualizar':
                $pessoasController->atualizar();
                break;
            case 'excluir':
                $pessoasController->excluir();
                break;
            default:

                http_response_code(404);
                echo 'Ação não encontrada.';
                break;
        }
        break;

    case 'atendimentos':
        $atendimentosController = new AtendimentosController();

        switch ($action) {
            case 'listarAtendimentos':
                $atendimentosController->listarAtendimentos();
                break;
            case 'buscarAtendimento':
                $atendimentosController->buscarAtendimento();
                break;
            case 'criarNovoAtendimento':
                $atendimentosController->criarNovoAtendimento();
                break;
            case 'atualizarAtendimento':
                $atendimentosController->atualizarAtendimento();
                break;
            case 'excluirAtendimento':
                $atendimentosController->excluirAtendimento();
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