<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Proteção
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode([
        "success" => false,
        "message" => "Acesso negado."
    ]);
    exit;
}

// filtro opcional
$status = isset($_GET['status'])
    ? $conn->real_escape_string($_GET['status'])
    : '';

$where = $status
    ? "WHERE c.status = '$status'"
    : '';


$sql = "
SELECT
    c.id_chamado,
    c.descricao_problema,
    c.status,
    c.prioridade,
    c.data_abertura,

    a.nome AS ambiente_nome,
    b.nome AS bloco_nome,

    u.nome AS solicitante_nome,
    t.nome AS tecnico_nome

FROM chamados c

LEFT JOIN ambientes a
    ON c.id_ambiente = a.id_ambiente

LEFT JOIN blocos b
    ON a.id_bloco = b.id_bloco

LEFT JOIN usuarios u
    ON c.id_solicitante = u.id_usuario

LEFT JOIN usuarios t
    ON c.id_tecnico = t.id_usuario

$where

ORDER BY
    CASE
        WHEN c.prioridade = 'urgente' THEN 1
        WHEN c.prioridade = 'alta' THEN 2
        WHEN c.prioridade = 'media' THEN 3
        ELSE 4
    END,
    c.data_abertura DESC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "erro_sql" => $conn->error
    ]);
    exit;
}

$chamados = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($chamados);