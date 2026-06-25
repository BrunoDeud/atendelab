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
            http_response_code(400);
            echo json_encode(['erro' => 'Nome do atendimento é obrigatorio']);
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
            $this->jsonResponse(['erro' => 'Erro ao cadastrar Tipo de Atendimento'], 500);

        }
    }

     public function listarTipoAtendimento(): void
    {
        $sql = 'SELECT id, nome, descricao, status
                FROM tipos_atendimentos
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $tipos_atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->jsonResponse($tipos_atendimentos);
    }

    public function atualizarAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'ativo';

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID é obrigatorio.'], 400);
        }
        if (!in_array($status, ['ativo', 'inativo'], true)) {
            $this->jsonResponse(['erro' => 'Status invalido.'], 400);
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

            $this->jsonResponse(['mensagem' => 'Tipo de atendimento atualizado com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao atualizar tipo de atendimento.'], 500);
        }
    }

    public function excluirAtendimento(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['erro' => 'ID inválido'], 400);
        }

        try {
            $sql = 'DELETE FROM tipos_atendimentos WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->jsonResponse(['mensagem' => 'Tipo de atendimento excluido com sucesso']);
        } catch (PDOException $e) {
            $this->jsonResponse(['erro' => 'Erro ao deletar tipo de atendimento'], 500);
        }
    }
}