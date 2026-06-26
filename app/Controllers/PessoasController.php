<?php

class PessoasController
{
    private PDO $pdo;

        public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

        public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

       $sql = 'SELECT id, nome, documento, telefone, email,
                curso, periodo, status, observacoes
                FROM pessoas
                ORDER BY nome';

        $stmt = $this->pdo->query($sql);
        $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($pessoas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

        public function buscarPorId(): void
    {
        header ('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID invalido.']);
            return;
        }

        $sql = 'SELECT id, nome, documento, telefone, email,
                curso, periodo, status, observacoes
                FROM pessoas
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $pessoas = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pessoa) {
            http_response_code(404);
            echo json_encode(['erro' => 'Pessoa não encontrada']);
            return;
        }

        echo json_encode($pessoa, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

        public function cadastrar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = $_POST['status'] ?? 'ativo';
        $observacoes = trim($_POST['observacoes'] ?? '');

        if ($nome === '' || $documento === '' || $curso === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Nome, documento e curso são obrigatorios.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Email inválido.']);
            return;
        }
        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $documento)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Documento inválido.']);
            return;
        }

        if (!preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $telefone)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Telefone inválido.']);
            return;
        }
        
        if (!in_array($periodo, ['manhã', 'tarde', 'noite'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Período inválido.']);
            return;
        }

        if(!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status inválido.']);
            return;
        }

        try{
            $sql = 'INSERT INTO pessoas
                    (nome, documento, telefone, email, curso, periodo,
                      status, observacoes)
                    VALUES
                    (:nome, :documento, :telefone, :email, :curso,
                      :periodo, :status, :observacoes)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':observacoes', $observacoes);
            $stmt->execute();

            http_response_code(201);

            echo json_encode([
                'mensagem' => 'Pessoa cadastrada com sucesso.',
                'id' => $this->pdo->lastInsertId()
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar pessoa: ']);
        }
    }

        public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $curso = trim($_POST['curso'] ?? '');
        $periodo = trim($_POST['periodo'] ?? '');
        $status = $_POST['status'] ?? 'ativo';
        $observacoes = trim($_POST['observacoes'] ?? '');

        if (!$id || $nome === '' || $documento === '' || $email === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'ID, nome e documento são obrigatórios.']);
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Email inválido.']);
            return;
        }

        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $documento)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Documento inválido.']);
            return;
        }

        if (!preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $telefone)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Telefone inválido.']);
            return;
        }
        
        if (!in_array($periodo, ['manhã', 'tarde', 'noite'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Período inválido.']);
            return;
        }

        if(!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status inválido.']);
            return;
        }

        try{
            $sql = 'UPDATE pessoas
                    SET nome = :nome,
                        documento = :documento,
                        telefone = :telefone,
                        email = :email,
                        curso = :curso,
                        periodo = :periodo,
                        status = :status,
                        observacoes = :observacoes
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':observacoes', $observacoes);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            echo json_encode(['mensagem' => 'Pessoa atualizada com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar pessoa: ']);
        }
    }
        public function excluir(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        try{
            $sql = 'DELETE FROM pessoas WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Pessoa excluida com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao excluir pessoa: ']);
        }
    }

        public function inativar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        try{
            $sql = 'UPDATE pessoas SET status = "inativo" WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Pessoa inativada com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao inativar pessoa: ']);
        }
    }
} 