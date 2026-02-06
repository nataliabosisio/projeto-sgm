<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados Gestor</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    
    <nav class="navbar bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-light"> SGM Admin </a>
            <div class="d-flex">
                <button class="btn btn-outline-light border-0" type="button"> Chamados </button>
                <button class="btn btn-outline-secondary border-0" type="button"> Locais</button>
                <button class="btn btn-outline-secondary border-0" type="button"> <a href="api/logout.php" class="text-secondary text-decoration-none"> Sair </a></button>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="row">
            <h1 class="navbar-brand font-weight text-dark p-2 fs-2">Todos os Chamados</h1>
        </div>

        <div class="column">
            <button type="button" class="btn btn-outline-secondary">Todos</button>
            <button type="button" class="btn btn-outline-primary">Abertos</button>
            <button type="button" class="btn btn-outline-warning">Em execução</button>
            <button type="button" class="btn btn-outline-success">Concluídos</button>
        </div>
        

        
        <table class="table border shadow m-3 p-4">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Solicitante</th>
      <th scope="col">Local/Tipo</th>
      <th scope="col">Prioridade</th>
      <th scope="col">Técnico</th>
      <th scope="col">Status</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <th scope="row">#1</th>
      <td>Maria Solicitante</td>
      <td>Bloco A</td>
      <td><i class="bi bi-circle-fill text-warning"></i> ALTA </td>
      <td>João Técnico</td>
      <td><span class="bg-secondary rounded text-light p-1">Fechado</span></td>
      <td><span class="bg-primary rounded text-light p-1"><i class="bi bi-eye text-light"></i> Gerenciar</span></td>
    </tr>

  </tbody>
</table>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>