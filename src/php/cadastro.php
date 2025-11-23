<?php
include_once 'config.php';

$mensagem = '';

// Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha     = isset($_POST['senha']) ? $_POST['senha'] : '';
    $sexo      = isset($_POST['sexo']) ? $_POST['sexo'] : 'O';

    if ($nome === '' || $sobrenome === '' || $email === '' || $senha === '') {
        $mensagem = "Por favor, preencha todos os campos.";
    } else {
        // 1. Criação do Hash da senha (Nunca grave senha em texto plano)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // 2. Uso de Prepared Statements para segurança e correção da sintaxe SQL
        // Assumindo que a coluna 'nivel_acesso' existe e deve ser 'user'
        $sql = "INSERT INTO usuarios (nome, sobrenome, email, sexo, senha, nivel_acesso) VALUES (?, ?, ?, ?, ?, 'user')";
        
        if ($stmt = $link->prepare($sql)) {
            // "sssss" indica que são 5 strings (nome, sobrenome, email, sexo, senha_hash)
            $stmt->bind_param("sssss", $nome, $sobrenome, $email, $sexo, $senha_hash);

            if ($stmt->execute()) {
                $mensagem = "Usuário cadastrado com sucesso!";
                // Limpa os campos após cadastro
                $nome = $sobrenome = $email = $senha = '';
                $sexo = 'O';
            } else {
                $mensagem = "Erro ao cadastrar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensagem = "Erro na preparação da query: " . $link->error;
        }
    }
}

// Listagem de Usuários
$usuarios = [];
$sql_list = "SELECT id, nome, sobrenome, email, sexo, senha, data_cadastro FROM usuarios ORDER BY id ASC";
$result = $link->query($sql_list);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    $result->free();
} else {
    $mensagem .= "<br>Erro ao listar usuários: " . $link->error;
}

$link->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Cadastro de Usuário - Versão Corrigida</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    </head>
<body>
    <div class="container">
        <h1>Cadastro de Usuário</h1>
        
        <?php if ($mensagem !== ''): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="nome">Nome</label><br>
            <input id="nome" name="nome" type="text" value="<?php echo isset($nome) ? htmlspecialchars($nome, ENT_QUOTES) : ''; ?>" required><br><br>

            <label for="sobrenome">Sobrenome</label><br>
            <input id="sobrenome" name="sobrenome" type="text" value="<?php echo isset($sobrenome) ? htmlspecialchars($sobrenome, ENT_QUOTES) : ''; ?>" required><br><br>

            <label for="email">E-mail</label><br>
            <input id="email" name="email" type="email" value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES) : ''; ?>" required><br><br>

            <label for="senha">Senha</label><br>
            <input id="senha" name="senha" type="password" value="<?php echo isset($senha) ? htmlspecialchars($senha, ENT_QUOTES) : ''; ?>" required><br><br>
            
            <label for="sexo">Sexo</label><br>
            <select id="sexo" name="sexo" required>
                <option value="M" <?php echo (isset($sexo) && $sexo === 'M') ? 'selected' : ''; ?>>Masculino</option>
                <option value="F" <?php echo (isset($sexo) && $sexo === 'F') ? 'selected' : ''; ?>>Feminino</option>
                <option value="O" <?php echo (isset($sexo) && $sexo === 'O') ? 'selected' : ''; ?>>Outro</option>
            </select><br><br>

            <button type="submit">Cadastrar</button>
        </form>

        <hr>

        <h2>Usuários cadastrados</h2>
        <?php if (count($usuarios) === 0): ?>
            <p>Nenhum usuário cadastrado ainda.</p>
        <?php else: ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Sobrenome</th>
                        <th>Sexo</th>
                        <th>E-mail</th>
                        <th>Senha (Hash)</th>
                        <th>Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($u['nome'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($u['sobrenome'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($u['sexo'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($u['email'], ENT_QUOTES); ?></td>
                            <td style="font-size: 10px; max-width: 150px; overflow: hidden;"><?php echo htmlspecialchars($u['senha'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($u['data_cadastro'], ENT_QUOTES); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>