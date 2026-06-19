<?php
session_start();
// Verifica se está logado e se o perfil é técnico
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

// Captura o ID do chamado vindo da URL
$id_chamado = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Detalhes do Chamado</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            margin: 0;
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        /* SIDEBAR */
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

        /* HEADER INTERNO */
        .page-header h2 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .page-header p {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }

        /* CARDS DO LAYOUT */
        .details-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.01);
            height: 100%;
        }

        .card-header-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 16px;
        }

        .card-header-title.info-title i { color: #267899; }
        .card-header-title.action-title i { color: #267899; }
        .card-header-title.concluido-title i { color: #198754; }

        /* CAMPOS DE EXIBIÇÃO DE INFORMAÇÃO */
        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 24px;
        }

        .info-value.id-highlight { color: #267899; font-weight: 600; }

        /* BADGES DE STATUS DINÂMICOS */
        .status-badge-dinamico {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            display: inline-block;
            text-transform: uppercase;
        }
        .badge-aberto { background: #007bff; color: white; }
        .badge-agendado { background: #00bcd4; color: white; }
        .badge-em_execucao { background: #ffc107; color: #212529; }
        .badge-concluido { background: #198754; color: white; }
        .badge-fechado { background: #6c757d; color: white; }

        /* CAIXAS DE TEXTO EXIBIDO */
        .text-display-box {
            background: #f1f5f9;
            padding: 16px;
            border-radius: 4px 12px 12px 4px;
            color: #334155;
            font-size: 0.92rem;
            border-left: 3px solid #267899;
            margin-bottom: 24px;
        }

        /* FORMULÁRIOS */
        .form-label-custom {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control-custom {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.95rem;
            color: #334155;
            background-color: #f8fafc;
            transition: all 0.2s;
        }
        .form-control-custom:focus {
            background-color: white;
            border-color: #267899;
            box-shadow: 0 0 0 3px rgba(38, 120, 153, 0.1);
            outline: none;
        }

        /* BOTÃO ENVIAR RELATÓRIO */
        .btn-submit-report {
            background: #267899;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            font-weight: 500;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s;
            margin-top: 25px;
            cursor: pointer;
        }
        .btn-submit-report:hover { background: #1f617c; }
        .btn-submit-report:disabled { background: #a4b5bc; cursor: not-allowed; }

        /* MINIATURAS DAS IMAGENS */
        .img-thumbnail-zoom {
            width: 110px; 
            height: 90px; 
            object-fit: cover; 
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
        }
        .img-thumbnail-zoom:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-color: #267899;
        }

        /* MODAL CUSTOMIZADO NATIVO (BLINDADO CONTRA ERROS DO BOOTSTRAP) */
        .custom-image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 99999;
            justify-content: center;
            align-items: center;
        }
        .custom-image-modal.active {
            display: flex;
        }
        .custom-modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            user-select: none;
        }
        .custom-modal-img {
            max-width: 85%;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
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
            <a href="tecnico_minhas_tarefas.php" class="active">
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
        
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2>Detalhes do Atendimento</h2>
                <p>Gerencie as ações necessárias para fechar este chamado</p>
            </div>
            <div>
                <a href="tecnico_minhas_tarefas.php" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px; font-size: 0.9rem; font-weight: 500;">
                    <i class="bi bi-arrow-left me-2"></i> Voltar à Fila
                </a>
            </div>
        </div>

        <div class="content-container p-0">
            <div class="row g-4">
                
                <div class="col-lg-6">
                    <div class="details-card">
                        <div class="card-header-title info-title">
                            <i class="bi bi-info-circle"></i> Informações do Chamado
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="info-label">Id Chamado</div>
                                <div class="info-value id-highlight">#<?= $id_chamado ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="status-badge-dinamico" id="lblStatus">...</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="info-label">Localização</div>
                                <div class="info-value" id="lblLocalizacao">...</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-label">Prioridade</div>
                                <div class="info-value" id="lblPrioridade">...</div>
                            </div>
                        </div>

                        <div class="info-label">Problema Relatado</div>
                        <div class="text-display-box" id="lblDescricao">...</div>

                        <div class="info-label">Fotos (Abertura)</div>
                        <div class="d-flex flex-wrap gap-2 mt-1" id="containerFotosAbertura">
                            <span class="text-muted small">Nenhuma foto anexada.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" id="colunaDireitaDinamica"></div>

            </div>
        </div>
    </div>

    <div id="customImageModal" class="custom-image-modal" onclick="fecharModalImagem()">
        <span class="custom-modal-close" onclick="fecharModalImagem()">&times;</span>
        <img class="custom-modal-img" id="customModalImg" src="">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/bundle.min.js"></script>
    
    <script>
        const idChamado = <?= $id_chamado ?>;

        if (!idChamado || idChamado === 0) {
            alert("ID do chamado inválido.");
            window.location.href = 'tecnico_minhas_tarefas.php';
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('main').classList.toggle('expanded');
        }

        // Funções de controle do Modal Nativo (A prova de falhas)
        function abrirModalImagem(url) {
            const modal = document.getElementById('customImageModal');
            const modalImg = document.getElementById('customModalImg');
            if(modal && modalImg) {
                modalImg.src = url;
                modal.classList.add('active');
            }
        }

        function fecharModalImagem() {
            const modal = document.getElementById('customImageModal');
            if(modal) {
                modal.classList.remove('active');
            }
        }

        // Captura o clique nas fotos de forma delegada e segura
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('img-thumbnail-zoom')) {
                const urlImagem = e.target.getAttribute('data-url');
                if (urlImagem) {
                    abrirModalImagem(urlImagem);
                }
            }
        });

        // 1. BUSCA DETALHES PRINCIPAIS DO CHAMADO
        async function buscarDetalhesChamado() {
            if(!idChamado) return;

            try {
                const resposta = await fetch(`api/chamados.php?id=${idChamado}`);
                const chamado = await resposta.json();

                if(chamado && chamado.id_chamado) {
                    document.getElementById('lblLocalizacao').textContent = `${chamado.bloco_nome} / ${chamado.ambiente_nome}`;
                    document.getElementById('lblDescricao').textContent = chamado.descricao_problema;
                    document.getElementById('lblPrioridade').textContent = chamado.prioridade || 'Média';
                    
                    const statusTxt = chamado.status.toLowerCase();
                    const statusBadge = document.getElementById('lblStatus');
                    statusBadge.textContent = chamado.status.replace('_', ' ');
                    
                    statusBadge.className = `status-badge-dinamico badge-${statusTxt}`;

                    if(statusTxt === 'concluido' || statusTxt === 'fechado') {
                        montarPainelConcluido(chamado);
                    } else {
                        montarFormularioExecucao();
                    }

                    buscarAnexosAbertura();
                } else {
                    alert("Chamado não encontrado.");
                }
            } catch (error) {
                console.error("Erro ao carregar dados do chamado:", error);
            }
        }

        // 2. MONTA O PAINEL SE O CHAMADO JÁ ESTIVER CONCLUÍDO (Corrigido o bug das aspas literais)
        function montarPainelConcluido(chamado) {
            const container = document.getElementById('colunaDireitaDinamica');
            const tempoGasto = chamado.tempo_gasto_minutos ? `${chamado.tempo_gasto_minutos} minutos` : '-';
            const solucaoDefinida = chamado.solucao_tecnica || 'Nenhuma solução detalhada foi registrada.';

            // CORRIGIDO: Agora usando template strings com crase (``) para renderizar as variáveis corretamente na tela
            container.innerHTML = `
                <div class="details-card">
                    <div class="card-header-title concluido-title">
                        <i class="bi bi-journal-check"></i> Relatório de Conclusão
                    </div>

                    <div class="info-label">Solução Registrada</div>
                    <div class="text-display-box">
                        ${solucaoDefinida}
                    </div>

                    <div class="info-label">Tempo Gasto</div>
                    <div class="info-value">${tempoGasto}</div>

                    <div class="info-label">Fotos (Conclusão)</div>
                    <div class="d-flex flex-wrap gap-2 mt-1" id="containerFotosConclusao">
                        <span class="text-muted small">Buscando fotos anexadas...</span>
                    </div>
                </div>
            `;

            buscarAnexosConclusao();
        }

        // 3. BUSCA AS FOTOS DE CONCLUSÃO
        async function buscarAnexosConclusao() {
            try {
                const resposta = await fetch(`api/anexos.php?id_chamado=${idChamado}&tipo=conclusao`);
                if (!resposta.ok) {
                    document.getElementById('containerFotosConclusao').innerHTML = '<span class="text-danger small">Erro ao buscar fotos do servidor.</span>';
                    return;
                }
                
                const anexos = await resposta.json();
                const container = document.getElementById('containerFotosConclusao');

                if (anexos && anexos.length > 0) {
                    container.innerHTML = ''; 
                    anexos.forEach(anexo => {
                        const img = document.createElement('img');
                        img.src = anexo.caminho_arquivo;
                        img.className = 'img-thumbnail img-thumbnail-zoom';
                        img.setAttribute('data-url', anexo.caminho_arquivo);
                        container.appendChild(img);
                    });
                } else {
                    container.innerHTML = '<span class="text-muted small">Nenhuma foto enviada para este encerramento.</span>';
                }
            } catch (e) {
                console.error('Erro ao buscar anexos de conclusão:', e);
                document.getElementById('containerFotosConclusao').innerHTML = '<span class="text-danger small">Erro ao carregar fotos.</span>';
            }
        }

        // 4. BUSCA AS FOTOS DE ABERTURA
        async function buscarAnexosAbertura() {
            try {
                const resposta = await fetch(`api/anexos.php?id_chamado=${idChamado}&tipo=abertura`);
                if (!resposta.ok) return;
                const anexos = await resposta.json();
                const container = document.getElementById('containerFotosAbertura');

                if(anexos && anexos.length > 0) {
                    container.innerHTML = '';
                    anexos.forEach(anexo => {
                        const img = document.createElement('img');
                        img.src = anexo.caminho_arquivo;
                        img.className = 'img-thumbnail img-thumbnail-zoom';
                        img.setAttribute('data-url', anexo.caminho_arquivo);
                        container.appendChild(img);
                    });
                } else {
                    container.innerHTML = '<span class="text-muted small">Nenhuma foto anexada.</span>';
                }
            } catch(e) {
                console.error('Erro ao buscar anexos de abertura:', e);
            }
        }

        // 5. MONTA O FORMULÁRIO SE O CHAMADO AINDA ESTIVER EM ABERTO/EXECUÇÃO
        function montarFormularioExecucao() {
            const container = document.getElementById('colunaDireitaDinamica');
            container.innerHTML = `
                <div class="details-card">
                    <div class="card-header-title action-title">
                        <i class="bi bi-gear"></i> Registrar Conclusão
                    </div>
                    <form id="formConclusao" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label-custom">Solução Técnica Realizada *</label>
                            <textarea class="form-control-custom w-100" name="solucao_tecnica" rows="4" placeholder="Descreva detalhadamente o que foi feito para resolver o problema..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Tempo Gasto (Em Minutos)</label>
                            <input type="number" class="form-control-custom w-100" name="tempo_gasto" placeholder="Ex: 30">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Evidências por Foto (Conclusão)</label>
                            <input type="file" class="form-control" name="fotos_conclusao[]" multiple accept="image/*">
                        </div>
                        <button type="submit" class="btn-submit-report" id="btnSalvar">
                            <i class="bi bi-check-circle"></i> Enviar Relatório e Concluir
                        </button>
                    </form>
                </div>
            `;

            document.getElementById('formConclusao').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('btnSalvar');
                const campoSolucao = e.target.querySelector('[name="solucao_tecnica"]');
                const campoTempo = e.target.querySelector('[name="tempo_gasto"]');
                
                const solucaoTexto = campoSolucao ? campoSolucao.value.trim() : '';
                const tempoGastoValor = campoTempo ? campoTempo.value.trim() : '0';
                
                if (!solucaoTexto) {
                    alert('Por favor, digite a solução técnica aplicada antes de enviar.');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = `Salvando...`;

                const formData = new FormData();
                formData.append('id_chamado', idChamado);
                formData.append('solucao_tecnica', solucaoTexto);
                formData.append('tempo_gasto', tempoGastoValor);

                const inputFotos = e.target.querySelector('input[type="file"]');
                if (inputFotos && inputFotos.files.length > 0) {
                    for (let i = 0; i < inputFotos.files.length; i++) {
                        formData.append('fotos_conclusao[]', inputFotos.files[i]);
                    }
                }

                try {
                    const resposta = await fetch('api/concluir_chamado.php', {
                        method: 'POST',
                        body: formData
                    });

                    const textoResposta = await resposta.text();
                    let resultado;
                    
                    try {
                        resultado = JSON.parse(textoResposta);
                    } catch (err) {
                        console.error("Resposta do servidor inválida:", textoResposta);
                        throw new Error("O servidor retornou uma resposta inesperada.");
                    }

                    if (resultado.sucesso) {
                        alert('Chamado concluído com sucesso!');
                        window.location.href = 'tecnico_minhas_tarefas.php';
                    } else {
                        alert('Erro: ' + (resultado.erro || 'Falha ao salvar.'));
                        btn.disabled = false;
                        btn.innerHTML = `<i class="bi bi-check-circle"></i> Enviar Relatório e Concluir`;
                    }
                } catch (error) {
                    console.error("Erro no envio:", error);
                    alert(error.message || 'Erro de comunicação com o servidor.');
                    btn.disabled = false;
                    btn.innerHTML = `<i class="bi bi-check-circle"></i> Enviar Relatório e Concluir`;
                }
            });
        }

        // Inicializa a busca
        buscarDetalhesChamado();
    </script>
</body>
</html>