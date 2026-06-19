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

    // Inicia uma transação no banco
    $conn->begin_transaction();

    $solucao_escapada = $conn->real_escape_string($solucao_tecnica);

    // CORREÇÃO: Adicionado data_conclusao = NOW() para gravar a hora exata da finalização
    $sql = "UPDATE chamados 
            SET status = 'concluido', 
                solucao_tecnica = '$solucao_escapada',
                tempo_gasto_minutos = $tempo_gasto,
                data_conclusao = NOW() 
            WHERE id_chamado = $id_chamado";
    
    if (!$conn->query($sql)) {
        throw new Exception("Erro ao atualizar o chamado: " . $conn->error);
    }

    // ==========================================
    // LÓGICA DE UPLOAD DAS FOTOS DE CONCLUSÃO
    // ==========================================
    if (isset($_FILES['fotos_conclusao'])) {
        $arquivos = $_FILES['fotos_conclusao'];
        $total_arquivos = count($arquivos['name']);
        
        $diretorio_destino = "../uploads/";
        if (!is_dir($diretorio_destino)) {
            mkdir($diretorio_destino, 0755, true);
        }

        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        for ($i = 0; $i < $total_arquivos; $i++) {
            if ($arquivos['error'][$i] === UPLOAD_ERR_OK) {
                
                $nome_original = $arquivos['name'][$i];
                $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
                
                if (!in_array($extensao, $extensoes_permitidas)) {
                    throw new Exception("Extensão de arquivo não permitida para o arquivo: $nome_original");
                }

                $novo_nome = "concluido_" . $id_chamado . "_" . uniqid() . "." . $extensao;
                $caminho_completo = $diretorio_destino . $novo_nome;
                $caminho_banco = "uploads/" . $novo_nome;

                if (move_uploaded_file($arquivos['tmp_name'][$i], $caminho_completo)) {
                    $sql_anexo = "INSERT INTO chamados_anexos (id_chamado, caminho_arquivo, tipo_anexo) 
                                  VALUES (?, ?, 'conclusao')";
                    
                    $stmt_anexo = $conn->prepare($sql_anexo);
                    $stmt_anexo->bind_param("is", $id_chamado, $caminho_banco);
                    
                    if (!$stmt_anexo->execute()) {
                        throw new Exception("Erro ao salvar anexo no banco: " . $stmt_anexo->error);
                    }
                } else {
                    throw new Exception("Falha ao mover o arquivo enviado para o diretório de destino.");
                }
            } elseif ($arquivos['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                throw new Exception("Erro no upload do arquivo código: " . $arquivos['error'][$i]);
            }
        }
    }

    $conn->commit();

    echo json_encode([
        "sucesso" => true, 
        "mensagem" => "Chamado finalizado e fotos salvas com sucesso!"
    ]);
    exit;

} catch (Exception $e) {
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }

    http_response_code(500);
    echo json_encode([
        "sucesso" => false,
        "erro" => "Erro interno: " . $e->getMessage()
    ]);
    exit;
}