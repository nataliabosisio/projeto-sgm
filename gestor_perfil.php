<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redireciona se não estiver logado
if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Define o ID ativo vindo da sessão (priorizando id_usuario)
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : $_SESSION['user_id'];

// Inclui a conexão a partir da pasta config
if (file_exists(__DIR__ . '/config/database.php')) {
    require_once __DIR__ . '/config/database.php';
} elseif (file_exists(__DIR__ . '/database.php')) {
    require_once __DIR__ . '/database.php';
} else {
    die("Erro: Arquivo database.php não encontrado.");
}

// Garante o uso da variável correta do MySQLi
if (!isset($conn) && isset($mysqli)) { $conn = $mysqli; }
if (!isset($conn)) { die("Erro: Conexão \$conn não definida."); }

$mensagem_sucesso = "";
$mensagem_erro = "";

// 1. Processar o formulário de atualização (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    try {
        if (empty($nome) || empty($email)) {
            throw new Exception("Nome e E-mail são obrigatórios.");
        }

        // Upload da Imagem de Perfil
        $foto_url = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
                $diretorio_upload = 'uploads/perfil/';
                if (!is_dir($diretorio_upload)) { mkdir($diretorio_upload, 0755, true); }
                
                $nome_arquivo = 'user_' . $id_usuario . '_' . time() . '.' . $extensao;
                $caminho_final = $diretorio_upload . $nome_arquivo;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_final)) {
                    $foto_url = $caminho_final;
                }
            }
        }

        // Montagem do SQL utilizando estritamente a coluna 'id_usuario'
        $valores = [$nome, $email];
        $tipos = "ss";
        $sql = "UPDATE usuarios SET nome = ?, email = ?";
        
        if ($foto_url) {
            $sql .= ", foto = ?";
            $tipos .= "s";
            $valores[] = $foto_url;
        }
        
        if (!empty($nova_senha)) {
            if ($nova_senha !== $confirmar_senha) {
                throw new Exception("A nova senha e a confirmação não coincidem.");
            }
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql .= ", senha = ?";
            $tipos .= "s";
            $valores[] = $senha_hash;
        }
        
        $sql .= " WHERE id_usuario = ?";
        $tipos .= "i";
        $valores[] = $id_usuario;
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($tipos, ...$valores);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['user_nome'] = $nome;
            $mensagem_sucesso = "Perfil atualizado com sucesso!";
        } else {
            throw new Exception("Erro ao preparar a atualização: " . $conn->error);
        }
    } catch (Exception $e) {
        $mensagem_erro = $e->getMessage();
    }
}

// 2. Buscar dados atuais usando estritamente 'id_usuario'
$usuario = ['nome' => '', 'email' => '', 'foto' => ''];
$stmt = $conn->prepare("SELECT nome, email, foto FROM usuarios WHERE id_usuario = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($dados = $resultado->fetch_assoc()) {
        $usuario = $dados;
    }
    $stmt->close();
}

$foto_atual = (!empty($usuario['foto']) && file_exists($usuario['foto'])) 
    ? $usuario['foto'] 
    : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - SGM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body{ margin:0; background:#f5f7fb; font-family:Arial,sans-serif; }
        .sidebar{ position:fixed; top:0; left:0; width:250px; height:100vh; background:white; border-right:1px solid #eee; transition:.3s; overflow:hidden; box-shadow:4px 0 15px rgba(0,0,0,.05); z-index:1000; }
        .sidebar.collapsed{ width:80px; }
        .logo{ padding:25px; font-size:1.3rem; color:#267899; font-weight:bold; border-bottom:1px solid #f0f0f0; white-space:nowrap; position:relative; }
        .logo-text{ transition:.3s; }
        .sidebar.collapsed .logo-text{ display:none; }
        .toggle-btn{ position:absolute; right:15px; top:25px; cursor:pointer; color:#267899; font-size:1.2rem; }
        .menu{ padding:20px 10px; }
        .menu a{ display:flex; align-items:center; gap:15px; padding:14px 18px; text-decoration:none; color:#555; border-radius:12px; margin-bottom:8px; transition:.3s; white-space:nowrap; }
        .menu a:hover{ background:#eef7fb; color:#267899; }
        .menu a.active{ background:#267899; color:white; }
        .sidebar.collapsed .menu-text{ display:none; }
        .main{ margin-left:250px; padding:35px; transition:.3s; }
        .main.expanded{ margin-left:80px; }
        .topbar{ background:white; border-radius:18px; padding:25px; box-shadow:0 4px 15px rgba(0,0,0,.05); margin-bottom:25px; }
        .topbar h3{ color:#267899; margin:0; }
        .profile-card { background: white; border-radius: 18px; padding: 35px; box-shadow: 0 4px 15px rgba(0,0,0,.05); }
        .profile-avatar-container { position: relative; width: 130px; height: 130px; margin: 0 auto 20px auto; }
        .profile-avatar { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 4px solid #267899; }
        .avatar-edit-badge { position: absolute; bottom: 0; right: 0; background: #267899; color: white; padding: 6px 10px; border-radius: 50%; cursor: pointer; transition: .2s; }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="logo">
        <i class="bi bi-clipboard2-check-fill"></i> <span class="logo-text">SGM</span>
        <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
    </div>
    <div class="menu">
        <a href="dashboard_gestor.php"><i class="bi bi-house"></i><span class="menu-text">Dashboard</span></a>
        <a href="gestor_chamados.php"><i class="bi bi-list-ul"></i><span class="menu-text">Chamados</span></a>
        <a href="gestor_blocos.php"><i class="bi bi-intersect"></i><span class="menu-text">Blocos</span></a>
        <a href="gestor_servicos.php"><i class="bi bi-card-heading"></i><span class="menu-text">Serviços</span></a>
        <a href="./ambientes_gestor.php"><i class="bi bi-geo-alt"></i><span class="menu-text">Ambientes</span></a>
        <a href="usuarios.php"><i class="bi bi-people-fill"></i><span class="menu-text">Usuários</span></a>
        <a class="active"><i class="bi bi-person-circle"></i><span class="menu-text">Perfil</span></a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i><span class="menu-text">Sair</span></a>
    </div>
</div>

<div class="main" id="main">
    <div class="topbar">
        <h3>Configurações de Perfil</h3>
        <p>Olá, <span><?php echo htmlspecialchars($usuario['nome'] ?: 'Usuário'); ?></span></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card">
                <?php if($mensagem_sucesso): ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?php echo $mensagem_sucesso; ?></div>
                <?php endif; ?>
                <?php if($mensagem_erro): ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $mensagem_erro; ?></div>
                <?php endif; ?>

                <form action="gestor_perfil.php" method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <div class="profile-avatar-container">
                            <img src="<?php echo $foto_atual; ?>" id="avatar-preview" class="profile-avatar">
                            <label for="foto-input" class="avatar-edit-badge"><i class="bi bi-camera-fill"></i></label>
                            <input type="file" id="foto-input" name="foto" accept="image/*" class="d-none" onchange="previewImage(this)">
                        </div>
                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($usuario['nome'] ?: 'Usuário'); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($usuario['email']); ?></small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nova Senha</label>
                            <input type="password" name="nova_senha" class="form-control" placeholder="Deixe em branco para manter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmar Senha</label>
                            <input type="password" name="confirmar_senha" class="form-control" placeholder="Repita a nova senha">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn text-white" style="background: #267899;"><i class="bi bi-save me-2"></i>Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('expanded');
}
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { document.getElementById('avatar-preview').src = e.target.result; }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>