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
<title>Blocos</title>

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

.sidebar.collapsed{ width:80px; }

.logo{
    padding:25px;
    font-size:1.3rem;
    color:#267899;
    font-weight:bold;
    border-bottom:1px solid #f0f0f0;
    white-space:nowrap;
}

.logo-text{ transition:.3s; }

.sidebar.collapsed .logo-text{ display:none; }

.toggle-btn{
    position:absolute;
    right:15px;
    top:25px;
    cursor:pointer;
    color:#267899;
    font-size:1.2rem;
}

.menu{ padding:20px 10px; }

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

.menu-text{ transition:.3s; }

.sidebar.collapsed .menu-text{ display:none; }

/* MAIN */
.main{
    margin-left:250px;
    padding:35px;
    transition:.3s;
}

.main.expanded{ margin-left:80px; }

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

        <a class="active">
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
            <h3>Blocos</h3>
            <p>Gerencie os blocos cadastrados no sistema</p>
        </div>

        <button class="btn" style="background-color:#267899;color:white;" 
                data-bs-toggle="modal" data-bs-target="#criar">
            + Novo Bloco
        </button>
    </div>

    <!-- TABELA -->
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome do Bloco</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody id="tabelaBlocos">
                    <tr>
                        <td colspan="4" class="text-center">
                            Carregando blocos...
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="criar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Criar Bloco</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nome do Bloco</label>
          <input type="text" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Descrição</label>
          <input type="text" class="form-control">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button class="btn" style="background-color:#267899;color:white;">Criar</button>
      </div>

    </div>
  </div>
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

async function carregarBlocos() {
    const tbody = document.getElementById('tabelaBlocos');

    try {
        const res = await fetch('api/api_blocos.php');
        const json = await res.json();

        const blocos = json.data || json;

        if (!blocos || blocos.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">
                        Nenhum bloco encontrado
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = blocos.map(b => `
            <tr>
                <td>#${b.id_bloco}</td>
                <td>${b.nome}</td>
                <td>${b.descricao || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-light">
                        <i class="bi bi-pencil"></i> Editar
                    </button>

                    <button class="btn btn-sm btn-light text-danger">
                        <i class="bi bi-trash3"></i>
                    </button>
                </td>
            </tr>
        `).join('');

    } catch (err) {
        console.error(err);

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-danger text-center">
                    Erro ao carregar blocos
                </td>
            </tr>
        `;
    }
}

window.onload = carregarBlocos;

document.querySelector("#criar .btn[style]").addEventListener("click", async () => {

    const nome = document.getElementById("nome_bloco").value;
    const descricao = document.getElementById("descricao_bloco").value;

    try {
        const res = await fetch("api/api_blocos.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                nome,
                descricao
            })
        });

        const data = await res.json();

        if (data.success) {
            alert("Bloco criado!");

            document.getElementById("nome_bloco").value = "";
            document.getElementById("descricao_bloco").value = "";

            bootstrap.Modal.getInstance(
                document.getElementById("criar")
            ).hide();

            carregarBlocos();
        } else {
            alert(data.message);
        }

    } catch (err) {
        console.error(err);
        alert("Erro ao criar bloco");
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>