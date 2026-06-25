<?php

class AtendimentosController
{
    private PDO $pdo;

        public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

        public function listarAtendimentos(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = 'SELECT id, pessoa_id, date_atendimento, descricao
                FROM atendimentos
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $atendimento = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($atendimento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

        public function criarNovoAtendimento(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $date_atendimento = htmlspecialchars(trim($_POST['date_atendimento'] ?? ''), ENT_QUOTES, 'UTF-8');
        $descricao = htmlspecialchars(trim($_POST['descricao'] ?? ''), ENT_QUOTES, 'UTF-8');

        if (!$pessoa_id || empty($date_atendimento) || empty($descricao)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            return;
        }

        $sql = 'INSERT INTO atendimentos (pessoa_id, date_atendimento, descricao)
                VALUES (:pessoa_id, :date_atendimento, :descricao)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
        $stmt->bindParam(':date_atendimento', $date_atendimento, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['mensagem' => 'Atendimento cadastrado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar atendimento.']);
        }
    }

        public function atualizarAtendimento(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $date_atendimento = htmlspecialchars(trim($_POST['date_atendimento'] ?? ''), ENT_QUOTES, 'UTF-8');
        $descricao = htmlspecialchars(trim($_POST['descricao'] ?? ''), ENT_QUOTES, 'UTF-8');

        if (!$id || !$pessoa_id || !$date_atendimento || !$descricao) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            return;
        }

        $sql = 'UPDATE atendimentos
                SET pessoa_id = :pessoa_id, date_atendimento = :date_atendimento, descricao = :descricao
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
        $stmt->bindParam(':date_atendimento', $date_atendimento, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['mensagem' => 'Atendimento atualizado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar atendimento.']);
        }
    }

        public function buscarAtendimento(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        $sql = 'SELECT id, pessoa_id, date_atendimento, descricao
                FROM atendimentos
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($atendimento) {
            echo json_encode($atendimento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Atendimento não encontrado.']);
        }
    }

        public function excluirAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID inválido'], 400);
        }
        try {
            $sql = 'DELETE FROM atendimentos WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem' => 'Atendimento excluído com sucesso.']);
            
        } catch (PDOException $e) {
           http_response_code(500);
            echo json_encode(['erro' => 'Erro ao excluir atendimento: ' . $e->getMessage()]);
        }
    }
}