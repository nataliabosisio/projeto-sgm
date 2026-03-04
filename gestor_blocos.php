<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambientes</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
     <nav class="navbar" style="background-color: #267899">
        <div class="container-fluid">
            <a class="navbar-brand text-light"> <i class="bi bi-briefcase"></i> SGM | Gestão Administrativa </a>
            <div class="d-flex">
                <a class="navbar-brand text-light"> Olá, Admin Gestor | </a>
                 <button class="btn text-white " style="background-color: #267899;" type="button"><a href="./gestor_dashboard.php" class="text-white text-decoration-none border-0"> Inicio </a></button>
                <button class="btn" type="button"> <a href="api/logout.php" class="text-white text-decoration-none border-0"> Sair </a></button>
            </div>
        </div>
</nav>

<!-- button modal  -->
<div class="d-flex justify-content-between mb-4 mt-5 m-5">
            <button type="button" class="btn" style="background-color: #267899; color: white;" data-bs-toggle="modal" data-bs-target="#criar" data-bs-whatever="@mdo"> + Novo Bloco</button>
        </div>

<!-- modal  -->
        <div class="modal fade" id="criar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Criar Bloco</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Nome do Bloco:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Descrição:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn"  style="background-color: #267899; color: white;" >Criar</button>
      </div>
    </div>
  </div>
</div>



    <div class="container mt-3">
        <h2 class="mb-4"> <i class="bi bi-layers-fill"></i> Todos os Blocos</h2>
        <div class="container">
        
        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID Bloco</th>
                            <th>Nome do Bloco</th>
                            <th>Descrição</th>
                            <th> Configurações </th>
    
                        </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>19</td>
                        <td>Nome do bloco</td>
                        <td>77</td>
                        <td> Editar </td>
                      </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>
</html>