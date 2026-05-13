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
    background:#f5f7fb;
    font-family:Arial,sans-serif;
    min-height:100vh;
}

/* TOPO */
.topbar{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    margin-top:30px;
    margin-bottom:25px;
}

.topbar h3{
    color:#267899;
    margin:0;
}

.topbar p{
    color:#888;
    margin:0;
}

/* CARD */
.form-card{
    background:white;
    border-radius:18px;
    padding:30px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

.form-label{
    font-weight:600;
    color:#444;
}

.form-control,
.form-select{
    border-radius:10px;
    padding:10px;
}

/* BOTÕES */
.btn-sgm{
    background:#267899;
    color:white;
    border:none;
    border-radius:10px;
    padding:12px;
    font-weight:600;
}

.btn-sgm:hover{
    background:#1f6480;
    color:white;
}

.btn-voltar{
    border-radius:10px;
}
</style>
</head>
<body>

<div class="container" style="max-width:700px;">

    <!-- topo -->
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h3>
                    <i class="bi bi-tools"></i>
                    Abrir Chamado
                </h3>
                <p>Cadastre uma nova solicitação de manutenção</p>
            </div>

            <a href="solicitante_dashboard.php" class="btn btn-outline-secondary btn-voltar">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>

        </div>
    </div>

    <!-- formulário -->
    <div class="form-card">

        <form id="formChamado">

            <div class="mb-3">
                <label class="form-label">Bloco</label>
                <select id="selectBloco"
                        class="form-select"
                        required
                        onchange="carregarAmbientes(this.value)">
                    <option value="">Selecione o bloco</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ambiente / Sala</label>
                <select id="selectAmbiente"
                        class="form-select"
                        required
                        disabled>
                    <option value="">Selecione o ambiente</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Serviço</label>
                <select id="selectTipo"
                        class="form-select"
                        required>
                    <option value="">Selecione o tipo</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição do Problema</label>
                <textarea id="descricao"
                          class="form-control"
                          rows="4"
                          placeholder="Ex: lâmpada queimada, vazamento, computador não liga..."
                          required></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Foto da ocorrência (opcional)</label>
                <input type="file"
                       id="foto"
                       class="form-control"
                       accept="image/*">
            </div>

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

    // carregar blocos
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

    // carregar tipos
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
        window.location.href='gestor_chamados.php';
    }else{
        alert("Erro: " + result.message);
    }

});

window.onload = iniciar;
</script>

</body>
</html>