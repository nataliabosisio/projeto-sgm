<?php

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor'){
    echo json_encode([
        "success" => false,
        "message" => "Acesso negado."
    ]);
    exit;
}

/* RESUMO */
$sql = "SELECT
    SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) as abertos,
    SUM(CASE WHEN status = 'em_execucao' THEN 1 ELSE 0 END) as em_execucao,
    SUM(CASE WHEN prioridade = 'urgente' AND status != 'fechado' THEN 1 ELSE 0 END) as urgentes,
    COUNT(*) as total
FROM chamados";

$res = $conn->query($sql);
$dados = $res->fetch_assoc();


$sqlAtividade = "
    SELECT id_chamado, descricao_problema, data_abertura
    FROM chamados
    WHERE status != 'fechado'
    ORDER BY data_abertura DESC
    LIMIT 3
";

$resAtividade = $conn->query($sqlAtividade);

$atividades = [];

while($row = $resAtividade->fetch_assoc()){
    $atividades[] = $row;
}


/* PERFIL */
$perfil = [
    "usuario" => $_SESSION['user_nome'],
    "nivel" => $_SESSION['user_perfil']
];


/* DESEMPENHO */
$sqlDesempenho = "
    SELECT COUNT(*) as concluidos
    FROM chamados
    WHERE status='fechado'
    AND MONTH(data_fechamento)=MONTH(CURRENT_DATE())
";

$resDesempenho = $conn->query($sqlDesempenho);
$desempenho = $resDesempenho->fetch_assoc();


echo json_encode([
    "resumo" => $dados,
    "atividade" => $atividades,
    "perfil" => $perfil,
    "desempenho" => $desempenho
]);

?>