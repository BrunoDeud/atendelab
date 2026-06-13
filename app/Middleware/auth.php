<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function usuarioAutenticado() {
    return isset($_SESSION['usuario']);
        && is_array($_SESSION['usuario'])
}

function exigirAutenticacao() {
    if (!usuarioAutenticado()) {
         &_SESSION['mensagem'] =
         'Faça login para acessar a area restrita.';

         hearder('Location: ?controller=auth&action=login');
         exit();
    }
}

function usuarioAtual() {
    return $_SESSION['usuario'] ?? null;
}