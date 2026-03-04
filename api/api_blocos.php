<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}
$method = $_SERVER['REQUEST_METHOD'];

switch ($method){
    case 'GET':
        $sql = "SELECT id_bloco, nome, descricao from blocos order by nome asc";

        $result = $conn -> query ($sql);
        $blocos = [];

        if ($result){
            while ($row = $result ->fetch_assoc()){
                $blocos[] = $row;
            }
        }
        echo json_encode(["success" => true, "data" => $blocos]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if(!isset($data->nome) || !isset($data->descricao)){
            echo json_encode(["success" => false, "message" => "Dados incompletos. Informe nome e descricao."]);
            exit;
            }

        $nome = $conn->real_escape_string(trim($data->nome));
        $descricao = $data->descricao;

        $sql = "INSERT INTO blocos (nome, descricao) VALUES ('$nome', '$descricao')";

        if($conn->query($sql) === TRUE){
            echo json_encode(["success" => true, "message" => "Bloco criado com sucesso!", "id_bloco" => $conn->insert_id]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao criar bloco: " . $conn->error]);
            }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if(!isset($data->id_bloco) || !isset($data->nome) || !isset($data->descricao)){
            echo json_encode(["sucess" => false, "message" => "Dados incompletos para atualização."]);
            exit;
            }

        $id_bloco = (int)$data->id_bloco;
        $nome = $conn->real_escape_string(trim($data->nome));
        $descricao = $conn->real_escape_string(trim($data->descricao));

        $sql = "UPDATE ambientes SET nome = '$nome', id_bloco = $id_bloco WHERE id_ambiente = $id_ambiente";

        if ($conn->query($sql)=== TRUE) {
            echo json_encode(["sucess" => true, "message" => "Ambiente atualizado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao atualizar: " . $conn->error]);
            }
        break;
    }