<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGM - Gestão de Chamados</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
    color:#999;
    margin:0;
}

.dashboard-card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
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

        <a href="gestor_dashboard.php">
            <i class="bi bi-house"></i>
            <span class="menu-text">Dashboard</span>
        </a>

        <a class="active">
            <i class="bi bi-list-ul"></i>
            <span class="menu-text">Chamados</span>
        </a>

        <a href="gestor_blocos.php">
            <i class="bi bi-intersect"></i>
            <span class="menu-text">Blocos</span>
        </a>

        <a href="gestor_servicos.php">
            <i class="bi bi-card-heading"></i>
            <span class="menu-text">Serviços</span>
        </a>

        <a href="ambientes_gestor.php">
            <i class="bi bi-geo-alt"></i>
            <span class="menu-text">Ambientes</span>
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Sair</span>
        </a>

    </div>
</div>

<!-- CONTEÚDO -->
<div class="main" id="main">

    <div class="topbar">
        <h3>Chamados</h3>
        <p>Gerencie todos os chamados do sistema</p>
    </div>

    <!-- FILTROS -->
    <div class="dashboard-card mb-4">
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-secondary" onclick="carregarChamados('')">
                Todos
            </button>

            <button class="btn btn-sm btn-outline-primary" onclick="carregarChamados('aberto')">
                Abertos
            </button>

            <button class="btn btn-sm btn-outline-warning" onclick="carregarChamados('em_execucao')">
                Em Execução
            </button>

            <button class="btn btn-sm btn-outline-success" onclick="carregarChamados('concluido')">
                Concluídos
            </button>
        </div>
    </div>

    <!-- TABELA -->
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Local / Tipo</th>
                        <th>Prioridade</th>
                        <th>Técnico</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody id="tabelaGeral">
                    <tr>
                        <td colspan="7" class="text-center">
                            Carregando chamados...
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

const coresPrioridade = {
    urgente: 'text-danger',
    alta: 'text-warning',
    media: 'text-primary',
    baixa: 'text-secondary'
};

const coresStatus = {
    aberto: 'bg-secondary',
    em_execucao: 'bg-warning',
    concluido: 'bg-success',
    fechado: 'bg-dark'
};

async function carregarChamados(status = '') {
    const tbody = document.getElementById('tabelaGeral');

    try {
        const url = `api/gestor_chamados.php?status=${status}`;
        const res = await fetch(url);

        if (!res.ok) {
            throw new Error('Erro ao buscar dados');
        }

        const chamados = await res.json();

        if (chamados.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="7" class="text-center">Nenhum chamado encontrado.</td></tr>';
            return;
        }

        tbody.innerHTML = chamados.map(c => `
            <tr>
                <td>#${c.id_chamado}</td>

                <td>
                    ${c.solicitante_nome || 'N/A'}
                </td>

                <td>
                    <small class="text-muted">
                        ${c.bloco_nome || ''}
                    </small><br>

                    <strong>
                        ${c.ambiente_nome || 'Não definido'}
                    </strong>
                </td>

                <td>
                    <i class="bi bi-circle-fill ${coresPrioridade[c.prioridade] || 'text-muted'} me-1"></i>
                    ${(c.prioridade || 'baixa').toUpperCase()}
                </td>

                <td>
                    ${c.tecnico_nome || '<em class="text-muted">Não atribuído</em>'}
                </td>

                <td>
                    <span class="badge ${coresStatus[c.status] || 'bg-secondary'}">
                        ${(c.status || 'aberto').replace('_', ' ').toUpperCase()}
                    </span>
                </td>

                <td>
                    <a href="gestor_detalhes.php?id=${c.id_chamado}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                        Gerenciar
                    </a>
                </td>
            </tr>
        `).join('');

    } catch (error) {
        console.error(error);

        tbody.innerHTML =
            '<tr><td colspan="7" class="text-danger text-center">Erro ao carregar chamados.</td></tr>';
    }
}

window.onload = () => carregarChamados('');
</script>

</body>
</html>