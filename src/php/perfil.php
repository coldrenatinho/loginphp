<?php
/**
 * =====================================================
 * Página de Perfil do Usuário
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

include_once 'protect.php';
protect();

include_once 'config.php';

// Inicializa variáveis
$erro = [];
$sucesso = '';

// Busca informações do usuário
$usuario_id = $_SESSION['usuario'];
$sql = "SELECT nome, sobrenome, email, sexo FROM usuarios WHERE id = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $sobrenome, $email, $sexo);
$stmt->fetch();
$stmt->close();

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $novo_sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
    $novo_email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $novo_sexo = isset($_POST['sexo']) ? $_POST['sexo'] : $sexo;

    // Validações
    if (empty($novo_nome) || strlen($novo_nome) < 3) {
        $erro[] = "O nome deve ter pelo menos 3 caracteres.";
    }

    if (empty($novo_sobrenome) || strlen($novo_sobrenome) < 2) {
        $erro[] = "O sobrenome deve ter pelo menos 2 caracteres.";
    }

    if (empty($novo_email) || !filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        $erro[] = "Por favor, insira um email válido.";
    }

    // Verifica se o email já existe (para outro usuário)
    if (count($erro) == 0 && $novo_email !== $email) {
        $sql_check = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
        $stmt_check = $link->prepare($sql_check);
        $stmt_check->bind_param("si", $novo_email, $usuario_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $erro[] = "Este email já está sendo usado por outro usuário.";
        }
        $stmt_check->close();
    }

    // Atualiza os dados
    if (count($erro) == 0) {
        $sql_update = "UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, sexo = ? WHERE id = ?";
        $stmt_update = $link->prepare($sql_update);
        $stmt_update->bind_param("ssssi", $novo_nome, $novo_sobrenome, $novo_email, $novo_sexo, $usuario_id);
        
        if ($stmt_update->execute()) {
            $sucesso = "Perfil atualizado com sucesso!";
            // Atualiza as variáveis com os novos valores
            $nome = $novo_nome;
            $sobrenome = $novo_sobrenome;
            $email = $novo_email;
            $sexo = $novo_sexo;
            // Atualiza a sessão
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
        } else {
            $erro[] = "Erro ao atualizar o perfil. Tente novamente.";
        }
        $stmt_update->close();
    }
}

$link->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Sistema de Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-container">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">
                <i class="fas fa-shield-alt me-2"></i>Sistema de Login
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($nome); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="perfil.php"><i class="fas fa-user me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="alterar_senha.php"><i class="fas fa-key me-2"></i>Alterar Senha</a></li>
                            <?php if ($_SESSION['nivel_acesso'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="usuarios.php"><i class="fas fa-users me-2"></i>Usuários</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirmLogout(event)">
                                <i class="fas fa-sign-out-alt me-2"></i>Sair
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Meu Perfil</li>
                    </ol>
                </nav>

                <!-- Card do Perfil -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Perfil</h4>
                    </div>
                    <div class="card-body">
                        <!-- Mensagens -->
                        <?php if (count($erro) > 0): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Erro!</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($erro as $msg): ?>
                                <li><?php echo htmlspecialchars($msg); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($sucesso)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $sucesso; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <!-- Formulário -->
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nome *
                                    </label>
                                    <input type="text" class="form-control" id="nome" name="nome" 
                                           value="<?php echo htmlspecialchars($nome); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sobrenome" class="form-label">
                                        <i class="fas fa-user me-1"></i>Sobrenome *
                                    </label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" 
                                           value="<?php echo htmlspecialchars($sobrenome); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="sexo" class="form-label">
                                    <i class="fas fa-venus-mars me-1"></i>Sexo *
                                </label>
                                <select class="form-select" id="sexo" name="sexo" required>
                                    <option value="M" <?php echo ($sexo === 'M') ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="F" <?php echo ($sexo === 'F') ? 'selected' : ''; ?>>Feminino</option>
                                    <option value="O" <?php echo ($sexo === 'O') ? 'selected' : ''; ?>>Outro</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="admin.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card de Ações Adicionais -->
                <div class="card shadow mt-3">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configurações da Conta</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="alterar_senha.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-key me-2 text-warning"></i>
                                <strong>Alterar Senha</strong>
                                <p class="mb-0 text-muted small">Mantenha sua conta segura alterando sua senha regularmente</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Customizado -->
    <script src="../js/validation.js"></script>
</body>
</html>
