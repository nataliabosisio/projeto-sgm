<?php
ini_set('display_errors', 0);
ini_set('html_errors', 0);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (file_exists(__DIR__ . '/../config/database.php')) {
        require_once __DIR__ . '/../config/database.php';
    } else {
        throw new Exception("Arquivo database.php não encontrado.");
    }

    if (!isset($conn)) {
        throw new Exception("Variável de conexão não encontrada.");
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
        throw new Exception("Acesso negado ou sessão inválida.");
    }

    $id_tecnico = (int)$_SESSION['user_id'];

    // Buscando os chamados do técnico logado
    $sql = "SELECT c.id_chamado, c.descricao_problema, c.data_abertura, c.status, c.prioridade, 
                   c.id_ambiente, a.nome as ambiente_nome, b.nome as bloco_nome, c.tempo_gasto_minutos
            FROM chamados c
            LEFT JOIN ambientes a ON c.id_ambiente = a.id_ambiente
            LEFT JOIN blocos b ON a.id_bloco = b.id_bloco
            WHERE c.id_tecnico = ?
            ORDER BY c.data_abertura DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id_tecnico);
    $stmt->execute();
    $result = $stmt->get_result();

    $resultado = [];
    while ($c = $result->fetch_assoc()) {
        $resultado[] = [
            "id_chamado" => $c["id_chamado"],
            "descricao_problema" => $c["descricao_problema"],
            "data_abertura" => $c["data_abertura"],
            "status" => $c["status"],
            "prioridade" => $c["prioridade"],
            "bloco_nome" => $c["bloco_nome"] ?? "Setor Geral", 
            "ambiente_nome" => $c["ambiente_nome"] ?? ("Ambiente ID: " . $c["id_ambiente"]),
            "tempo_gasto_minutos" => $c["tempo_gasto_minutos"]
        ];
    }

    if (ob_get_length()) ob_clean();
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if (ob_get_length()) ob_clean();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}