<?php
    include("conexao.php");
    session_start();
    $msg='';
    if(isset($_POST['submit'])){
         $email = $_POST['email'];
         $senha = $_POST['senha'];

         $select = $pdo -> prepare("SELECT*FROM usuario WHERE email = :email AND senha = :senha");
         $select -> execute(['email' => $email,'senha' => $senha]);

         if($select->rowCount()>0){
            $row = $select ->fetch(PDO::FETCH_ASSOC);

            $_SESSION['email'] = $row['email'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['tipo'] = $row['tipo_usuario'];

            if($row['tipo_usuario'] == 'aluno'){
                $_SESSION['user'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                header("Location: index.php");
                exit;
            }
            elseif($row['tipo_usuario'] == 'professor'){
                $_SESSION['professor'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                header("Location: professor.php");
                exit;
            }
            else{
                $msg="Email e senha incorretos!";
            }
         }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <div class="form">  
        <form action="" method="post">
            <h2>Bem-vindo</h2>
            <p class="msg"></p>
            
            <div class="form-group">
                <input type="email" name="email" placeholder="Insira seu email" class="form-control" required>
            </div>
        
            <div class="form-group">
                <input type="password" name="senha" placeholder="Insira sua senha" class="form-control" required>
            </div>

            <button class="btn font-weight-bold" name="submit">Entrar agora</button>
            <p>Não possui uma conta? <a href="cadastrar.php">Registre-se agora :></a></p>
            
        </form>
    </div>
</body>
</html>