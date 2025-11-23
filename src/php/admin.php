<?php
include_once 'protect.php';
protect();
?>
<html>
<head>
    <title>Administração</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Área Administrativa</h1>
    <p>Bem-vindo, usuário <?php echo $_SESSION['usuario']; ?>!</p>
    <a href="logout.php">Sair</a>
</body>
</html>