<?php

require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentos.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

if ($controller === 'usuarios') {
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
    
            echo 'Ação de usuários não encontrada.';
            break;
    }

} elseif ($controller === 'tiposatendimentos') {
    $tiposAtendimentos = new TiposAtendimentos();

    switch ($action) {
        case 'criarTipoAtendimento':
            $tiposAtendimentos->criarTipoAtendimento();
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

            echo 'Ação não encontrada.';
            break;
    }
    
} elseif ($controller === 'pessoas') {
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

            echo 'Ação não encontrada.';
            break;
    }

} elseif ($controller === 'atendimentos') {
    $atendimentosController = new AtendimentosController();

    switch ($action) {
        case 'listar':
            $atendimentosController->listar();
            break;

        case 'status':
            $atendimentosController->status();
            break;

        case 'cadastrar':
            $atendimentosController->cadastrar();
            break;

        case 'atualizar':
            $atendimentosController->atualizar();
            break;

        case 'visualizar':
            $atendimentosController->visualizar();
            break;

        default:

            echo 'Ação não encontrada.';
            break;
    }

} else {

    echo '<h1>AtendeLabb</h1>';
    echo '<p>Projeto em execução, Use ?controller=usuarios&action=listar para testar</p>';
}