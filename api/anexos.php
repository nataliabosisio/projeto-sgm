<?php

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

// pega o ID do chamado
$id = isset($_GET['id_chamado']) ? (int) $_GET['id_chamado'] : 0;

if ($id <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id_anexo, caminho_arquivo, tipo_anexo 
        FROM chamados_anexos 
        WHERE id_chamado = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$dados = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($dados);