<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

/* =========================
   PROTEÇÃO DE ACESSO
========================= */
if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['user_perfil'] !== 'gestor'
) {
    echo json_encode([
        "success" => false,
        "message" => "Acesso negado."
    ]);
    exit;
}


/* =========================
   RECEBER DADOS
========================= */
$id_solicitante = $_SESSION['user_id'];

$id_ambiente = (int)($_POST['id_ambiente'] ?? 0);

$id_tipo = (int)($_POST['id_tipo'] ?? 0);

$descricao = trim($_POST['descricao'] ?? '');
$descricao = $conn->real_escape_string($descricao);


/* =========================
   VALIDAR CAMPOS
========================= */
if (
    !$id_ambiente ||
    !$id_tipo ||
    empty($descricao)
) {
    echo json_encode([
        "success" => false,
        "message" => "Preencha todos os campos obrigatórios."
    ]);
    exit;
}


/* =========================
   INSERIR CHAMADO
========================= */
$sql = "
    INSERT INTO chamados (
        descricao_problema,
        id_solicitante,
        id_ambiente,
        id_tipo_servico,
        status
    )
    VALUES (
        '$descricao',
        $id_solicitante,
        $id_ambiente,
        $id_tipo,
        'aberto'
    )
";

if (!$conn->query($sql)) {
    echo json_encode([
        "success" => false,
        "message" => "Erro no banco: " . $conn->error
    ]);
    exit;
}


/* =========================
   ID DO CHAMADO CRIADO
========================= */
$id_chamado = $conn->insert_id;

/* =========================
   UPLOAD DA FOTO (OPCIONAL)
========================= */
if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] === UPLOAD_ERR_OK
) {

    $tiposPermitidos = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    $extensao = strtolower(
        pathinfo(
            $_FILES['foto']['name'],
            PATHINFO_EXTENSION
        )
    );

    // validar extensão
    if (!in_array($extensao, $tiposPermitidos)) {
        echo json_encode([
            "success" => false,
            "message" => "Formato de imagem inválido."
        ]);
        exit;
    }

    $diretorio = "../assets/uploads/";

    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    $nome_arquivo =
        "abertura_" .
        uniqid() .
        "." .
        $extensao;

    $caminho_final =
        $diretorio .
        $nome_arquivo;

    if (
        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $caminho_final
        )
    ) {

        $caminho_db =
            "assets/uploads/" .
            $nome_arquivo;

        $sql_anexo = "
            INSERT INTO chamados_anexos (
                id_chamado,
                caminho_arquivo,
                tipo_anexo
            )
            VALUES (
                $id_chamado,
                '$caminho_db',
                'abertura'
            )
        ";

        $conn->query($sql_anexo);
    }
}

/* =========================
   RESPOSTA FINAL
========================= */
echo json_encode([
    "success" => true,
    "message" => "Chamado #$id_chamado aberto com sucesso!"
]);
?>