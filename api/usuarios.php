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

    // ================= LISTAR =================
    case 'GET':

        $sql = "SELECT
                    id_usuario,
                    nome,
                    email,
                    perfil,
                    ativo,
                    data_criacao
                FROM usuarios
                ORDER BY nome ASC";

        $result = $conn->query($sql);

        $usuarios = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        echo json_encode([
            "success" => true,
            "data" => $usuarios
        ]);

    break;


    // ================= CRIAR =================
    case 'POST':

        $data = json_decode(file_get_contents("php://input"));

        if (
            !isset($data->nome) ||
            !isset($data->email) ||
            !isset($data->senha) ||
            !isset($data->perfil)
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Preencha todos os campos."
            ]);
            exit;
        }

        $nome = $conn->real_escape_string(trim($data->nome));
        $email = $conn->real_escape_string(trim($data->email));
        $perfil = $conn->real_escape_string(trim($data->perfil));

        $senha_hash = password_hash(
            $data->senha,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO usuarios
                (nome, email, senha_hash, perfil, ativo, data_criacao)
                VALUES
                (
                    '$nome',
                    '$email',
                    '$senha_hash',
                    '$perfil',
                    1,
                    NOW()
                )";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Usuário criado com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro: " . $conn->error
            ]);
        }

    break;


    // ================= EDITAR =================
    case 'PUT':

        $data = json_decode(file_get_contents("php://input"));

        if (
            !isset($data->id_usuario) ||
            !isset($data->nome) ||
            !isset($data->email) ||
            !isset($data->perfil)
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Dados incompletos."
            ]);
            exit;
        }

        $id = (int)$data->id_usuario;
        $nome = $conn->real_escape_string(trim($data->nome));
        $email = $conn->real_escape_string(trim($data->email));
        $perfil = $conn->real_escape_string(trim($data->perfil));

        $sql = "UPDATE usuarios
                SET
                    nome='$nome',
                    email='$email',
                    perfil='$perfil'
                WHERE id_usuario=$id";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Atualizado com sucesso!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro: " . $conn->error
            ]);
        }

    break;


    // ================= EXCLUIR (DESATIVAR) =================
    case 'DELETE':

        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id_usuario)) {
            echo json_encode([
                "success" => false,
                "message" => "ID não informado."
            ]);
            exit;
        }

        $id = (int)$data->id_usuario;

        // não apaga do banco, só desativa
        $sql = "UPDATE usuarios
                SET ativo = 0
                WHERE id_usuario = $id";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true,
                "message" => "Usuário desativado!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Erro ao excluir."
            ]);
        }

    break;
}
?>