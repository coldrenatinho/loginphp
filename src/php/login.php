<?php
require_once 'config.php';

$erro = []; 

if (isset($_POST['email']) && !empty($_POST['email'])) {

    if(!isset($_SESSION))
        session_start();


    $_SESSION['email'] = $link->escape_string($_POST['email']);
    
    $sql_code = "SELECT senha, id FROM usuarios WHERE email='" . $_SESSION['email'] . "'";
    $mysqli_query = $link->query($sql_code) or die($link->error);
    $dado = $mysqli_query->fetch_assoc();
    $total = $mysqli_query->num_rows;

    if ($total == 0 ) {
        $erro[] = "Email não cadastrado.";
    } else {
        // Use password_verify for secure password checking
        if($_POST['senha'] == $dado['senha']) {
            $_SESSION['usuario'] = $dado['id'];
        } else{
            $erro[] = "Senha inválida.";
        }
    }

    if(count($erro) == 0 && isset($_SESSION['usuario'])){
        echo "<script>alert('Login realizado com sucesso!');location.href='suseco.php';</script>";
        exit();
    }

} else {
    if(!isset($_SESSION))
        session_start();
}

?>
<html>
<head>
    <title>Login PHP</title>
    <meta charset="utf-8">
</head>
<body>
    <div>
    <?php 
    if(!empty($erro)) {
        foreach($erro as $msg){
            echo "<p>$msg</p>";
        }
    }
    ?>
    </div>
    <form method="POST" action="">
        <p><input value="<?php echo $_SESSION['email'] ?? ''; ?>" name="email" placeholder="Email" type="text"></p>
        <p><input placeholder="Senha" name="senha" type="password"></p>
        <p><a href="">Esqueci minha senha</a></p>
        <p><input type="submit" value="Entrar">
        <p>
    </form>
    <div class="debug">
        <h2>Debug</h2>
        <div>
           <?php 
               if (isset($_SESSION)) {
                   echo "<pre>";
                   print_r($_SESSION);
                   echo "</pre>";
               }
              ?> 
        </div>
        <div>
            <?php
                echo "<pre>";
                print_r($_POST);
                echo "</pre>";
            ?>
        </div>
    </div>
</body>

</html>