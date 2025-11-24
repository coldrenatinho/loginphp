<?php
/**
 * =====================================================
 * Página de Alteração de Senha
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

include_once 'protect.php';
protect();

include_once 'config.php';

// Inicializa variáveis
$erro = [];
$sucesso = '';

$usuario_id = $_SESSION['usuario'];
$nome = $_SESSION['nome'];

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = isset($_POST['senha_atual']) ? $_POST['senha_atual'] : '';
    $nova_senha = isset($_POST['nova_senha']) ? $_POST['nova_senha'] : '';
    $confirmar_senha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';

    // Validações
    if (empty($senha_atual)) {
        $erro[] = "Digite sua senha atual.";
    }

    if (empty($nova_senha)) {
        $erro[] = "Digite uma nova senha.";
    } elseif (strlen($nova_senha) < 6) {
        $erro[] = "A nova senha deve ter pelo menos 6 caracteres.";
    }

    if (empty($confirmar_senha)) {
        $erro[] = "Confirme sua nova senha.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro[] = "As senhas não coincidem.";
    }

    // Verifica a senha atual
    if (count($erro) == 0) {
        $sql = "SELECT senha FROM usuarios WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($senha_hash);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($senha_atual, $senha_hash)) {
            $erro[] = "Senha atual incorreta.";
        }
    }

    // Atualiza a senha
    if (count($erro) == 0) {
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        $sql_update = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $stmt_update = $link->prepare($sql_update);
        $stmt_update->bind_param("si", $nova_senha_hash, $usuario_id);
        
        if ($stmt_update->execute()) {
            $sucesso = "Senha alterada com sucesso!";
        } else {
            $erro[] = "Erro ao alterar a senha. Tente novamente.";
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
    <title>Alterar Senha - Sistema de Login</title>
    
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
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item active" href="alterar_senha.php"><i class="fas fa-key me-2"></i>Alterar Senha</a></li>
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
            <div class="col-lg-6">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Alterar Senha</li>
                    </ol>
                </nav>

                <!-- Card de Alteração de Senha -->
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-key me-2"></i>Alterar Senha</h4>
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

                        <!-- Dicas de Segurança -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Dicas para uma senha segura:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Use pelo menos 8 caracteres</li>
                                <li>Combine letras maiúsculas e minúsculas</li>
                                <li>Inclua números e caracteres especiais</li>
                                <li>Não use informações pessoais óbvias</li>
                            </ul>
                        </div>

                        <!-- Formulário -->
                        <form id="alterarSenhaForm" method="POST" action="" novalidate>
                            <div class="mb-3">
                                <label for="senha_atual" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Senha Atual *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="senha_atual" name="senha_atual" 
                                           placeholder="Digite sua senha atual" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="senha_atual">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Digite sua senha atual.</div>
                            </div>

                            <div class="mb-3">
                                <label for="nova_senha" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Nova Senha *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="nova_senha" name="nova_senha" 
                                           placeholder="Mínimo 6 caracteres" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="nova_senha">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">A senha deve ter pelo menos 6 caracteres.</div>
                                <div class="password-strength" id="passwordStrengthBar" style="display: none;"></div>
                                <small class="password-strength-text" id="passwordStrengthText"></small>
                            </div>

                            <div class="mb-3">
                                <label for="confirmar_senha" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Confirmar Nova Senha *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" 
                                           placeholder="Digite a senha novamente" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirmar_senha">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">As senhas não coincidem.</div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="admin.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-1"></i>Alterar Senha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Customizado -->
    <script src="../js/validation.js"></script>
    <script>
        // Validação específica para alteração de senha
        document.getElementById('nova_senha').addEventListener('input', function() {
            const senha = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');
            
            if (senha.length > 0) {
                const strength = checkPasswordStrength(senha);
                strengthBar.className = 'password-strength ' + strength;
                strengthBar.style.display = 'block';
                
                const texts = {
                    'weak': 'Senha Fraca',
                    'medium': 'Senha Média', 
                    'strong': 'Senha Forte'
                };
                const colors = {
                    'weak': '#e74a3b',
                    'medium': '#f6c23e',
                    'strong': '#1cc88a'
                };
                
                strengthText.textContent = texts[strength];
                strengthText.style.color = colors[strength];
            } else {
                strengthBar.style.display = 'none';
                strengthText.textContent = '';
            }
        });
    </script>
</body>
</html>
