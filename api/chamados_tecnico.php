<?php
ini_set('display_errors', 0);
ini_set('html_errors', 0);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (file_exists(__DIR__ . '/../database.php')) {
        require_once __DIR__ . '/../database.php';
    } else {
        throw new Exception("Arquivo database.php não encontrado.");
    }

    if (!isset($pdo) && isset($conn)) $pdo = $conn;
    if (!isset($pdo)) throw new Exception("Variável de conexão não encontrada.");

    // 🔴 TESTE: Buscando ABSOLUTAMENTE TODOS os chamados da tabela, sem filtrar por ID
    $sql = "SELECT id_chamado, descricao_problema, data_abertura, status, prioridade, id_ambiente 
            FROM chamados 
            ORDER BY data_abertura DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultado = [];
    foreach ($chamados as $c) {
        $resultado[] = [
            "id_chamado" => $c["id_chamado"],
            "descricao_problema" => $c["descricao_problema"],
            "data_abertura" => $c["data_abertura"],
            "status" => $c["status"],
            "prioridade" => $c["prioridade"],
            "bloco_nome" => "Setor Geral", 
            "ambiente_nome" => "Ambiente ID: " . $c["id_ambiente"]
        ];
    }

    if (ob_get_length()) ob_clean();
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if (ob_get_length()) ob_clean();
    echo json_encode([]);
}