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
<title>Ambientes</title>

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

        <a class="active">
            <i class="bi bi-geo-alt"></i>
            <span class="menu-text">Ambientes</span>
        </a>

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


<!-- CONTEÚDO -->
<div class="main" id="main">

    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h3>Ambientes</h3>
            <p>Gerencie os ambientes cadastrados</p>
        </div>

        <button
            class="btn"
            style="background:#267899;color:white;"
            data-bs-toggle="modal"
            data-bs-target="#criar">

            <i class="bi bi-plus-lg"></i>
            Novo Ambiente
        </button>
    </div>




    <div class="dashboard-card">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0" id="tabelaGeral">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome do Ambiente</th>
                        <th>Nome do Bloco</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody id="tabelaBody"></tbody>
            </table>

        </div>
    </div>
</div>


<!-- MODAL -->
<div class="modal fade" id="criar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-plus-circle"></i>
                    Criar ambiente
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <form id="formChamado">

                    <div class="mb-3">
                        <label class="form-label">
                            Nome do ambiente
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nome_ambiente"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Bloco
                        </label>

                        <select
                            class="form-select"
                            id="selectBloco"
                            required>
                        </select>
                    </div>

                    <div class="modal-footer px-0">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                            Fechar
                        </button>

                        <button
                            type="submit"
                            class="btn"
                            style="background:#267899;color:white;">
                            Salvar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
const API = 'api/api_ambientes.php';

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

// ================= LISTAR =================
async function carregarAmbientes() {
    const body = document.getElementById('tabelaBody');

    try {
        const res = await fetch(API);
        const json = await res.json();

        if (!json.data || json.data.length === 0) {
            body.innerHTML =
                '<tr><td colspan="4" class="text-center">Nenhum ambiente encontrado.</td></tr>';
            return;
        }

        body.innerHTML = json.data.map(a => `
            <tr>
                <td>#${a.id_ambiente}</td>
                <td>${a.nome}</td>
                <td>${a.nome_bloco || 'Não definido'}</td>
                <td class="text-center">

                    <button class="btn btn-sm btn-outline-primary me-2"
                        onclick="abrirEdicao(${a.id_ambiente}, '${a.nome}', ${a.id_bloco})">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button class="btn btn-sm btn-outline-danger"
                        onclick="excluirAmbiente(${a.id_ambiente})">
                        <i class="bi bi-trash"></i>
                    </button>

                </td>
            </tr>
        `).join('');

    } catch (err) {
        console.error(err);
        body.innerHTML =
            '<tr><td colspan="4" class="text-danger text-center">Erro ao carregar</td></tr>';
    }
}

// ================= SALVAR =================
document.getElementById('formChamado').addEventListener('submit', async (e) => {
    e.preventDefault();

    const nome = nome_ambiente.value;
    const id_bloco = selectBloco.value;
    const id = e.target.dataset.id;

    const metodo = id ? 'PUT' : 'POST';

    const body = id
        ? { id_ambiente: id, nome, id_bloco }
        : { nome, id_bloco };

    try {
        const res = await fetch(API, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        });

        const data = await res.json();

        if (data.success) {
            alert(id ? 'Atualizado!' : 'Criado!');

            e.target.reset();
            delete e.target.dataset.id;

            bootstrap.Modal
                .getInstance(document.getElementById('criar'))
                .hide();

            carregarAmbientes();
        } else {
            alert(data.message);
        }

    } catch (err) {
        console.error(err);
        alert('Erro');
    }
});

// ================= EDITAR =================
function abrirEdicao(id, nome, id_bloco) {
    nome_ambiente.value = nome;
    selectBloco.value = id_bloco;

    const form = document.getElementById('formChamado');
    form.dataset.id = id;

    new bootstrap.Modal(
        document.getElementById('criar')
    ).show();
}

// ================= EXCLUIR =================
async function excluirAmbiente(id) {
    if (!confirm('Excluir ambiente?')) return;

    try {
        const res = await fetch(API, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_ambiente: id
            })
        });

        const data = await res.json();

        if (data.success) {
            carregarAmbientes();
        } else {
            alert(data.message);
        }

    } catch (err) {
        console.error(err);
        alert('Erro ao excluir');
    }
}

// ================= BLOCOS =================
async function carregarBlocos() {
    const select = document.getElementById('selectBloco');

    try {
        const res = await fetch('api/api_blocos.php');
        const json = await res.json();

        select.innerHTML =
            '<option disabled selected>Selecione...</option>';

        json.data.forEach(b => {
            select.innerHTML += `
                <option value="${b.id_bloco}">
                    ${b.nome}
                </option>
            `;
        });

    } catch (err) {
        console.error(err);
    }
}

// ================= INIT =================
window.onload = () => {
    carregarAmbientes();
    carregarBlocos();
};
</script>

</body>
</html>