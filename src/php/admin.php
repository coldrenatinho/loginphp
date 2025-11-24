<?php
/**
 * =====================================================
 * Página Administrativa (Protegida)
 * Sistema de Login com PHP e MySQL
 * Acesso restrito apenas para usuários autenticados
 * =====================================================
 */

include_once 'protect.php';
protect();

include_once 'config.php';

// Busca informações do usuário
$usuario_id = $_SESSION['usuario'];
$sql = "SELECT nome, sobrenome, email, nivel_acesso, data_cadastro FROM usuarios WHERE id = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $sobrenome, $email, $nivel_acesso, $data_cadastro);
$stmt->fetch();
$stmt->close();

// Conta total de usuários
$sql_count = "SELECT COUNT(*) as total FROM usuarios";
$result = $link->query($sql_count);
$total_usuarios = $result->fetch_assoc()['total'];

$link->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Login</title>
    
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
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt me-2"></i>Sistema de Login
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($nome); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Perfil</a></li>
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
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 ms-sm-auto px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download me-1"></i>Exportar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mensagem de Boas-Vindas -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Bem-vindo(a), <?php echo htmlspecialchars($nome . ' ' . $sobrenome); ?>!</strong>
                    Você está logado desde <?php echo date('d/m/Y H:i', strtotime($data_cadastro)); ?>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <!-- Cards de Estatísticas -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total de Usuários
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $total_usuarios; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Nível de Acesso
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo ucfirst($nivel_acesso); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Status
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            Ativo
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Segurança
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            Alta
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-lock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Usuário -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 bg-primary text-white">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fas fa-user me-2"></i>Informações do Perfil
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong><i class="fas fa-user me-2"></i>Nome Completo:</strong><br>
                                    <?php echo htmlspecialchars($nome . ' ' . $sobrenome); ?>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                                    <?php echo htmlspecialchars($email); ?>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-shield-alt me-2"></i>Nível de Acesso:</strong><br>
                                    <span class="badge bg-primary"><?php echo ucfirst($nivel_acesso); ?></span>
                                </div>
                                <div class="mb-0">
                                    <strong><i class="fas fa-calendar me-2"></i>Membro desde:</strong><br>
                                    <?php echo date('d/m/Y', strtotime($data_cadastro)); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 bg-success text-white">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fas fa-tasks me-2"></i>Ações Rápidas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="fas fa-user-edit me-2"></i>Editar Perfil
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="fas fa-key me-2"></i>Alterar Senha
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="fas fa-cog me-2"></i>Configurações
                                    </a>
                                    <a href="logout.php" class="list-group-item list-group-item-action text-danger" onclick="return confirmLogout(event)">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sair do Sistema
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Customizado -->
    <script src="../js/validation.js"></script>
</body>
</html>