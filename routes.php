<?php

require_once __DIR__ . '/app/Controllers/UsuarioController.php';

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
            
            echo 'Ação de usuarios não encontrada.';
            break;
    }
}

if ($controller === 'pessoas') {
    require_once __DIR__ . '/app/Controllers/PessoasController.php';
    $pessoasController = new PessoasController();

    switch ($action) {
        case 'listar':
            $pessoasController->listar();
            break;

        case 'buscar':
            $pessoasController->buscarPorId();
            break;
            
        case 'criar':
            $pessoasController->criar();
            break;
        
        default:
            echo 'Ação de pessoas não encontrada.';
            break;
    }
}

else {
    echo '<h1>AtendeLab</h1>';
    echo '<p>Projeto em execução. Use ?controller=usuarios&action=listar para testar.</p>';
}