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
        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: white;
            border-right: 1px solid #eee;
            transition: .3s;
            overflow: hidden;
            box-shadow: 4px 0 15px rgba(0,0,0,.05);
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .logo {
            padding: 25px;
            font-size: 1.3rem;
            color: #267899;
            font-weight: bold;
            border-bottom: 1px solid #f0f0f0;
        }

        .logo-text {
            transition: .3s;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .toggle-btn {
            position: absolute;
            right: 15px;
            top: 25px;
            cursor: pointer;
            color: #267899;
            font-size: 1.2rem;
        }

        .menu {
            padding: 20px 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            text-decoration: none;
            color: #555;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: .3s;
        }

        .menu a:hover {
            background: #eef7fb;
            color: #267899;
        }

        .menu a.active {
            background: #267899;
            color: white;
        }

        .sidebar.collapsed .menu-text {
            display: none;
        }

        /* MAIN */
        .main {
            margin-left: 250px;
            padding: 35px;
            transition: .3s;
        }

        .main.expanded {
            margin-left: 80px;
        }

        /* TOPBAR */
        .topbar {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
            margin-bottom: 25px;
        }

        .topbar h3 {
            color: #267899;
            margin: 0;
        }

        .topbar p {
            color: #888;
            margin: 0;
        }

        /* CARDS */
        .info-card {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
            border-left: 5px solid #267899;
            transition: .3s;
        }

        .info-card:hover {
            transform: translateY(-3px);
        }

        .info-card h6 {
            color: #777;
        }

        .info-card h2 {
            margin: 0;
            color: #267899;
            font-weight: bold;
        }

        /* TABLE CARD */
        .dashboard-card {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
        }

        .table th {
            color: #267899;
            border-top: none;
        }

        .table td {
            vertical-align: middle;
        }

        tr:hover {
            background: #fafcff;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 500;
        }

        .mini-thumb {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid #ddd;
            transition: .3s;
        }

        .mini-thumb:hover {
            transform: scale(1.08);
        }

        .btn-primary-custom {
            background: #267899;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background: #1f6682;
            color: white;
        }

        /* modal imagem e detalhes */
        .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .img-detalhe {
            max-height: 380px;
            object-fit: cover;
            border-radius: 12px;
            width: 100%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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

<div class="main" id="main">

    <div class="topbar">
        <h3>Minhas Solicitações</h3>
        <p>Olá, <?= htmlspecialchars($_SESSION['user_nome']) ?></p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="info-card">
                <h6>Total de Chamados</h6>
                <h2 id="totalChamados">0</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-card">
                <h6>Em andamento</h6>
                <h2 id="abertosChamados">0</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-card">
                <h6>Concluídos</h6>
                <h2 id="concluidosChamados">0</h2>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="solicitante_abrir_chamado.php" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Nova Solicitação
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
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabelaChamados"></tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalhesTitulo" style="color: #267899; font-weight: bold;">Detalhes do Chamado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-5 text-center d-flex align-items-center justify-content-center" id="modalDetalhesContainerFoto">
                        </div>
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="text-muted small d-block">Localização</label>
                            <strong id="detalheLocal" class="fs-5">-</strong>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Data de Abertura</label>
                            <span id="detalheData">-</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Status Atual</label>
                            <div id="detalheStatus"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Descrição do Problema</label>
                            <p id="detalheDescricao" style="white-space: pre-wrap;" class="bg-light p-3 rounded">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
let listaChamadosGlobal = [];

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}

function verDetalhes(idChamado) {
    const chamado = listaChamadosGlobal.find(c => c.id_chamado == idChamado);
    if (!chamado) return;

    // Preenche os dados de texto do Modal
    document.getElementById('modalDetalhesTitulo').textContent = `Detalhes do Chamado #${chamado.id_chamado}`;
    document.getElementById('detalheLocal').textContent = `${chamado.bloco_nome} - ${chamado.ambiente_nome}`;
    document.getElementById('detalheData').textContent = new Date(chamado.data_abertura).toLocaleDateString('pt-BR');
    document.getElementById('detalheDescricao').textContent = chamado.descricao_problema;

    const cores = {
        aberto: 'bg-secondary',
        agendado: 'bg-info',
        em_execucao: 'bg-warning text-dark',
        concluido: 'bg-success',
        fechado: 'bg-dark'
    };
    
    const statusFormatado = chamado.status.replace('_', ' ').toUpperCase();
    document.getElementById('detalheStatus').innerHTML = `
        <span class="badge ${cores[chamado.status] || 'bg-secondary'}">${statusFormatado}</span>
    `;

    // Processamento Inteligente da Imagem (Trata se vier direto na API principal)
    const containerFoto = document.getElementById('modalDetalhesContainerFoto');
    
    // Verifica se existe alguma propriedade de foto dentro do objeto do chamado
    let urlImagem = chamado.caminho_arquivo || chamado.foto || chamado.anexo || null;

    if (urlImagem) {
        // Limpa caminhos relativos de subpastas se necessário
        if (urlImagem.startsWith('../')) {
            urlImagem = urlImagem.substring(3);
        }
        containerFoto.innerHTML = `<img src="${urlImagem}" class="img-detalhe" alt="Foto do problema" onerror="this.onerror=null; this.parentNode.innerHTML=obterTemplateSemFoto();">`;
    } else {
        containerFoto.innerHTML = obterTemplateSemFoto();
    }

    // Abre o modal de forma limpa
    const modalElement = document.getElementById('modalDetalhes');
    const modalInstancia = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modalInstancia.show();
}

function obterTemplateSemFoto() {
    return `
        <div class="text-center text-muted p-4 bg-light rounded w-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 200px;">
            <i class="bi bi-image fs-1 d-block mb-2 text-secondary"></i>
            <span>Nenhuma imagem anexada</span>
        </div>`;
}

async function carregarChamados(){
    try {
        const response = await fetch('api/chamados.php');
        const chamados = await response.json();
        listaChamadosGlobal = chamados; 
        
        const lista = document.getElementById('tabelaChamados');

        const cores = {
            aberto: 'bg-secondary',
            agendado: 'bg-info',
            em_execucao: 'bg-warning text-dark',
            concluido: 'bg-success',
            fechado: 'bg-dark'
        };

        // Atualiza contadores
        document.getElementById('totalChamados').textContent = chamados.length;
        
        document.getElementById('abertosChamados').textContent = chamados.filter(c => 
            c.status !== 'concluido' && c.status !== 'fechado'
        ).length;

        document.getElementById('concluidosChamados').textContent = chamados.filter(c => 
            c.status === 'concluido' || c.status === 'fechado'
        ).length;

        let linhasHTML = '';

        for (const c of chamados) {
            // Verifica se a imagem veio acoplada no chamado
            let urlImagem = c.caminho_arquivo || c.foto || c.anexo || null;
            let thumb = `<i class="bi bi-image text-muted fs-4"></i>`;

            if (urlImagem) {
                if (urlImagem.startsWith('../')) {
                    urlImagem = urlImagem.substring(3);
                }
                thumb = `<img src="${urlImagem}" class="mini-thumb" onclick="verDetalhes(${c.id_chamado})" alt="Anexo" onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\"bi bi-image text-muted fs-4\"></i>';">`;
            }

            const dataFormatada = new Date(c.data_abertura).toLocaleDateString('pt-BR');
            const statusFormatado = c.status.replace('_', ' ').toUpperCase();

            linhasHTML += `
                <tr>
                    <td>#${c.id_chamado}</td>
                    <td>${thumb}</td>
                    <td>${c.bloco_nome} - ${c.ambiente_nome}</td>
                    <td>${c.descricao_problema.substring(0, 40)}...</td>
                    <td>${dataFormatada}</td>
                    <td>
                        <span class="badge ${cores[c.status] || 'bg-secondary'}">
                            ${statusFormatado}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-light border" onclick="verDetalhes(${c.id_chamado})" title="Ver Detalhes">
                            <i class="bi bi-eye text-dark"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        lista.innerHTML = linhasHTML;

    } catch (error) {
        console.error("Erro ao carregar os chamados:", error);
    }
}

// Inicializa a página carregando os chamados
carregarChamados();
</script>

</body>
</html>