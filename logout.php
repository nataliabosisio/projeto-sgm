

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$voltar = "login.php";

if ($_SESSION['user_perfil'] === 'gestor') {
    $voltar = "gestor_dashboard.php";
}

if ($_SESSION['user_perfil'] === 'solicitante') {
    $voltar = "solicitante_dashboard.php";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Confirmar saída</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f7fb;
    font-family:Arial,sans-serif;
}

.logout-card{
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
    text-align:center;
    width:400px;
}

h3{
    color:#267899;
    margin-bottom:20px;
}

.btn-confirmar{
    background:#267899;
    color:white;
    border:none;
}

.btn-confirmar:hover{
    background:#1f6783;
    color:white;
}
</style>
</head>
<body>

<div class="logout-card">
    <h3>Deseja realmente sair?</h3>
    <p class="text-muted mb-4">
        Sua sessão será encerrada.
    </p>

    <div class="d-flex gap-3 justify-content-center">
        <a href="api/logout.php" class="btn btn-confirmar px-4">
            Sim, sair
        </a>

<a href="<?= $voltar ?>" class="btn btn-outline-secondary px-4">
    Cancelar
</a>
    </div>
</div>

</div>
</body>
</html>