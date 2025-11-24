<?php
/**
 * =====================================================
 * Página de Recuperação de Senha
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

include('config.php');

// Inicializa variáveis
$erro = [];
$sucesso = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Validação do email
    if (empty($email)) {
        $erro[] = "O campo email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro[] = "Por favor, insira um email válido.";
    } else {
        // Busca o usuário no banco
        $sql_code = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $link->prepare($sql_code);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $erro[] = "Email não cadastrado no sistema.";
        } else {
            // Gera uma nova senha aleatória
            $novasenha = substr(md5(time()), 0, 8);
            $senha_hash = password_hash($novasenha, PASSWORD_DEFAULT);

            // Atualiza a senha no banco
            $sql_update = "UPDATE usuarios SET senha = ? WHERE email = ?";
            $stmt_update = $link->prepare($sql_update);
            $stmt_update->bind_param("ss", $senha_hash, $email);

            if ($stmt_update->execute()) {
                // Simula envio de email (em produção, use PHPMailer ou similar)
                $sucesso = "Uma nova senha foi gerada. <br><strong>Nova senha temporária: " . $novasenha . "</strong><br>
                           <small class='text-muted'>Em produção, esta senha seria enviada por email.</small><br>
                           <a href='login.php' class='btn btn-primary mt-2'>Fazer Login</a>";
                $email = ''; // Limpa o campo
            } else {
                $erro[] = "Erro ao atualizar a senha. Tente novamente.";
            }

            $stmt_update->close();
        }
        $stmt->close();
    }
}

$link->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Sistema de Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="card fade-in">
            <div class="card-header text-center">
                <h3 class="mb-0"><i class="fas fa-key me-2"></i>Recuperar Senha</h3>
            </div>
            <div class="card-body">
                <!-- Exibição de Mensagens de Erro -->
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

                <!-- Exibição de Mensagem de Sucesso -->
                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success alert-dismissible fade show alert-permanent" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $sucesso; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (empty($sucesso)): ?>
                <!-- Texto Informativo -->
                <p class="text-muted text-center mb-4">
                    Digite seu email cadastrado e enviaremos instruções para redefinir sua senha.
                </p>

                <!-- Formulário de Recuperação -->
                <form id="recuperarSenhaForm" method="POST" action="" novalidate>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($email); ?>" 
                               placeholder="seuemail@exemplo.com" required autofocus>
                        <div class="invalid-feedback">Por favor, insira um email válido.</div>
                    </div>

                    <!-- Botão de Submit -->
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Instruções
                        </button>
                    </div>

                    <!-- Links -->
                    <div class="text-center">
                        <p class="mb-0">
                            <a href="login.php">
                                <i class="fas fa-arrow-left me-1"></i>Voltar para o Login
                            </a>
                        </p>
                        <p class="mb-0 mt-2">
                            Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
                        </p>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer mt-3">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Sistema de Login. Todos os direitos reservados.</p>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Customizado -->
    <script src="../js/validation.js"></script>
</body>
</html>