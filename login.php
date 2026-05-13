<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #dbeff7, #f7fbfd);
        }

        .login-container {
            width: 900px;
            max-width: 95%;
            min-height: 500px;
            display: flex;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            background: white;
        }

        /* LADO ESQUERDO */
        .welcome-panel {
            width: 50%;
            background: linear-gradient(135deg, #267899, #4aa3c2);
            color: white;
            padding: 60px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-panel::before,
        .welcome-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .welcome-panel::before {
            width: 220px;
            height: 220px;
            top: -60px;
            left: -60px;
        }

        .welcome-panel::after {
            width: 300px;
            height: 300px;
            bottom: -100px;
            right: -100px;
        }

        .welcome-panel h1 {
            font-size: 2.3rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .welcome-panel p {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* LADO DIREITO */
        .login-card {
            width: 50%;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .login-card h1 {
            font-size: 3rem;
            color: #267899;
            margin-bottom: 10px;
        }

        .login-card h3 {
            font-weight: 600;
            color: #333;
        }

        .login-card p {
            color: #999;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 25px;
            padding: 12px 18px;
            border: 1px solid #ddd;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #267899;
            box-shadow: 0 0 0 0.2rem rgba(38,120,153,0.15);
        }

        label {
            margin-bottom: 8px;
            color: #444;
            font-weight: 500;
        }

        .btn-login {
            background: linear-gradient(135deg, #267899, #4aa3c2);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(38,120,153,0.3);
            color: white;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .welcome-panel,
            .login-card {
                width: 100%;
            }

            .welcome-panel {
                min-height: 200px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="login-container">

    <div class="welcome-panel">
        <h1>Bem-vindo!</h1>
        <p>
            Faça login para acessar o Sistema de Gestão de Manutenção.
        </p>
    </div>

    <div class="login-card">
        <h1 class="text-center">
            <i class="bi bi-clipboard2-check-fill"></i>
        </h1>

        <h3 class="text-center mb-2">SGM - Acesso</h3>
        <p class="text-center">Entre com suas credenciais</p>

        <form id="formLogin">
            <div class="mb-3">
                <label><i class="bi bi-envelope-at-fill"></i> E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="nome@sgm.com" required>
            </div>

            <div class="mb-3">
                <label><i class="bi bi-key-fill"></i> Senha</label>
                <input type="password" id="senha" class="form-control" placeholder="*******" required>
            </div>

            <button type="submit" class="btn btn-login w-100">
                Entrar
            </button>

            <div id="mensagem" class="mt-3 text-center text-danger small"></div>
        </form>
    </div>

</div>

<script src="assets/js/login.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>