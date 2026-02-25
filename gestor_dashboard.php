<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> dashboard gestor</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>

    <nav class="navbar" style="background-color: #267899">
        <div class="container-fluid">
            <a class="navbar-brand text-light"> <i class="bi bi-briefcase"></i> SGM | Gestão Administrativa </a>
            <div class="d-flex">
                <a class="navbar-brand text-light"> Olá, Admin Gestor | </a>
                <button class="btn" type="button"> <a href="api/logout.php" class="text-white text-decoration-none border-0"> Sair </a></button>
            </div>
        </div>
    </nav>


    <main class="container p-4">

        <h1><i class="bi bi-pc-display-horizontal"></i> Minhas solicitações</h1>
        <h4 class="" style="color: #d3d3d3"> Visão geral </h4>
        <br>
        <div class="row">
            <div class="col-sm-4 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-body p-5 rounded shadow" style="background-color: #50C878">
                        <h4 class="card-title text-light">Novas Solicitações</h5>
                        <p class="card-text text-light">0</p>
                    </div>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body p-5 rounded shadow" style="background-color: #FBD040">
                        <h4 class="card-title text-light">Em atendimento</h5>
                        <p class="card-text text-light">0</p>
                    </div>
                </div>
                
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body p-5 rounded shadow" style="background-color: #F16548">
                        <h4 class="card-title text-light">Críticos / Urgentes </h5>
                        <p class="card-text text-light">0</p>
                    </div>
                </div>
                
            </div>
        </div>

<div class="m-5 shadow rounded-5">
    <div class="d-flex justify-content-center p-3">
        <a href="gestor_chamados.php" type="button" class="btn m-3" style="background-color: #267899; color: white;"><i class="bi bi-list-ul"></i> Gerenciar Chamados</a>
        <button type="button" class="btn m-3" style="color:#267899; border: #267899;"> <i class="bi bi-geo-alt"></i> Configurar Ambientes</button>
    </div>
</div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>