<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'solicitante') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>SGM - Meus Chamados</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>
body{
    margin:0;
    background:#f5f7fb;
    font-family:Arial,sans-serif;
}

/* SIDEBAR */
.sidebar{
    position:fixed;
    top:0;
    left:0;
    width:250px;
    height:100vh;
    background:white;
    border-right:1px solid #eee;
    transition:.3s;
    overflow:hidden;
    box-shadow:4px 0 15px rgba(0,0,0,.05);
    z-index:1000;
}

.sidebar.collapsed{
    width:80px;
}

.logo{
    padding:25px;
    font-size:1.3rem;
    color:#267899;
    font-weight:bold;
    border-bottom:1px solid #f0f0f0;
    white-space:nowrap;
}

.logo-text{
    transition:.3s;
}

.sidebar.collapsed .logo-text{
    display:none;
}

.toggle-btn{
    position:absolute;
    right:15px;
    top:25px;
    cursor:pointer;
    color:#267899;
    font-size:1.2rem;
}

.menu{
    padding:20px 10px;
}

.menu a{
    display:flex;
    align-items:center;
    gap:15px;
    padding:14px 18px;
    text-decoration:none;
    color:#555;
    border-radius:12px;
    margin-bottom:8px;
    transition:.3s;
    white-space:nowrap;
}

.menu a:hover{
    background:#eef7fb;
    color:#267899;
}

.menu a.active{
    background:#267899;
    color:white;
}

.menu-text{
    transition:.3s;
}

.sidebar.collapsed .menu-text{
    display:none;
}

/* MAIN */
.main{
    margin-left:250px;
    padding:35px;
    transition:.3s;
}

.main.expanded{
    margin-left:80px;
}

.topbar{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    margin-bottom:25px;
}

.topbar h3{
    color:#267899;
    margin:0;
}

.topbar p{
    color:#888;
    margin:0;
}

/* CARD */
.dashboard-card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

/* TABLE */
.table th{
    color:#267899;
    border-top:none;
}

.table td{
    vertical-align:middle;
}

.badge{
    padding:8px 12px;
    border-radius:10px;
}

.mini-thumb{
    width:45px;
    height:45px;
    object-fit:cover;
    border-radius:8px;
    cursor:pointer;
    border:1px solid #ddd;
}

.btn-primary-custom{
    background:#267899;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:12px;
    text-decoration:none;
}

.btn-primary-custom:hover{
    background:#1f6682;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="logo">
        <i class="bi bi-clipboard2-check-fill"></i>
        <span class="logo-text">SGM</span>
        <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
    </div>

    <div class="menu">

        <a class="active">
            <i class="bi bi-list-ul"></i>
            <span class="menu-text">Meus Chamados</span>
        </a>

        <a href="solicitante_abrir_chamado.php">
            <i class="bi bi-plus-circle"></i>
            <span class="menu-text">Nova Solicitação</span>
        </a>

        <a href="perfil_solicitante.php">
            <i class="bi bi-person-circle"></i>
            <span class="menu-text">Meu Perfil</span>
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Sair</span>
        </a>

    </div>
</div>

<!-- MAIN -->
<div class="main" id="main">

    <div class="topbar">
        <h3>Minhas Solicitações</h3>
        <p>Olá, <?= $_SESSION['user_nome'] ?></p>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="solicitante_abrir_chamado.php" class="btn-primary-custom">
            + Nova Solicitação
        </a>
    </div>

    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Local</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody id="tabelaChamados"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL FOTO -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body p-0 text-center">
                <img src="" id="imgModal" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

function verFoto(url){
    document.getElementById('imgModal').src = url;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

async function carregarChamados(){

    const chamados =
        await (await fetch('api/chamados.php')).json();

    const lista =
        document.getElementById('tabelaChamados');

    const cores = {
        aberto:'bg-secondary',
        agendado:'bg-info',
        em_execucao:'bg-warning',
        concluido:'bg-success',
        fechado:'bg-dark'
    };

    lista.innerHTML = await Promise.all(
        chamados.map(async c => {

            const anexos =
                await (await fetch(
                    `api/anexos.php?id_chamado=${c.id_chamado}`
                )).json();

            const thumbHtml =
                anexos.length > 0
                ? `<img src="${anexos[0].caminho_arquivo}"
                    class="mini-thumb"
                    onclick="verFoto('${anexos[0].caminho_arquivo}')">`
                : `<i class="bi bi-image text-muted"></i>`;

            return `
                <tr>
                    <td>#${c.id_chamado}</td>
                    <td>${thumbHtml}</td>
                    <td>${c.bloco_nome} - ${c.ambiente_nome}</td>
                    <td>${c.descricao_problema.substring(0,30)}...</td>
                    <td>${new Date(c.data_abertura).toLocaleDateString()}</td>
                    <td>
                        <span class="badge ${cores[c.status]}">
                            ${c.status.toUpperCase()}
                        </span>
                    </td>
                </tr>
            `;
        })
    ).then(rows => rows.join(''));
}

carregarChamados();
</script>

</body>
</html>