<?php

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

// CORREÇÃO 1: Permite que técnicos E gestores acessem as fotos
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_perfil'], ['gestor', 'tecnico'])) {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

// Pega o ID do chamado
$id = isset($_GET['id_chamado']) ? (int) $_GET['id_chamado'] : 0;

if ($id <= 0) {
    echo json_encode([]);
    exit;
}

// CORREÇÃO 2: Captura o tipo de anexo se ele for enviado na URL (ex: &tipo=conclusao)
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

if (!empty($tipo)) {
    // Se passou o tipo, filtra por chamado E por tipo
    $sql = "SELECT id_anexo, caminho_arquivo, tipo_anexo 
            FROM chamados_anexos 
            WHERE id_chamado = ? AND tipo_anexo = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $tipo);
} else {
    // Se não passou o tipo, traz todos como já fazia antes
    $sql = "SELECT id_anexo, caminho_arquivo, tipo_anexo 
            FROM chamados_anexos 
            WHERE id_chamado = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
}

$stmt->execute();
$result = $stmt->get_result();
$dados = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($dados);