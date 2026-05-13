<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id_chamado = $data['id_chamado'] ?? null;
$id_tecnico = $data['id_tecnico'] ?? null;
$prioridade = $data['prioridade'] ?? null;
$data_prevista = $data['data_prevista'] ?? null;

if (!$id_chamado || !$id_tecnico) {
    echo json_encode(["success" => false, "message" => "Dados inválidos"]);
    exit;
}

$sql = "UPDATE chamados 
        SET id_tecnico = ?, prioridade = ?, data_previsao_conclusao = ?, status = 'em_execucao'
        WHERE id_chamado = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issi", $id_tecnico, $prioridade, $data_prevista, $id_chamado);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao atualizar"]);
}
?>