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

        $sql = 'SELECT a.id, p.nome AS pessoa_nome,
                    t.nome AS tipo_nome,
                    u.nome AS responsavel_nome,
                    a.descricao, a.status,
                    a.data_atendimento, a.horario_atendimento,
                    a.observacao_final
                FROM atendimentos a
                INNER JOIN pessoas p ON a.id = a.pessoas_id
                INNER JOIN tipos_atendimento t 
                    ON t.id = a.tipo_atendimento_id
                INNER JOIN usuarios u ON u.id = a.usuario_id
                ORDER BY a.id DESC';

        $stmt = $this->pdo->query($sql);
        $atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($atendimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

        public function criarNovoAtendimento(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id = filter_var($_POST['pessoa_id'] ?? null, FILTER_VALIDATE_INT);
        $tipoId = filter_var($_POST['tipo_atendimento_id'] ?? null, FILTER_VALIDATE_INT);
        $usuarioId = filter_var($_POST['usuario_id'] ?? null, FILTER_VALIDATE_INT);
        $data_atendimento = filter_input(INPUT_POST, 'data_atendimento', FILTER_SANITIZE_STRING);
        $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING));
        $horario = $_POST['horario_atendimento'] ?? null;
        $status = $_POST['status'] ?? 'aberto';

        if (!$pessoa_id || !$tipoId || !$usuarioId || !$data_atendimento || !$descricao || !$horario) {
            http_response_code(422);
            echo json_encode(['erro' => 'Preencha todos os campos obrigatórios.']);
            return;
        }

        $sql = 'INSERT INTO atendimentos
                (pessoa_id, tipo_atendimento_id, usuario_id, data_atendimento, descricao, horario_atendimento, status)
                VALUES (:pessoa_id, :tipo_atendimento_id, :usuario_id, :data_atendimento, :descricao, :horario_atendimento, :status)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
        $stmt->bindParam(':tipo_atendimento_id', $tipoId, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(':data_atendimento', $data_atendimento, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':horario_atendimento', $horario, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

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
        $tipo_id = filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $data_atendimento = filter_input(INPUT_POST, 'data_atendimento', FILTER_SANITIZE_STRING);
        $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING));
        $horario = $_POST['horario_atendimento'] ?? null;
        $status = $_POST['status'] ?? 'aberto';

        if (!$id || !$pessoa_id || !$tipo_id || !$usuario_id || !$data_atendimento || !$descricao || !$horario) {
            http_response_code(422);
            echo json_encode(['erro' => 'Preencha todos os campos obrigatórios.']);
            return;
        }

        $sql = 'UPDATE atendimentos
                SET pessoa_id = :pessoa_id, tipo_atendimento_id = :tipo_id, usuario_id = :usuario_id, data_atendimento = :data_atendimento, descricao = :descricao, horario_atendimento = :horario_atendimento, status = :status
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
        $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':data_atendimento', $data_atendimento, PDO::PARAM_STR);
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

        $sql = 'SELECT a.*, p.nome AS pessoa_nome,
                    t.nome AS tipo_nome,
                    u.nome AS responsavel_nome
                FROM atendimentos a
                INNER JOIN pessoas p ON p.id = a.pessoa_id
                INNER JOIN tipos_atendimento t ON t.id = a.tipo_atendimento_id
                INNER JOIN usuarios u ON u.id = a.usuario_id
                WHERE a.id = :id';

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

     public function alterarStatus(): void
    {

        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $observacao_final = filter_input(INPUT_POST, 'observacao_final', FILTER_SANITIZE_STRING);

        if (!$id || !in_array($status, ['aberto', 'em_andamento', 'concluido'], true)) {
            http_response_code(422);
            echo json_encode(['erro' => 'Id inválido ou status inválido.']);
            return;
        }

        $sql = 'UPDATE atendimentos SET status = :status, observacao_final = :observacao_final WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['mensagem' => 'Status do atendimento atualizado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar status do atendimento.']);
        }
    }
}