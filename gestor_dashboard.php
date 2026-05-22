<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>dashboard gestor</title>

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
    color:#999;
    margin:0;
}

/* CARDS */
.dashboard-card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    border-left:5px solid #267899;
    transition:.3s;
    height:100%;
}

.dashboard-card:hover{
    transform:translateY(-4px);
}

.dashboard-card h6{
    color:#777;
}

.dashboard-card h2{
    color:#267899;
    margin:0;
    font-weight:bold;
}

.green{
    border-left-color:#50C878;
}

.yellow{
    border-left-color:#FBD040;
}

.red{
    border-left-color:#F16548;
}

.activity-item{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid #eee;
}

.activity-item:last-child{
    border:none;
}

.icon-box{
    font-size:2rem;
    color:#267899;
}

.perfil-card{
    cursor:pointer;
}

.perfil-card:hover{
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 8px 20px rgba(38,120,153,0.12);
}
</style>
</head>

<body>

<div class="sidebar" id="sidebar">

    <div class="logo">
        <i class="bi bi-clipboard2-check-fill"></i>
        <span class="logo-text">SGM</span>
        <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
    </div>

    <div class="menu">

         <a class="active">
        <i class="bi bi-house"></i>
        <span class="menu-text">Dashboard</span>
    </a>

    <a href="gestor_chamados.php">
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

    <a href="./ambientes_gestor.php">
        <i class="bi bi-geo-alt"></i>
        <span class="menu-text">Ambientes</span>
    </a>

    <a href="usuarios.php">
    <i class="bi bi-people-fill"></i>
    <span class="menu-text">Usuários</span>
</a>

    <!-- 👇 NOVO ITEM ADICIONADO -->
    <a href="gestor_perfil.php">
        <i class="bi bi-person-circle"></i>
        <span class="menu-text">Perfil</span>
    </a>

    <a href="logout.php">
        <i class="bi bi-box-arrow-right"></i>
        <span class="menu-text">Sair</span>
    </a>
    
    </div>
</div>

<div class="main" id="main">

    <div class="topbar">
        <h3>Minhas solicitações</h3>
        <p>Olá, <span id="perfil_usuario_topo">...</span></p>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="dashboard-card green">
                <h6>Novas Solicitações</h6>
                <h2 id="abertos">0</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card yellow">
                <h6>Em atendimento</h6>
                <h2 id="em_execucao">0</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card red">
                <h6>Críticos / Urgentes</h6>
                <h2 id="urgentes">0</h2>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-1">

        <div class="col-lg-8">
            <div class="dashboard-card">
                <h5 class="mb-4">
                    <i class="bi bi-clock-history"></i>
                    Atividade recente
                </h5>

                <div id="atividade_container"></div>
            </div>
        </div>

<div class="col-lg-4">
    <a href="gestor_perfil.php" class="text-decoration-none text-dark">
        <div class="dashboard-card perfil-card">
            <h5 class="mb-4">
                <i class="bi bi-person-circle"></i>
                Perfil
            </h5>

            <p><strong>Usuário:</strong> <span id="perfil_usuario">...</span></p>
            <p><strong>Nível:</strong> <span id="perfil_nivel">...</span></p>
        </div>
    </a>
</div>  

    <div class="row g-4 mt-1">

        <div class="col-md-6">
            <div class="dashboard-card text-center">
                <div class="icon-box">
                    <i class="bi bi-bell"></i>
                </div>

                <h5 class="mt-3">Notificações</h5>
                <p class="text-muted">
                    <span id="notificacoes">0</span> pendências urgentes
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="dashboard-card text-center">
                <div class="icon-box">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <h5 class="mt-3">Desempenho</h5>
                <p class="text-muted">
                    <span id="desempenho">0</span> chamados resolvidos este mês
                </p>
            </div>
        </div>

    </div>

</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

fetch('api/dashboard_gestor.php')
.then(response => response.json())
.then(data => {

    /* RESUMO */
    document.getElementById('abertos').textContent =
        data.resumo.abertos;

    document.getElementById('em_execucao').textContent =
        data.resumo.em_execucao;

    document.getElementById('urgentes').textContent =
        data.resumo.urgentes;

    /* PERFIL */
    document.getElementById('perfil_usuario').textContent =
        data.perfil.usuario;

    document.getElementById('perfil_usuario_topo').textContent =
        data.perfil.usuario;

    document.getElementById('perfil_nivel').textContent =
        data.perfil.nivel;

    /* NOTIFICAÇÕES */
    document.getElementById('notificacoes').textContent =
        data.resumo.urgentes;

    /* DESEMPENHO */
    document.getElementById('desempenho').textContent =
        data.desempenho.concluidos;

    /* ATIVIDADE RECENTE */
    const container =
        document.getElementById('atividade_container');

    container.innerHTML = '';

data.atividade.forEach(item => {
    container.innerHTML += `
        <div class="activity-item">
            <span>Chamado #${item.id_chamado} - ${item.descricao_problema}</span>
            <small class="text-muted">
                ${item.data_abertura}
            </small>
        </div>
    `;
});

})
.catch(error => {
    console.error('Erro ao carregar dashboard:', error);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>