<?php
/**
 * =====================================================
 * Página Sobre o Sistema
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

session_start();
$is_logged = isset($_SESSION['usuario']);
$nome = $is_logged ? $_SESSION['nome'] : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o Sistema - Sistema de Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="<?php echo $is_logged ? 'dashboard-container' : ''; ?>">
    <?php if ($is_logged): ?>
    <!-- Navbar para usuários logados -->
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
                    <li class="nav-item">
                        <a class="nav-link active" href="sobre.php">
                            <i class="fas fa-info-circle me-1"></i>Sobre
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($nome); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="alterar_senha.php"><i class="fas fa-key me-2"></i>Alterar Senha</a></li>
                            <?php if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin'): ?>
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
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <?php if ($is_logged): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Sobre</li>
            </ol>
        </nav>
        <?php endif; ?>

        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">
                <i class="fas fa-shield-alt text-primary"></i>
                Sistema de Login PHP
            </h1>
            <p class="lead text-muted">Plataforma completa de autenticação e gerenciamento de usuários</p>
        </div>

        <!-- Features -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-lock fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Segurança Avançada</h5>
                        <p class="card-text text-muted">
                            Proteção com password_hash, prepared statements e validações em múltiplas camadas.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-mobile-alt fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Design Responsivo</h5>
                        <p class="card-text text-muted">
                            Interface moderna com Bootstrap 5, adaptável a qualquer dispositivo.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-tachometer-alt fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Alto Desempenho</h5>
                        <p class="card-text text-muted">
                            Otimizado com índices de banco de dados e queries eficientes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tecnologias -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-code me-2"></i>Tecnologias Utilizadas</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h5 class="text-primary"><i class="fas fa-server me-2"></i>Backend</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fab fa-php me-2 text-primary"></i>PHP 8.x
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-database me-2 text-warning"></i>MySQL 8.0
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-server me-2 text-success"></i>Nginx
                            </li>
                            <li class="list-group-item">
                                <i class="fab fa-docker me-2 text-info"></i>Docker & Docker Compose
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-success"><i class="fas fa-paint-brush me-2"></i>Frontend</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fab fa-bootstrap me-2 text-purple"></i>Bootstrap 5.3
                            </li>
                            <li class="list-group-item">
                                <i class="fab fa-js me-2 text-warning"></i>JavaScript (Vanilla)
                            </li>
                            <li class="list-group-item">
                                <i class="fab fa-css3-alt me-2 text-primary"></i>CSS3 Customizado
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-icons me-2 text-danger"></i>Font Awesome 6.4
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funcionalidades -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Funcionalidades</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Cadastro de usuários com validação
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Login seguro com criptografia
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Recuperação de senha
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Dashboard administrativo
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Edição de perfil
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Alteração de senha segura
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Gerenciamento de usuários (Admin)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Paginação e filtros
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Indicador de força de senha
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Proteção contra SQL Injection e XSS
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Projeto -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Sobre o Projeto</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar me-2"></i>Data de Entrega:</strong> 25/11/2025</p>
                        <p><strong><i class="fas fa-book me-2"></i>Referência:</strong> Jon Duckett - Capítulo 16</p>
                        <p><strong><i class="fas fa-graduation-cap me-2"></i>Tipo:</strong> Projeto Acadêmico</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-code-branch me-2"></i>Versão:</strong> 1.0.0</p>
                        <p><strong><i class="fab fa-github me-2"></i>GitHub:</strong> 
                            <a href="https://github.com/coldrenatinho/loginphp" target="_blank">
                                coldrenatinho/loginphp
                            </a>
                        </p>
                        <p><strong><i class="fas fa-file-alt me-2"></i>Licença:</strong> MIT</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mb-5">
            <?php if (!$is_logged): ?>
            <a href="login.php" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-sign-in-alt me-2"></i>Fazer Login
            </a>
            <a href="cadastro.php" class="btn btn-success btn-lg">
                <i class="fas fa-user-plus me-2"></i>Cadastrar
            </a>
            <?php else: ?>
            <a href="admin.php" class="btn btn-primary btn-lg">
                <i class="fas fa-tachometer-alt me-2"></i>Ir para Dashboard
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="mb-2">&copy; <?php echo date('Y'); ?> Sistema de Login PHP. Todos os direitos reservados.</p>
            <p class="mb-0 text-muted">
                <small>Desenvolvido com <i class="fas fa-heart text-danger"></i> por Renato</small>
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Customizado -->
    <script src="../js/validation.js"></script>
</body>
</html>
