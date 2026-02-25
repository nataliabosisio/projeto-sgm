<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { background-color: white; display: flex; align-items: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; margin: auto; }
    </style>
</head>
<body>
    <div class="login-card p-4 bg-white shadow rounded">
        <h1 class="text-center" style="color: #267899;">
        <i class="bi bi-clipboard2-check-fill"></i>
    </h1>
        <h3 class="text-center mb-4">SGM - Acesso  </h3>
        <p class="text-center" style="color: #D3D3D3"> Entre com suas credenciais</p>
        <form id="formLogin">
            <div class="mb-3">
                <label><i class="bi bi-envelope-at-fill"></i> E-mail</label>
               <input type="email" id="email" class="form-control" placeholder="nome@sgm.com" required>
            </div>
            <div class="mb-3">
                <label><i class="bi bi-key-fill"></i> Senha</label>
                <input type="password" id="senha" class="form-control" placeholder="*******" required>
            </div>
            <button type="submit" class="btn w-100" style="background-color: #267899; color: white;">Entrar</button>
            <div id="mensagem" class="mt-3 text-center text-danger small"></div>
        </form>
    </div>

    <script src="assets/js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>