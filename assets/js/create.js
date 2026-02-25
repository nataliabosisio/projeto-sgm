
       // Carrega Blocos e Tipos ao iniciar
        async function iniciar() {
            // Blocos
            const resB = await fetch('api/localizacoes.php?acao=listar_blocos');
            const blocos = await resB.json();
            const selB = document.getElementById('selectBloco');
            blocos.forEach(b => selB.innerHTML += `<option value="${b.id_bloco}">${b.nome}</option>`);

            // Tipos
            const resT = await fetch('api/localizacoes.php?acao=listar_tipos');
            const tipos = await resT.json();
            const selT = document.getElementById('selectTipo');
            tipos.forEach(t => selT.innerHTML += `<option value="${t.id_tipo}">${t.nome}</option>`);
        }

        // Carrega Ambientes dinamicamente quando o Bloco muda
        async function carregarAmbientes(id_bloco) {
            const selA = document.getElementById('selectAmbiente');
            if (!id_bloco) { selA.disabled = true; return; }
           
            const res = await fetch(`api/localizacoes.php?acao=listar_ambientes&id_bloco=${id_bloco}`);
            const ambientes = await res.json();
           
            selA.innerHTML = '<option value="">Selecione a Sala...</option>';
            ambientes.forEach(a => selA.innerHTML += `<option value="${a.id_ambiente}">${a.nome}</option>`);
            selA.disabled = false;
        }

document.getElementById('formChamado').addEventListener('submit', async (e) => {
    e.preventDefault();
   
    const formData = new FormData();
    formData.append('id_ambiente', document.getElementById('selectAmbiente').value);
    formData.append('id_tipo', document.getElementById('selectTipo').value);
    formData.append('descricao', document.getElementById('descricao').value);
   
    const fotoFile = document.getElementById('foto').files[0];
    if (fotoFile) {
        formData.append('foto', fotoFile);
    }

    const response = await fetch('api/salvar_chamado.php', {
        method: 'POST',
         body: formData
    });

    const result = await response.json();
    if (result.success) {
        alert(result.message);
        window.location.href = 'solicitante_dashboard.php';
    } else {
        alert("Erro: " + result.message);
    }
});

        iniciar();