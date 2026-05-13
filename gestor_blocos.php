<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Blocos </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-2" style="background-color: #267899">

  <div class="container-fluid px-4">

    <!-- Logo esquerda -->
    <a class="navbar-brand fw-semibold mb-0">
      <i class="bi bi-briefcase"></i> SGM | Gestão Administrativa
    </a>

    <!-- Botão mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Conteúdo -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <!-- empurra tudo pra direita -->
      <div class="ms-auto d-flex align-items-center gap-3">

        <span class="text-white small fw-semibold">
          Olá, Admin Gestor
        </span>

        <div class="dropdown">
          <button class="btn d-flex align-items-center justify-content-center rounded-circle p-0"
            style="width: 45px; height: 45px;"
            data-bs-toggle="dropdown">

            <i class="bi bi-person-circle" style="color: white; font-size: 1.5rem;"></i>
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li>
              <a class="dropdown-item py-2" href="gestor_perfil.php">
                <i class="bi bi-person"></i> Meu Perfil
              </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
              <a class="dropdown-item text-danger py-2" href="api/logout.php">
                <i class="bi bi-box-arrow-right"></i> Sair
              </a>
            </li>
          </ul>
        </div>

      </div>

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
                            <th>ID</th>
                            <th>Nome do Bloco </th>
                            <th>Descrição </th>
                            <th> Ações </th>
    
                        </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>19</td>
                        <td>Nome do bloco</td>
                        <td>77</td>
                        <td>
                          <button type="btn" class="text-decoration-none border-0" style="background-color: white; color: black;"> <a href=""><i class="bi bi-pencil"></i> Editar</a> </button> 
                          <button type="btn" class="text-dark text-decoration-none border-0" style="background-color: white;"><a href=""><i class="bi bi-trash3"></i></a></button>
                        </td>
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

