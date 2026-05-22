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
<title>Usuários</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>
body{
    margin:0;
    background:#f5f7fb;
    font-family:Arial,sans-serif;
}

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
    box-shadow:0 4px 15px rgba(0,0,0,.05);
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

        <a href="ambientes_gestor.php">
            <i class="bi bi-geo-alt"></i>
            <span class="menu-text">Ambientes</span>
        </a>

        <a class="active">
            <i class="bi bi-people-fill"></i>
            <span class="menu-text">Usuários</span>
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

<!-- MAIN -->
<div class="main" id="main">

    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h3>Usuários</h3>
            <p>Gerencie os usuários do sistema</p>
        </div>

        <button
            class="btn"
            style="background:#267899;color:white;"
            data-bs-toggle="modal"
            data-bs-target="#modalUsuario">

            <i class="bi bi-plus-lg"></i>
            Novo Usuário
        </button>
    </div>

    <div class="dashboard-card">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody id="tabelaUsuarios"></tbody>

            </table>

        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalUsuario" tabindex="-1">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content shadow">

<div class="modal-header bg-light">

    <h5 class="modal-title fw-semibold">
        <i class="bi bi-person-plus"></i>
        Usuário
    </h5>

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal">
    </button>

</div>

<div class="modal-body">

<form id="formUsuario">

    <div class="mb-3">
        <label class="form-label">Nome</label>

        <input
            type="text"
            class="form-control"
            id="nome"
            required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>

        <input
            type="email"
            class="form-control"
            id="email"
            required>
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>

        <input
            type="password"
            class="form-control"
            id="senha">
    </div>

    <div class="mb-3">
        <label class="form-label">Perfil</label>

        <select
            class="form-select"
            id="perfil"
            required>

            <option value="solicitante">Solicitante</option>
            <option value="tecnico">Técnico</option>
            <option value="gestor">Gestor</option>

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
const API = 'api/usuarios.php';

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

// ================= LISTAR =================
async function carregarUsuarios(){

    const tabela =
        document.getElementById('tabelaUsuarios');

    try{

        const res = await fetch(API);
        const json = await res.json();

        if(!json.data || json.data.length === 0){

            tabela.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">
                        Nenhum usuário encontrado.
                    </td>
                </tr>
            `;

            return;
        }

        tabela.innerHTML = json.data.map(u => `

            <tr>

                <td>#${u.id_usuario}</td>

                <td>${u.nome}</td>

                <td>${u.email}</td>

                <td>
                    <td>
    ${
        u.perfil === 'gestor'
        ? '<span class="badge bg-danger">Gestor</span>'

        : u.perfil === 'tecnico'
        ? '<span class="badge bg-warning text-dark">Técnico</span>'

        : '<span class="badge bg-info text-dark">Solicitante</span>'
    }
</td>
                </td>

                <td>
                    ${
                        u.ativo == 1
                        ? '<span class="badge bg-success">Ativo</span>'
                        : '<span class="badge bg-danger">Inativo</span>'
                    }
                </td>

                <td class="text-center">

                    <button
                        class="btn btn-sm btn-outline-primary me-2"
                        onclick="editarUsuario(
                            ${u.id_usuario},
                            '${u.nome}',
                            '${u.email}',
                            '${u.perfil}'
                        )">

                        <i class="bi bi-pencil"></i>
                    </button>

                    <button
                        class="btn btn-sm btn-outline-danger"
                        onclick="excluirUsuario(${u.id_usuario})">

                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>

        `).join('');

    }catch(err){
        console.error(err);
    }
}

// ================= SALVAR =================
document.getElementById('formUsuario').addEventListener('submit', async (e)=>{
    e.preventDefault();

    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const perfil = document.getElementById('perfil').value;

    const id = e.target.dataset.id;

    const metodo = id ? 'PUT' : 'POST';

    const body = id
        ? {
            id_usuario:id,
            nome,
            email,
            perfil
        }
        : {
            nome,
            email,
            senha,
            perfil
        };

    const res = await fetch(API,{
        method:metodo,
        headers:{
            'Content-Type':'application/json'
        },
        body:JSON.stringify(body)
    });

    const data = await res.json();

    if(data.success){

        alert('Salvo com sucesso!');

        e.target.reset();

        delete e.target.dataset.id;

        bootstrap.Modal
            .getInstance(
                document.getElementById('modalUsuario')
            )
            .hide();

        carregarUsuarios();

    }else{
        alert(data.message);
    }
});

// ================= EDITAR =================
function editarUsuario(id,nomeUsuario,emailUsuario,perfilUsuario){

    nome.value = nomeUsuario;
    email.value = emailUsuario;
    perfil.value = perfilUsuario;

    senha.value = '';

    const form =
        document.getElementById('formUsuario');

    form.dataset.id = id;

    new bootstrap.Modal(
        document.getElementById('modalUsuario')
    ).show();
}

// ================= EXCLUIR =================
async function excluirUsuario(id){

    if(!confirm('Deseja desativar este usuário?'))
        return;

    const res = await fetch(API,{
        method:'DELETE',
        headers:{
            'Content-Type':'application/json'
        },
        body:JSON.stringify({
            id_usuario:id
        })
    });

    const data = await res.json();

    if(data.success){
        carregarUsuarios();
    }else{
        alert(data.message);
    }
}

window.onload = carregarUsuarios;
</script>

</body>
</html>