<?php
/**
 * =====================================================
 * Página de Listagem de Usuários (Apenas Admin)
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

include_once 'protect.php';
protect();

include_once 'config.php';

// Verifica se é admin
if ($_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: admin.php");
    exit();
}

$nome = $_SESSION['nome'];

// Paginação
$por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $por_pagina;

// Filtros
$filtro_busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$filtro_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : '';

// Monta a query
$where_conditions = [];
$params = [];
$types = '';

if (!empty($filtro_busca)) {
    $where_conditions[] = "(nome LIKE ? OR sobrenome LIKE ? OR email LIKE ?)";
    $busca_param = "%{$filtro_busca}%";
    $params[] = $busca_param;
    $params[] = $busca_param;
    $params[] = $busca_param;
    $types .= 'sss';
}

if (!empty($filtro_nivel)) {
    $where_conditions[] = "nivel_acesso = ?";
    $params[] = $filtro_nivel;
    $types .= 's';
}

$where_clause = count($where_conditions) > 0 ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Conta total de registros
$sql_count = "SELECT COUNT(*) as total FROM usuarios {$where_clause}";
if (!empty($params)) {
    $stmt_count = $link->prepare($sql_count);
    if (!empty($types)) {
        $stmt_count->bind_param($types, ...$params);
    }
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $total_usuarios = $result_count->fetch_assoc()['total'];
    $stmt_count->close();
} else {
    $result_count = $link->query($sql_count);
    $total_usuarios = $result_count->fetch_assoc()['total'];
}

$total_paginas = ceil($total_usuarios / $por_pagina);

// Busca os usuários
$sql = "SELECT id, nome, sobrenome, email, sexo, nivel_acesso, data_cadastro 
        FROM usuarios {$where_clause} 
        ORDER BY data_cadastro DESC 
        LIMIT {$por_pagina} OFFSET {$offset}";

$usuarios = [];
if (!empty($params)) {
    $stmt = $link->prepare($sql);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    $stmt->close();
} else {
    $result = $link->query($sql);
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$link->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - Sistema de Login</title>
    
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
                            <li><a class="dropdown-item" href="alterar_senha.php"><i class="fas fa-key me-2"></i>Alterar Senha</a></li>
                            <li><a class="dropdown-item active" href="usuarios.php"><i class="fas fa-users me-2"></i>Usuários</a></li>
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
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Usuários</li>
            </ol>
        </nav>

        <!-- Card de Usuários -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="mb-0"><i class="fas fa-users me-2"></i>Gerenciar Usuários</h4>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark"><?php echo $total_usuarios; ?> usuários</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <form method="GET" action="" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label for="busca" class="form-label">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="busca" name="busca" 
                                   placeholder="Nome, sobrenome ou email..." 
                                   value="<?php echo htmlspecialchars($filtro_busca); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="nivel" class="form-label">Nível de Acesso</label>
                        <select class="form-select" id="nivel" name="nivel">
                            <option value="">Todos</option>
                            <option value="admin" <?php echo ($filtro_nivel === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                            <option value="user" <?php echo ($filtro_nivel === 'user') ? 'selected' : ''; ?>>Usuário</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>
                        <a href="usuarios.php" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Limpar
                        </a>
                    </div>
                </form>

                <!-- Tabela de Usuários -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Sexo</th>
                                <th>Nível</th>
                                <th>Data Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($usuarios) > 0): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><strong>#<?php echo $usuario['id']; ?></strong></td>
                                    <td>
                                        <i class="fas fa-user-circle me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($usuario['nome'] . ' ' . $usuario['sobrenome']); ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope me-2 text-secondary"></i>
                                        <?php echo htmlspecialchars($usuario['email']); ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $sexo_icons = ['M' => 'mars', 'F' => 'venus', 'O' => 'genderless'];
                                        $sexo_labels = ['M' => 'Masculino', 'F' => 'Feminino', 'O' => 'Outro'];
                                        $icon = $sexo_icons[$usuario['sexo']] ?? 'genderless';
                                        $label = $sexo_labels[$usuario['sexo']] ?? 'Outro';
                                        ?>
                                        <i class="fas fa-<?php echo $icon; ?> me-1"></i>
                                        <?php echo $label; ?>
                                    </td>
                                    <td>
                                        <?php if ($usuario['nivel_acesso'] === 'admin'): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-shield-alt me-1"></i>Admin
                                        </span>
                                        <?php else: ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-user me-1"></i>Usuário
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1 text-muted"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($usuario['data_cadastro'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                        Nenhum usuário encontrado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <?php if ($total_paginas > 1): ?>
                <nav aria-label="Navegação de página">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($pagina_atual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $pagina_atual - 1; ?>&busca=<?php echo urlencode($filtro_busca); ?>&nivel=<?php echo urlencode($filtro_nivel); ?>">
                                <i class="fas fa-chevron-left"></i> Anterior
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <?php if ($i == $pagina_atual): ?>
                                <li class="page-item active"><span class="page-link"><?php echo $i; ?></span></li>
                            <?php elseif ($i == 1 || $i == $total_paginas || abs($i - $pagina_atual) <= 2): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?>&busca=<?php echo urlencode($filtro_busca); ?>&nivel=<?php echo urlencode($filtro_nivel); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif (abs($i - $pagina_atual) == 3): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <li class="page-item <?php echo ($pagina_atual >= $total_paginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $pagina_atual + 1; ?>&busca=<?php echo urlencode($filtro_busca); ?>&nivel=<?php echo urlencode($filtro_nivel); ?>">
                                Próxima <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Customizado -->
    <script src="../js/validation.js"></script>
</body>
</html>
