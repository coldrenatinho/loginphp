<?php
/**
 * =====================================================
 * Página de Login
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

require_once 'config.php';

// Inicia a sessão
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa variáveis
$erro = [];
$email = '';

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    // Validações Server-Side
    if (empty($email)) {
        $erro[] = "O campo email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro[] = "Por favor, insira um email válido.";
    }

    if (empty($senha)) {
        $erro[] = "O campo senha é obrigatório.";
    }

    // Se não houver erros, prossegue com a autenticação
    if (count($erro) == 0) {
        // Usa prepared statement para maior segurança
        $sql_code = "SELECT id, nome, senha, nivel_acesso FROM usuarios WHERE email = ?";
        $stmt = $link->prepare($sql_code);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $erro[] = "Email não cadastrado.";
        } else {
            // Vincula os resultados
            $stmt->bind_result($id, $nome, $senha_hash, $nivel_acesso);
            $stmt->fetch();

            // Verifica a senha usando password_verify
            if (password_verify($senha, $senha_hash)) {
                // Senha correta - cria a sessão do usuário
                $_SESSION['usuario'] = $id;
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['nivel_acesso'] = $nivel_acesso;

                // Redireciona para a página protegida
                header("Location: admin.php");
                exit();
            } else {
                $erro[] = "Senha inválida.";
            }
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
    <title>Login - Sistema de Login</title>

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
                <h3 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Fazer Login</h3>
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

                <!-- Formulário de Login -->
                <form id="loginForm" method="POST" action="" novalidate>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($email); ?>" placeholder="seuemail@exemplo.com" required
                            autofocus>
                        <div class="invalid-feedback">Por favor, insira um email válido.</div>
                    </div>

                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            <i class="fas fa-lock me-1"></i>Senha
                        </label>
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Digite sua senha" required>
                        <div class="invalid-feedback">Por favor, insira sua senha.</div>
                    </div>

                    <!-- Link Esqueceu a Senha -->
                    <div class="mb-3 text-end">
                        <a href="esqueceuasenha.php" class="text-muted">
                            <small>Esqueceu sua senha?</small>
                        </a>
                    </div>

                    <!-- Botão de Submit -->
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </div>

                    <!-- Link para Cadastro -->
                    <div class="text-center">
                        <p class="mb-0">Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
                    </div>
                </form>
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