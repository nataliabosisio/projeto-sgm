<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Detalhes </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <main>
        <div class="column m-2 p-4">
            <button type="button" class="btn btn-outline-secondary" disabled> Voltar </button>
        </div>

        <section class="column">
            <div class="card shadow" style="width: 35rem; margin: 5px; padding: 4px;">
                
            <div class="card-body">
                <h5 class="card-title">Dados da Solicitação</h5>        
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Status:</strong></li>
                <li class="list-group-item"><strong>Descrição:</strong></li>
                <li class="list-group-item"><strong>Local:</strong></li>
                <li class="list-group-item"><strong>Solicitante:</strong></li>
                <li class="list-group-item"><strong>Abertura:</strong></li>
                <li class="list-group-item"><strong>Evidências:</strong></li>
            </ul>

            <div class="card-body">
                <!-- <img> -->
            </div>
        </div>
        </section>

        <div class="d-grid gap-2">
            <button class="btn btn-warning" type="button">Reabrir chamado</button>
        </div>

        <section class="column m-5 p-4 border">
            <form>
                <fieldset disabled>
                    <legend>Triagem e Atribuição</legend>
                <div class="mb-3">
                     <label for="disabledTextInput" class="form-label">Técnico Responsável</label>
                     <input type="text" id="disabledTextInput" class="form-control" placeholder="Disabled input">
                </div>

                 <div class="mb-3">
                    <label for="disabledSelect" class="form-label">Prioridade</label>
                <select id="disabledSelect" class="form-select">
                    <option>Disabled select</option>
                </select>
                </div>
            <div class="mb-3">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </fieldset>
</form>
        </section>
    </main>

    
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>