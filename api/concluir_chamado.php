<?php
// api/concluir_chamado.php

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (!file_exists('../config/database.php')) {
        throw new Exception("Arquivo de configuracao do banco de dados nao encontrado.");
    }
    require_once '../config/database.php';

    if (!isset($conn)) {
        throw new Exception("A variavel de conexao com o banco (\$conn) nao foi detectada.");
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
        http_response_code(403);
        echo json_encode(["sucesso" => false, "erro" => "Sessao expirada ou acesso negado."]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["sucesso" => false, "erro" => "Metodo HTTP nao permitido."]);
        exit;
    }

    // Captura os dados enviados pelo FormData do Front-end
    $id_chamado      = isset($_POST['id_chamado']) ? (int)$_POST['id_chamado'] : 0;
    $solucao_raw     = isset($_POST['solucao_tecnica']) ? $_POST['solucao_tecnica'] : '';
    $solucao_tecnica = trim(strip_tags($solucao_raw));
    $tempo_gasto     = (!empty($_POST['tempo_gasto'])) ? (int)$_POST['tempo_gasto'] : 0;

    if ($id_chamado === 0) {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "erro" => "ID do chamado invalido."]);
        exit;
    }

    if ($solucao_tecnica === '') {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "erro" => "Por favor, preencha a solucao tecnica."]);
        exit;
    }

    $solucao_escapada = $conn->real_escape_string($solucao_tecnica);

    // CORREÇÃO: Atualizando a coluna exata do banco -> tempo_gasto_minutos
    $sql = "UPDATE chamados 
            SET status = 'concluido', 
                solucao_tecnica = '$solucao_escapada',
                tempo_gasto_minutos = $tempo_gasto 
            WHERE id_chamado = $id_chamado";
    
    if ($conn->query($sql)) {
        echo json_encode([
            "sucesso" => true, 
            "mensagem" => "Chamado finalizado com sucesso!"
        ]);
        exit;
    } else {
        throw new Exception("Erro ao executar comandos no banco: " . $conn->error);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "sucesso" => false,
        "erro" => "Erro interno: " . $e->getMessage()
    ]);
    exit;
}