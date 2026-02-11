<?php

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor'){
     echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
 }

$sql = "select id_anexo, caminho_arquivo, tipo_anexo from chamados_anexos;";
        
$res = $conn->query($sql);
$dados = $res->fetch_all(MYSQLI_ASSOC);
echo json_encode($dados);

?>