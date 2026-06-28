<?php

class TiposAtendimentos
{
    private PDO $pdo;

    public function __construct()
    {

        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function buscarAtendimento(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID invalido.']);
            return;
        }

        $sql = 'SELECT id, nome, descricao, status
                FROM tipos_atendimentos
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $tipos_atendimentos = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tipos_atendimentos) {
            http_response_code(404);
            echo json_encode(['erro' => 'Tipo de atendimentos não encontrado.']);
            return;
        }
        http_response_code(200);
        echo json_encode($tipos_atendimentos, JSON_UNESCAPED_UNICODE);
    }

    public function criarTipoAtendimento(): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if ($nome === '') {
            http_response_code(422);
            echo json_encode(['erro' => 'Nome do atendimento é obrigatorio']);
            return;
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(422);
            echo json_encode(['erro' => 'Status invalido']);
            return;
        }

        try {
            $sql = 'INSERT INTO tipos_atendimentos(nome, descricao, status)
                    VALUES (:nome, :descricao, :status)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            http_response_code(201);
            echo json_encode(['mensagem' => 'Tipo de Atendimento cadastrado com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar Tipo de Atendimento']);

        }
    }

     public function listarTipoAtendimento(): void
    {
        $sql = 'SELECT id, nome, descricao, status
                FROM tipos_atendimentos
                ORDER BY nome';

        $stmt = $this->pdo->query($sql);
        $tipos_atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($tipos_atendimentos, JSON_UNESCAPED_UNICODE);
    }

    public function atualizarAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$id || $nome === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'ID e nome são obrigatórios.']);
            return;
        }
        
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status invalido.']);
            return;
        }

        try {
            $sql = 'UPDATE tipos_atendimentos
                    SET nome = :nome,
                        descricao = :descricao,
                        status = :status
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Tipo de atendimento atualizado com sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar tipo de atendimento.']);
        }
    }

    public function excluirAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido']);
            return;
        }

        try {
            $sql = 'DELETE FROM tipos_atendimentos WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Tipo de atendimento excluido com sucesso']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao deletar tipo de atendimento']);
        }
    }

    public function inativar(): void
    {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido']);
            return;
        }

        try {
            $sql = "UPDATE tipos_atendimentos SET status = 'inativo' WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Tipo de atendimento inativado com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao inativar Tipo de atendimento: ']);
        }
    }
}