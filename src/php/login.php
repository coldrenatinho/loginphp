<?php
require_once 'config.php';

if (isset($_POST['email']) && !empty($_POST['email'])) {
    // Lógica de login aqui
}
?>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <form method="POST" action="">
        <p><input type="text" placeholder="E-mail" name="email"></p>
        <p><input placeholder="Senha" name="senha" type="password"></p>
        <p><a href="">Esqueci minha senha</a></p>
        <p><input type="submit" value="Entrar">
        <p>
    </form>
</body>

</html>