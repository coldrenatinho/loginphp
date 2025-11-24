<?php
/**
 * =====================================================
 * Página de Cadastro de Usuários
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

include_once 'config.php';

// Inicializa variáveis
$erro = [];
$sucesso = '';
$nome = '';
$sobrenome = '';
$email = '';
$sexo = 'M';

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário
    $nome      = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha     = isset($_POST['senha']) ? $_POST['senha'] : '';
    $confirmar_senha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';
    $sexo      = isset($_POST['sexo']) ? $_POST['sexo'] : 'M';

    // Validações Server-Side
    if (empty($nome)) {
        $erro[] = "O campo nome é obrigatório.";
    } elseif (strlen($nome) < 3) {
        $erro[] = "O nome deve ter pelo menos 3 caracteres.";
    }

    if (empty($sobrenome)) {
        $erro[] = "O campo sobrenome é obrigatório.";
    } elseif (strlen($sobrenome) < 2) {
        $erro[] = "O sobrenome deve ter pelo menos 2 caracteres.";
    }

    if (empty($email)) {
        $erro[] = "O campo email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro[] = "Por favor, insira um email válido.";
    }

    if (empty($senha)) {
        $erro[] = "O campo senha é obrigatório.";
    } elseif (strlen($senha) < 6) {
        $erro[] = "A senha deve ter pelo menos 6 caracteres.";
    }

    if (empty($confirmar_senha)) {
        $erro[] = "Por favor, confirme sua senha.";
    } elseif ($senha !== $confirmar_senha) {
        $erro[] = "As senhas não coincidem.";
    }

    // Se não houver erros, prossegue com o cadastro
    if (count($erro) == 0) {
        // Verifica se o email já está cadastrado
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $link->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $erro[] = "Este email já está cadastrado no sistema.";
            $stmt_check->close();
        } else {
            $stmt_check->close();

            // Cria o hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o novo usuário no banco de dados
            $sql = "INSERT INTO usuarios (nome, sobrenome, email, sexo, senha, nivel_acesso) VALUES (?, ?, ?, ?, ?, 'user')";
            
            if ($stmt = $link->prepare($sql)) {
                $stmt->bind_param("sssss", $nome, $sobrenome, $email, $sexo, $senha_hash);

                if ($stmt->execute()) {
                    $sucesso = "Cadastro realizado com sucesso! Você já pode fazer <a href='login.php'>login</a>.";
                    // Limpa os campos após cadastro bem-sucedido
                    $nome = $sobrenome = $email = '';
                    $sexo = 'M';
                } else {
                    $erro[] = "Erro ao cadastrar: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $erro[] = "Erro na preparação da query: " . $link->error;
            }
        }
    }
}



?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Sistema de Login</title>

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
                <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Criar Nova Conta</h3>
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
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $sucesso; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Formulário de Cadastro -->
                <form id="cadastroForm" method="POST" action="" novalidate>
                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="nome" class="form-label">
                            <i class="fas fa-user me-1"></i>Nome *
                        </label>
                        <input type="text" class="form-control" id="nome" name="nome"
                            value="<?php echo htmlspecialchars($nome); ?>" placeholder="Digite seu nome" required>
                        <div class="invalid-feedback">Por favor, insira seu nome.</div>
                    </div>

                    <!-- Sobrenome -->
                    <div class="mb-3">
                        <label for="sobrenome" class="form-label">
                            <i class="fas fa-user me-1"></i>Sobrenome *
                        </label>
                        <input type="text" class="form-control" id="sobrenome" name="sobrenome"
                            value="<?php echo htmlspecialchars($sobrenome); ?>" placeholder="Digite seu sobrenome"
                            required>
                        <div class="invalid-feedback">Por favor, insira seu sobrenome.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email *
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($email); ?>" placeholder="seuemail@exemplo.com" required>
                        <div class="invalid-feedback">Por favor, insira um email válido.</div>
                    </div>

                    <!-- Sexo -->
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

                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            <i class="fas fa-lock me-1"></i>Senha *
                        </label>
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Mínimo 6 caracteres" required>
                        <div class="invalid-feedback">A senha deve ter pelo menos 6 caracteres.</div>
                        <div class="password-strength" id="passwordStrengthBar" style="display: none;"></div>
                        <small class="password-strength-text" id="passwordStrengthText"></small>
                    </div>

                    <!-- Confirmar Senha -->
                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">
                            <i class="fas fa-lock me-1"></i>Confirmar Senha *
                        </label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha"
                            placeholder="Digite a senha novamente" required>
                        <div class="invalid-feedback">As senhas não coincidem.</div>
                    </div>

                    <!-- Botão de Submit -->
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Cadastrar
                        </button>
                    </div>

                    <!-- Link para Login -->
                    <div class="text-center">
                        <p class="mb-0">Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
                        <p class="mb-0 mt-2"><a href="sobre.php">Sobre o Sistema</a></p>
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