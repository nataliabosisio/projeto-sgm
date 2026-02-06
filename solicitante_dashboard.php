<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> dashboard tecnico </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-light"> SGM - Painel do Solicitante </a>
            <div class="d-flex">
                <a class="navbar-brand text-light"> Olá, Maria Solicitante | </a>
                <button class="btn btn-outline-light" type="button"> <a href="api/logout.php" class="text-white  text-decoration-none"> Sair </a></button>
            </div>
        </div>
    </nav>


    <main class="container p-4">

         <nav class="navbar">
  <div class="container-fluid">
    <h1 class="navbar-brand font-weight text-dark p-2 m-2 fs-2">Minhas Solicitações</h1>
    <form class="d-flex" role="search">
      <button class="btn btn-outline-success p-2 m-4" type="submit">+ Nova Solicitação</button>
    </form>
  </div>
        </nav>

        <table class="table border shadow">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Foto</th>
      <th scope="col">Local</th>
      <th scope="col">Descrução</th>
      <th scope="col">Data</th>
      <th scope="col">Status</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <th scope="row">#1</th>
      <td>Foto</td>
      <td>Bloco A</td>
      <td>Bla bla ... bla</td>
      <td>06/02/2026</td>
      <td>Fechado</td>
    </tr>

  </tbody>
</table>
    </main>

    
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>
</html>