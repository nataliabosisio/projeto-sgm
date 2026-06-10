<?php
session_start();
// Verifica se está logado e se o perfil é técnico
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

// Captura o nome do técnico vindo da sessão (com fallback caso não exista)
$nome_tecnico = isset($_SESSION['user_nome']) ? $_SESSION['user_nome'] : (isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Técnico');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Fila de Trabalho</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            margin: 0;
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        /* SIDEBAR (Idêntica ao Gestor e Solicitante) */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: white;
            border-right: 1px solid #e2e8f0;
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
            box-shadow: 6px 0 24px rgba(0, 0, 0, .01);
            z-index: 1000;
        }

        .sidebar.collapsed { width: 80px; }

        .logo {
            padding: 24px;
            font-size: 1.4rem;
            color: #267899;
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar.collapsed .logo-text { display: none; }

        .toggle-btn {
            position: absolute;
            right: 20px;
            top: 26px;
            cursor: pointer;
            color: #267899;
            font-size: 1.3rem;
            transition: transform 0.3s;
        }
        .toggle-btn:hover { transform: scale(1.1); }

        .menu { padding: 20px 12px; }

        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all .2s ease-in-out;
            white-space: nowrap;
            cursor: pointer;
        }

        .menu a:hover { background: #f0f7fa; color: #267899; }
        .menu a.active {
            background: #267899;
            color: white;
            box-shadow: 0 4px 12px rgba(38, 120, 153, 0.15);
        }

        .sidebar.collapsed .menu-text { display: none; }

        /* MAIN AREA */
        .main {
            margin-left: 260px;
            padding: 40px;
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main.expanded { margin-left: 80px; }

        /* HEADER FILA DE TRABALHO */
        .fila-header .saudacao {
            font-size: 1.1rem;
            color: #267899;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .fila-header h2 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .fila-header p {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }

        /* BARRA DE FILTROS (Pílulas conforme imagem) */
        .filter-bar {
            background: white;
            padding: 14px 20px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: transparent;
            border: 1px solid transparent;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* Estados ativos das pílulas de filtro */
        .filter-btn.active[data-filter="todos"] { background: #267899; color: white; }
        .filter-btn.active[data-filter="aberto"] { background: #007bff; color: white; }
        .filter-btn.active[data-filter="em_execucao"] { background: #ffc107; color: #212529; }
        .filter-btn.active[data-filter="finalizados"] { background: #198754; color: white; }

        /* GRID DE CARDS DA FILA */
        .task-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .01);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all .25s ease;
        }

        .task-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .03);
        }

        .task-id {
            color: #267899;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 12px;
        }

        .task-title {
            color: #1e293b;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 4px;
        }

        .task-location {
            color: #8a99ad;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 18px;
        }

        .task-desc-box {
            background: #f1f5f9;
            border-left: 3px solid #267899;
            padding: 16px;
            border-radius: 4px 12px 12px 4px;
            font-size: 0.92rem;
            color: #334155;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            color: #8a99ad;
            font-size: 0.88rem;
            margin-bottom: 18px;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }

        /* BADGES DE STATUS */
        .status-badge {
            position: absolute;
            top: 24px;
            right: 24px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-aberto { background: #007bff; color: white; }
        .status-agendado { background: #00bcd4; color: white; }
        .status-em_execucao { background: #ffc107; color: #212529; }
        .status-concluido { background: #198754; color: white; }
        .status-fechado { background: #6c757d; color: white; }

        /* BOTÃO DE AÇÃO EXPANDIDO */
        .btn-actions-layout {
            display: flex;
            width: 100%;
        }

        .btn-details {
            border: 1px solid #267899;
            background: transparent;
            color: #267899;
            padding: 10px;
            border-radius: 10px;
            width: 100%;
            font-size: 0.92rem;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-details:hover {
            background: #267899;
            color: white;
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
                <i class="bi bi-briefcase"></i>
                <span class="menu-text">Fila de Trabalho</span>
            </a>
            <a href="tecnico_perfil.php">
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
        
        <div class="fila-header">
            <div class="saudacao">Olá, <?php echo htmlspecialchars($nome_tecnico); ?>! 👋</div>
            <h2>Fila de Trabalho</h2>
            <p><span id="totalTarefas">0</span> tarefas encontradas</p>
        </div>

        <div class="filter-bar">
            <button class="filter-btn active" data-filter="todos">
                <i class="bi bi-grid-1x2-fill"></i> Todos
            </button>
            <button class="filter-btn" data-filter="aberto">
                <i class="bi bi-exclamation-circle-fill"></i> Abertos
            </button>
            <button class="filter-btn" data-filter="em_execucao">
                <i class="bi bi-tools"></i> Em Execução
            </button>
            <button class="filter-btn" data-filter="finalizados">
                <i class="bi bi-archive-fill"></i> Finalizados
            </button>
        </div>

        <div class="row g-4" id="gridChamados"></div>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('main').classList.toggle('expanded');
        }

        let chamadosDados = [];

        async function carregarFila() {
            try {
                const resposta = await fetch('api/chamados.php');
                chamadosDados = await resposta.json();
                
                renderizarCards('todos');
                configurarFiltros();
            } catch (error) {
                console.error("Erro ao carregar a fila de chamados:", error);
            }
        }

        function renderizarCards(filtro) {
            const grid = document.getElementById('gridChamados');
            grid.innerHTML = '';

            const statusClasses = {
                aberto: 'status-aberto',
                agendado: 'status-agendado',
                em_execucao: 'status-em_execucao',
                concluido: 'status-concluido',
                fechado: 'status-fechado'
            };

            const dadosFiltrados = chamadosDados.filter(c => {
                if (filtro === 'todos') return true;
                if (filtro === 'aberto') return c.status === 'aberto' || c.status === 'agendado';
                if (filtro === 'em_execucao') return c.status === 'em_execucao';
                if (filtro === 'finalizados') return c.status === 'concluido' || c.status === 'fechado';
                return true;
            });

            document.getElementById('totalTarefas').textContent = dadosFiltrados.length;

            if(dadosFiltrados.length === 0) {
                grid.innerHTML = `<div class="col-12 text-center text-muted py-5">Nenhum chamado encontrado nesta categoria.</div>`;
                return;
            }

            dadosFiltrados.forEach(c => {
                const dataFormatada = new Date(c.data_abertura).toLocaleDateString('pt-BR');
                const classeBadge = statusClasses[c.status] || 'status-fechado';
                const textoStatus = c.status.replace('_', ' ').toUpperCase();

                grid.innerHTML += `
                    <div class="col-md-6 col-lg-4">
                        <div class="task-card">
                            <span class="status-badge ${classeBadge}">${textoStatus}</span>
                            <div class="task-id">#${c.id_chamado}</div>
                            
                            <div class="task-title">${c.bloco_nome || 'Ambiente Geral'}</div>
                            <div class="task-location">
                                <i class="bi bi-geo-alt"></i> ${c.ambiente_nome || 'Não Especificado'}
                            </div>

                            <div class="task-desc-box">
                                "${c.descricao_problema}"
                            </div>

                            <div class="task-footer">
                                <span><i class="bi bi-calendar3 me-1"></i> ${dataFormatada}</span>
                                <span><i class="bi bi-clock me-1"></i> --:--</span>
                            </div>

                            <div class="btn-actions-layout">
                                <a href="tecnico_detalhes.php?id=${c.id_chamado}" class="btn-details">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        function configurarFiltros() {
            const botoes = document.querySelectorAll('.filter-btn');
            botoes.forEach(btn => {
                btn.addEventListener('click', () => {
                    botoes.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    const filtroSelecionado = btn.getAttribute('data-filter');
                    renderizarCards(filtroSelecionado);
                });
            });
        }

        carregarFila();
    </script>
</body>
</html>