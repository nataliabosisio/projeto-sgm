<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

$acao = $_GET['acao'] ?? '';

switch($acao){

    // ================= BLOCO =================
    case 'listar_blocos':

        $sql = "SELECT id_bloco, nome
                FROM blocos
                ORDER BY nome";

        $result = $conn->query($sql);

        $dados = [];

        while($row = $result->fetch_assoc()){
            $dados[] = $row;
        }

        echo json_encode($dados);
        break;


    // ================= AMBIENTES =================
    case 'listar_ambientes':

        $id_bloco = intval($_GET['id_bloco'] ?? 0);

        $sql = "SELECT id_ambiente, nome
                FROM ambientes
                WHERE id_bloco = $id_bloco
                ORDER BY nome";

        $result = $conn->query($sql);

        $dados = [];

        while($row = $result->fetch_assoc()){
            $dados[] = $row;
        }

        echo json_encode($dados);
        break;


    // ================= TIPOS DE SERVIÇO =================
    case 'listar_tipos':

        $sql = "SELECT id_tipo, nome
                FROM tipos_servico
                ORDER BY nome";

        $result = $conn->query($sql);

        $dados = [];

        while($row = $result->fetch_assoc()){
            $dados[] = $row;
        }

        echo json_encode($dados);
        break;


    default:
        echo json_encode([
            "erro" => "Ação inválida"
        ]);
}
?>