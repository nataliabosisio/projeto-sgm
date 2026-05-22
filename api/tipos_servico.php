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

        $sql = "SELECT id_tipo, nome, descricao
                FROM tipos_servico
                ORDER BY nome ASC";

        $result = $conn->query($sql);

        $tipos = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tipos[] = $row;
            }
        }

        echo json_encode([
            "success" => true,
            "data" => $tipos
        ]);
    break;


    // CRIAR
    case 'POST':

        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->nome)) {
            echo json_encode([
                "success" => false,
                "message" => "Informe o nome."
            ]);
            exit;
        }

        $nome = $conn->real_escape_string(trim($data->nome));

        $descricao = isset($data->descricao)
            ? $conn->real_escape_string(trim($data->descricao))
            : null;

        $sql = "INSERT INTO tipos_servico (nome, descricao)
                VALUES ('$nome', " . ($descricao ? "'$descricao'" : "NULL") . ")";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Tipo criado com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $conn->error
            ]);
        }
    break;


    // EDITAR
    case 'PUT':

        $data = json_decode(file_get_contents("php://input"));

        if (
            !isset($data->id_tipo) ||
            !isset($data->nome)
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Dados incompletos."
            ]);
            exit;
        }

        $id = (int)$data->id_tipo;
        $nome = $conn->real_escape_string(trim($data->nome));

        $descricao = isset($data->descricao)
            ? $conn->real_escape_string(trim($data->descricao))
            : null;

        $sql = "UPDATE tipos_servico
                SET nome='$nome',
                    descricao=" . ($descricao ? "'$descricao'" : "NULL") . "
                WHERE id_tipo=$id";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Atualizado com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $conn->error
            ]);
        }
    break;


// EXCLUIR
case 'DELETE':

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id_tipo)) {
        echo json_encode([
            "success" => false,
            "message" => "ID não informado."
        ]);
        exit;
    }

    $id = (int)$data->id_tipo;

    // verificar se existe chamado usando esse tipo
    $sqlVerifica = "
        SELECT COUNT(*) AS total
        FROM chamados
        WHERE id_tipo_servico = $id
    ";

    $resultado = $conn->query($sqlVerifica);
    $row = $resultado->fetch_assoc();

    if ($row['total'] > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Não é possível excluir. Existem chamados vinculados a esse serviço."
        ]);
        exit;
    }

    // excluir se não houver vínculo
    $sql = "
        DELETE FROM tipos_servico
        WHERE id_tipo = $id
    ";

    if ($conn->query($sql)) {
        echo json_encode([
            "success" => true,
            "message" => "Excluído com sucesso!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => $conn->error
        ]);
    }

break;

break;
}
?>