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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Abrir Chamado</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>
body{
    background: linear-gradient(135deg,#eef7fb,#f7f9fc);
    font-family: Arial, sans-serif;
    min-height:100vh;
}

/* CONTAINER */
.page-container{
    max-width:750px;
    margin:40px auto;
}

/* TOPO */
.topbar{
    background: linear-gradient(135deg,#267899,#3298c1);
    color:white;
    border-radius:22px;
    padding:28px;
    box-shadow:0 8px 25px rgba(38,120,153,.18);
    margin-bottom:25px;
}

.topbar h3{
    margin:0;
    font-weight:bold;
}

.topbar p{
    margin:0;
    opacity:.9;
}

/* CARD */
.form-card{
    background:white;
    border-radius:22px;
    padding:35px;
    box-shadow:0 8px 25px rgba(0,0,0,.06);
}

/* LABEL */
.form-label{
    font-weight:600;
    color:#444;
    margin-bottom:8px;
}

/* INPUT */
.form-control,
.form-select{
    border-radius:12px;
    padding:12px;
    border:1px solid #dfe6ee;
    transition:.2s;
}

.form-control:focus,
.form-select:focus{
    border-color:#267899;
    box-shadow:0 0 0 0.15rem rgba(38,120,153,.15);
}

/* INPUT GROUP */
.input-group-text{
    background:#f5f7fb;
    border-radius:12px 0 0 12px;
    border:1px solid #dfe6ee;
    color:#267899;
}

/* FOTO */
.upload-box{
    border:2px dashed #d4dde7;
    border-radius:14px;
    padding:25px;
    text-align:center;
    background:#fafbfd;
    transition:.2s;
}

.upload-box:hover{
    border-color:#267899;
    background:#f5fbff;
}

.upload-box i{
    font-size:2rem;
    color:#267899;
}

/* BOTÕES */
.btn-sgm{
    background:#267899;
    color:white;
    border:none;
    border-radius:12px;
    padding:14px;
    font-weight:600;
    transition:.2s;
}

.btn-sgm:hover{
    background:#1f6480;
    transform:translateY(-2px);
}

.btn-voltar{
    border-radius:12px;
    background:rgba(255,255,255,.2);
    color:white;
    border:none;
}

.btn-voltar:hover{
    background:rgba(255,255,255,.3);
    color:white;
}
</style>
</head>
<body>

<div class="page-container">

    <!-- TOPO -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <div>
            <h3>
                <i class="bi bi-tools"></i>
                Abrir Chamado
            </h3>
            <p>Cadastre uma nova solicitação de manutenção</p>
        </div>

        <a href="solicitante_dashboard.php" class="btn btn-voltar">
            <i class="bi bi-arrow-left"></i>
            Voltar
        </a>

    </div>

    <!-- FORM -->
    <div class="form-card">

        <form id="formChamado">

            <!-- BLOCO -->
            <div class="mb-3">
                <label class="form-label">Bloco</label>
                <select id="selectBloco"
                        class="form-select"
                        required
                        onchange="carregarAmbientes(this.value)">
                    <option value="">Selecione o bloco</option>
                </select>
            </div>

            <!-- AMBIENTE -->
            <div class="mb-3">
                <label class="form-label">Ambiente / Sala</label>
                <select id="selectAmbiente"
                        class="form-select"
                        required
                        disabled>
                    <option value="">Selecione o ambiente</option>
                </select>
            </div>

            <!-- TIPO -->
            <div class="mb-3">
                <label class="form-label">Tipo de Serviço</label>
                <select id="selectTipo"
                        class="form-select"
                        required>
                    <option value="">Selecione o tipo</option>
                </select>
            </div>

            <!-- DESCRIÇÃO -->
            <div class="mb-4">
                <label class="form-label">Descrição do Problema</label>
                <textarea id="descricao"
                        class="form-control"
                        rows="4"
                        placeholder="Ex: lâmpada queimada, vazamento, computador não liga..."
                        required></textarea>
            </div>

            <!-- FOTO -->
            <div class="mb-4">
                <label class="form-label">Foto da ocorrência (opcional)</label>

                <div class="upload-box">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-2 mt-2 text-muted">
                        Clique para enviar uma imagem
                    </p>

                    <input type="file"
                           id="foto"
                           class="form-control"
                           accept="image/*">
                </div>
            </div>

            <!-- BOTÃO -->
            <button type="submit" class="btn btn-sgm w-100">
                <i class="bi bi-send-fill"></i>
                Enviar Chamado
            </button>

        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function iniciar() {

    const resB = await fetch('api/localizacoes.php?acao=listar_blocos');
    const blocos = await resB.json();

    const selB = document.getElementById('selectBloco');

    blocos.forEach(b => {
        selB.innerHTML += `
            <option value="${b.id_bloco}">
                ${b.nome}
            </option>
        `;
    });

    const resT = await fetch('api/localizacoes.php?acao=listar_tipos');
    const tipos = await resT.json();

    const selT = document.getElementById('selectTipo');

    tipos.forEach(t => {
        selT.innerHTML += `
            <option value="${t.id_tipo}">
                ${t.nome}
            </option>
        `;
    });
}

async function carregarAmbientes(id_bloco){

    const selA = document.getElementById('selectAmbiente');

    if(!id_bloco){
        selA.disabled = true;
        return;
    }

    const res = await fetch(
        `api/localizacoes.php?acao=listar_ambientes&id_bloco=${id_bloco}`
    );

    const ambientes = await res.json();

    selA.innerHTML =
        '<option value="">Selecione o ambiente</option>';

    ambientes.forEach(a => {
        selA.innerHTML += `
            <option value="${a.id_ambiente}">
                ${a.nome}
            </option>
        `;
    });

    selA.disabled = false;
}

document.getElementById('formChamado').addEventListener('submit', async (e)=>{

    e.preventDefault();

    const formData = new FormData();

    formData.append(
        'id_ambiente',
        document.getElementById('selectAmbiente').value
    );

    formData.append(
        'id_tipo',
        document.getElementById('selectTipo').value
    );

    formData.append(
        'descricao',
        document.getElementById('descricao').value
    );

    const foto =
        document.getElementById('foto').files[0];

    if(foto){
        formData.append('foto', foto);
    }

    const response = await fetch(
        'api/salvar_chamado.php',
        {
            method:'POST',
            body:formData
        }
    );

    const result = await response.json();

    if(result.success){
        alert(result.message);
        window.location.href='solicitante_dashboard.php';
    }else{
        alert("Erro: " + result.message);
    }

});

window.onload = iniciar;
</script>

</body>
</html>