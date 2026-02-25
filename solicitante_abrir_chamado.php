<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM - Abrir Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <main>
        <div class="section">
            <div class="card m-5 p-2">
                <h4 class="card-header bg-primary text-white">Abrir Chamado</h4>
                
                <form id="formChamado" class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label">Bloco</label>
                        <select id="selectBloco" class="form-select" required onchange="carregarAmbientes(this.value)">
                            <option value="">Selecione o bloco</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ambiente/Sala</label>
                        <select id="selectAmbiente" class="form-select" required>
                            <option value="">Selecione o Ambiente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Serviço</label>
                        <select id="selectTipo" class="form-select" required>
                            <option value="">Selecione o tipo...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição do Problema</label>
                        <textarea id="descricao" class="form-control" rows="4" required placeholder="EX: Lâmpada queimada..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto da Ocorrência (Opcional)</label>
                        <input type="file" id="foto" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Enviar Chamado</button>
                </form>
            </div>
        </div>
    </main>

<script src="./assets/js/solicitante_abrir_chamado.js"></script>
</body>
</html>