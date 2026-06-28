<?php

class FrontendController
{
    public function __construct()
    {
        // Garante que a sessão está ativa e o usuário está logado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once __DIR__ . '/../Middleware/auth.php';
        exigirAutenticacao();
    }

    public function pessoas(): void
    {
        require __DIR__ . '/../views/pessoas/index.php';
    }

    public function tipos(): void
    {
        require __DIR__ . '/../views/tipos-atendimentos/index.php';
    }

    public function atendimentos(): void
    {
        require __DIR__ . '/../views/atendimentos/index.php';
    }
}