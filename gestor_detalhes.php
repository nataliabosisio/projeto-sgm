<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do chamado não informado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM - Detalhes do Chamado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
    body{
        background:#f5f7fb;
        font-family:Arial, sans-serif;
    }

    /* TOP */
    .topbar{
        background:white;
        margin:25px;
        padding:20px;
        border-radius:16px;
        box-shadow:0 4px 15px rgba(0,0,0,.05);
    }

    .topbar h3{
        color:#267899;
        margin:0;
    }

    /* CARD */
    .card-modern{
        background:white;
        border-radius:16px;
        padding:20px;
        box-shadow:0 4px 15px rgba(0,0,0,.05);
        margin-bottom:20px;
    }

    .card-title{
        color:#267899;
        font-weight:bold;
        margin-bottom:15px;
    }

    /* BOTÕES */
    .btn-main{
        background:#267899;
        color:white;
        border:none;
        border-radius:10px;
        padding:10px 14px;
    }

    .btn-main:hover{
        background:#1f5f7a;
        color:white;
    }

    /* IMAGENS */
    .thumb-img{
        width:100%;
        height:110px;
        object-fit:cover;
        border-radius:10px;
        cursor:pointer;
        transition:.2s;
    }

    .thumb-img:hover{
        transform:scale(1.03);
    }

    /* STATUS */
    .status{
        padding:6px 10px;
        border-radius:10px;
        font-size:12px;
        color:white;
    }

    .status.aberto{ background:#28a745; }
    .status.em_execucao{ background:#f0ad4e; }
    .status.concluido{ background:#198754; }
    .status.fechado{ background:#0d6efd; }
    </style>
</head>

<body>

    <div class="topbar d-flex justify-content-between align-items-center">
        <h3>Detalhes do Chamado</h3>
        <a href="gestor_chamados.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="container">
        <div class="row">

            <div class="col-md-7">
                <div class="card-modern">
                    <h5 class="card-title">Dados da Solicitação</h5>
                    <div id="detalhesChamado">Carregando...</div>
                </div>
                <div id="areaFechamento"></div>
            </div>

            <div class="col-md-5">

                <div class="card-modern" id="cardTriagem">
                    <h5 class="card-title">Triagem e Atribuição</h5>

                    <form id="formAtribuir">
                        <input type="hidden" id="id_chamado" value="<?= $id ?>">

                        <div class="mb-3">
                            <label class="form-label">Técnico</label>
                            <select id="selectTecnico" class="form-select">
                                <option>Carregando...</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prioridade</label>
                            <select id="prioridade" class="form-select">
                                <option value="baixa">Baixa</option>
                                <option value="media">Média</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Data Prevista</label>
                            <input type="date" id="data_prevista" class="form-control">
                        </div>

                        <button class="btn-main w-100">
                            Confirmar Atribuição
                        </button>
                    </form>
                </div>

                <div class="card-modern d-none" id="cardConcluido">
                    <h5 class="card-title text-success">
                        <i class="bi bi-check-circle-fill"></i> Atendimento Finalizado
                    </h5>
                    <hr>
                    <p><strong>Técnico Responsável:</strong> <span id="infoTecnico">...</span></p>
                    <p><strong>Prioridade Definida:</strong> <span id="infoPrioridade" class="badge bg-secondary">...</span></p>
                    <p><strong>Data Prevista original:</strong> <span id="infoDataPrevista">...</span></p>
                    <div class="alert alert-success mt-3 text-center py-2" style="font-size: 14px;">
                        Este chamado já foi solucionado e não pode receber novas alterações de equipe.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFoto">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-body p-0 text-center">
                    <img id="imgModal" class="img-fluid">
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    const ID = <?= json_encode($id) ?>;

    function verFoto(url){
        document.getElementById('imgModal').src = url;
        new bootstrap.Modal(document.getElementById('modalFoto')).show();
    }

    /* FETCH SEGURO E ROBUSTO */
    async function fetchJSON(url, options = {}) {
        const res = await fetch(url, options);
        const text = await res.text();

        try {
            return JSON.parse(text);
        } catch (e) {
            console.error("❌ Resposta inválida da API:", text);
            return null;
        }
    }

    /* CARREGAR DADOS */
    async function carregarDados(){

        /* ================= TECNICOS ================= */
        const tecnicosResp = await fetchJSON('api/usuarios.php?perfil=tecnico');
        const select = document.getElementById('selectTecnico');
        select.innerHTML = '<option value="">Selecione um técnico...</option>';

        if (!tecnicosResp || tecnicosResp.success === false) {
            console.error("Erro na API usuários:", tecnicosResp);
            select.innerHTML = '<option>Erro ao carregar técnicos</option>';
            return;
        }

        const tecnicos = Array.isArray(tecnicosResp) ? tecnicosResp : (tecnicosResp.data || []);

        tecnicos.forEach(t => {
            select.innerHTML += `<option value="${t.id_usuario}">${t.nome}</option>`;
        });

        /* ================= CHAMADO ================= */
        const c = await fetchJSON(`api/chamados.php?id=${ID}`);

        if(!c){
            document.getElementById('detalhesChamado').innerHTML =
                "<div class='alert alert-danger'>Erro ao carregar chamado</div>";
            return;
        }

        // Verifica se o status indica finalização
        const statusAtual = (c.status || '').toLowerCase();
        if(statusAtual === 'concluido' || statusAtual === 'fechado') {
            // Esconde formulário de atribuição e mostra painel estático
            document.getElementById('cardTriagem').classList.add('d-none');
            document.getElementById('cardConcluido').classList.remove('d-none');

            // Preenche as informações fixas da conclusão
            document.getElementById('infoTecnico').textContent = c.tecnico_nome || 'Não informado';
            document.getElementById('infoPrioridade').textContent = (c.prioridade || 'Não definida').toUpperCase();
            document.getElementById('infoDataPrevista').textContent = c.data_previsao_conclusao 
                ? new Date(c.data_previsao_conclusao.replace(/-/g, '\/')).toLocaleDateString('pt-BR') 
                : 'Não informada';
        } else {
            // Preenche o formulário se o chamado já tiver atribuição ativa
            if (c.id_tecnico) {
                select.value = c.id_tecnico;
                const btnSubmit = document.querySelector('#formAtribuir button');
                if (btnSubmit) {
                    btnSubmit.textContent = 'Atualizar Atribuição';
                }
            }
            if (c.prioridade) {
                document.getElementById('prioridade').value = c.prioridade;
            }
            if (c.data_previsao_conclusao) {
                document.getElementById('data_prevista').value = c.data_previsao_conclusao.substring(0, 10);
            }
        }

        const dataFormatada = c.data_abertura ? new Date(c.data_abertura).toLocaleString('pt-BR') : '';

        document.getElementById('detalhesChamado').innerHTML = `
            <p><strong>Status:</strong>
                <span class="status ${c.status}">
                    ${(c.status || '').toUpperCase()}
                </span>
            </p>

            <p><strong>Descrição:</strong> ${c.descricao_problema || ''}</p>
            <p><strong>Local:</strong> ${c.bloco_nome || ''} - ${c.ambiente_nome || ''}</p>
            <p><strong>Solicitante:</strong> ${c.solicitante_nome || ''}</p>
            <p><strong>Data:</strong> ${dataFormatada}</p>

            <div class="row mt-3" id="fotos"></div>
        `;

        /* ================= ANEXOS ================= */
        const anexosResp = await fetchJSON(`api/anexos.php?id_chamado=${ID}`);
        const anexos = Array.isArray(anexosResp) ? anexosResp : [];

        let html = '';
        anexos.forEach(a => {
            const path = a.caminho_arquivo;
            html += `
                <div class="col-4 mb-2">
                    <img src="${path}" class="thumb-img" onclick="verFoto('${path}')">
                </div>
            `;
        });

        document.getElementById('fotos').innerHTML = html;
    }

    /* ================= ATRIBUIÇÃO ================= */
    document.getElementById('formAtribuir').onsubmit = async (e)=>{
        e.preventDefault();

        const res = await fetchJSON('api/atribuir_chamado.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify({
                id_chamado:ID,
                id_tecnico:document.getElementById('selectTecnico').value,
                prioridade:document.getElementById('prioridade').value,
                data_prevista:document.getElementById('data_prevista').value
            })
        });

        if(res?.success) {
            location.href='gestor_chamados.php';
        } else {
            alert("Erro ao atribuir chamado");
        }
    };

    carregarDados();
    </script>

</body>
</html>