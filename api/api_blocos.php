<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode([
        "success" => false,
        "message" => "Acesso negado."
    ]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // LISTAR
    case 'GET':

        $sql = "SELECT id_bloco, nome, descricao 
                FROM blocos
                ORDER BY nome ASC";

        $result = $conn->query($sql);

        $blocos = [];

        while ($row = $result->fetch_assoc()) {
            $blocos[] = $row;
        }

        echo json_encode([
            "success" => true,
            "data" => $blocos
        ]);
        break;

    // CRIAR
    case 'POST':

        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->nome)) {
            echo json_encode([
                "success" => false,
                "message" => "Nome obrigatório"
            ]);
            exit;
        }

        $nome = $conn->real_escape_string($data->nome);
        $descricao = isset($data->descricao)
            ? $conn->real_escape_string($data->descricao)
            : null;

        $sql = "INSERT INTO blocos (nome, descricao)
                VALUES ('$nome', " . ($descricao ? "'$descricao'" : "NULL") . ")";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Bloco criado com sucesso"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $conn->error
            ]);
        }

        break;

    // ATUALIZAR
    case 'PUT':

        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id_bloco) || !isset($data->nome)) {
            echo json_encode([
                "success" => false,
                "message" => "Dados incompletos"
            ]);
            exit;
        }

        $id = (int)$data->id_bloco;
        $nome = $conn->real_escape_string($data->nome);
        $descricao = isset($data->descricao)
            ? $conn->real_escape_string($data->descricao)
            : null;

        $sql = "UPDATE blocos 
                SET nome='$nome', 
                    descricao=" . ($descricao ? "'$descricao'" : "NULL") . "
                WHERE id_bloco=$id";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Atualizado com sucesso"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $conn->error
            ]);
        }

        break;

// DELETE
case 'DELETE':

    try {

        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id_bloco)) {
            echo json_encode([
                "success" => false,
                "message" => "ID obrigatório"
            ]);
            exit;
        }

        $id = (int)$data->id_bloco;

        // verificar se existem ambientes ligados ao bloco
        $sqlCheck = "
            SELECT COUNT(*) AS total
            FROM ambientes
            WHERE id_bloco = $id
        ";

        $res = $conn->query($sqlCheck);
        $row = $res->fetch_assoc();

        if ($row['total'] > 0) {
            echo json_encode([
                "success" => false,
                "message" =>
                    "Não é possível excluir este bloco porque existem ambientes vinculados a ele."
            ]);
            exit;
        }

        // excluir bloco
        $sql = "DELETE FROM blocos WHERE id_bloco = $id";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Bloco excluído com sucesso"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $conn->error
            ]);
        }

    } catch (mysqli_sql_exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }

    break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Método inválido"
        ]);
        break;
}
?>