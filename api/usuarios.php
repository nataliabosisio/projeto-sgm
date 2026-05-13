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

$sql = "SELECT id_usuario, nome 
        FROM usuarios 
        WHERE perfil = 'tecnico' 
        AND ativo = 1 
        ORDER BY nome ASC";

$result = $conn->query($sql);

$tecnicos = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tecnicos[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "data" => $tecnicos
]);