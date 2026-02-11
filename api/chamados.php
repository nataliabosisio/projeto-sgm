<?php

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor'){
     echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
 }

$sql =  "select id_chamado, usuarios.nome, ambientes.nome, chamados.descricao_problema, data_abertura, status from chamados
        inner join usuarios on id_usuario = id_solicitante 
        join ambientes on chamados.id_ambiente = ambientes.id_ambiente;";
        
$res = $conn->query($sql);
$dados = $res->fetch_all(MYSQLI_ASSOC);
echo json_encode($dados);

?>