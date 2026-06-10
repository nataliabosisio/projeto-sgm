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
        body {
            background: linear-gradient(135deg,#eef7fb,#f7f9fc);
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        /* CONTAINER */
        .page-container {
            max-width: 750px;
            margin: 40px auto;
        }

        /* TOPO */
        .topbar {
            background: linear-gradient(135deg,#267899,#3298c1);
            color: white;
            border-radius: 22px;
            padding: 28px;
            box-shadow: 0 8px 25px rgba(38,120,153,.18);
            margin-bottom: 25px;
        }

        .topbar h3 {
            margin: 0;
            font-weight: bold;
        }

        .topbar p {
            margin: 0;
            opacity: .9;
        }

        /* CARD */
        .form-card {
            background: white;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(0,0,0,.06);
        }

        /* LABEL */
        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        /* INPUT */
        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #dfe6ee;
            transition: .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #267899;
            box-shadow: 0 0 0 0.15rem rgba(38,120,153,.15);
        }

        /* INPUT GROUP */
        .input-group-text {
            background: #f5f7fb;
            border-radius: 12px 0 0 12px;
            border: 1px solid #dfe6ee;
            color: #267899;
        }

        /* FOTO CUSTOMIZADA */
        .upload-box {
            position: relative;
            border: 2px dashed #d4dde7;
            border-radius: 14px;
            padding: 30px 25px;
            text-align: center;
            background: #fafbfd;
            transition: .2s;
            cursor: pointer;
            overflow: hidden;
        }

        .upload-box:hover {
            border-color: #267899;
            background: #f5fbff;
        }

        .upload-box i {
            font-size: 2rem;
            color: #267899;
            display: block;
        }

        /* Esconde o input feio padrão do navegador */
        .upload-box input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Preview da imagem selecionada */
        .preview-img {
            max-height: 150px;
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* BOTÕES */
        .btn-sgm {
            background: #267899;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            transition: .2s;
        }

        .btn-sgm:hover {
            background: #1f6480;
            transform: translateY(-2px);
        }

        .btn-voltar {
            border-radius: 12px;
            background: rgba(255,255,255,.2);
            color: white;
            border: none;
        }

        .btn-voltar:hover {
            background: rgba(255,255,255,.3);
            color: white;
        }
    </style>
</head>
<body>

<div class="page-container">

    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h3>
                <i class="bi bi-tools"></i>
                Abrir Chamado
            </h3>
            <p>Cadastre uma nova solicitação de manutenção</p>
        </div>

        <a href="solicitante_dashboard.php" class="btn btn-voltar">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="form-card">
        <form id="formChamado" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Bloco</label>
                <select id="selectBloco" class="form-select" required onchange="carregarAmbientes(this.value)">
                    <option value="">Selecione o bloco</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ambiente / Sala</label>
                <select id="selectAmbiente" class="form-select" required disabled>
                    <option value="">Selecione o ambiente</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Serviço</label>
                <select id="selectTipo" class="form-select" required>
                    <option value="">Selecione o tipo</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Descrição do Problema</label>
                <textarea id="descricao" class="form-control" rows="4" placeholder="Ex: lâmpada queimada, vazamento, computador não liga..." required></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Foto da ocorrência (opcional)</label>
                
                <div class="upload-box" id="uploadBox">
                    <div id="uploadPrompt">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <p class="mb-0 mt-2 text-muted" id="uploadText">
                            Clique para enviar uma imagem
                        </p>
                    </div>
                    <img id="previewContainer" class="preview-img" alt="Preview da Ocorrência">
                    <input type="file" id="foto" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-sgm w-100">
                <i class="bi bi-send-fill"></i> Enviar Chamado
            </button>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Lógica para renderizar o preview da imagem na tela antes de enviar
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('previewContainer');
    const prompt = document.getElementById('uploadPrompt');
    const uploadText = document.getElementById('uploadText');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'inline-block';
            prompt.querySelector('i').className = "bi bi-check-circle-fill text-success fs-2";
            uploadText.innerHTML = `<strong>${file.name}</strong><br><span class="text-secondary small">Clique para substituir</span>`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.style.display = 'none';
        prompt.querySelector('i').className = "bi bi-cloud-arrow-up";
        uploadText.textContent = "Clique para enviar uma imagem";
    }
});

async function iniciar() {
    try {
        const resB = await fetch('api/localizacoes.php?acao=listar_blocos');
        const blocos = await resB.json();
        const selB = document.getElementById('selectBloco');

        blocos.forEach(b => {
            selB.innerHTML += `<option value="${b.id_bloco}">${b.nome}</option>`;
        });

        const resT = await fetch('api/localizacoes.php?acao=listar_tipos');
        const tipos = await resT.json();
        const selT = document.getElementById('selectTipo');

        tipos.forEach(t => {
            selT.innerHTML += `<option value="${t.id_tipo}">${t.nome}</option>`;
        });
    } catch (error) {
        console.error("Erro ao inicializar listagens:", error);
    }
}

async function carregarAmbientes(id_bloco){
    const selA = document.getElementById('selectAmbiente');

    if(!id_bloco){
        selA.disabled = true;
        selA.innerHTML = '<option value="">Selecione o ambiente</option>';
        return;
    }

    try {
        const res = await fetch(`api/localizacoes.php?acao=listar_ambientes&id_bloco=${id_bloco}`);
        const ambientes = await res.json();

        selA.innerHTML = '<option value="">Selecione o ambiente</option>';

        ambientes.forEach(a => {
            selA.innerHTML += `<option value="${a.id_ambiente}">${a.nome}</option>`;
        });

        selA.disabled = false;
    } catch (error) {
        console.error("Erro ao carregar ambientes:", error);
    }
}

document.getElementById('formChamado').addEventListener('submit', async (e)=>{
    e.preventDefault();

    const formData = new FormData();
    formData.append('id_ambiente', document.getElementById('selectAmbiente').value);
    formData.append('id_tipo', document.getElementById('selectTipo').value);
    formData.append('descricao', document.getElementById('descricao').value);

    const foto = document.getElementById('foto').files[0];
    if(foto){
        formData.append('foto', foto);
    }

    try {
        const response = await fetch('api/salvar_chamado.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if(result.success){
            alert(result.message);
            window.location.href = 'solicitante_dashboard.php';
        } else {
            alert("Erro: " + result.message);
        }
    } catch (error) {
        alert("Erro de comunicação com o servidor.");
        console.error(error);
    }
});

window.onload = iniciar;
</script>

</body>
</html>