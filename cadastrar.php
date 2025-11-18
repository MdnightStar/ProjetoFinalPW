<?php
    include("conexao.php");

    $msg='';
    if(isset($_POST['submit'])){
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $tipo_usuario = $_POST['tipo_usuario'];
        $senha = $_POST['senha'];
        $csenha = $_POST['csenha'];

        if($senha !== $csenha){
            $msg="As senhas não coincidem ):<";
        }
        else{
            $select = $pdo -> prepare("SELECT*FROM usuario WHERE email = :email");
            $select -> execute(['email' => $email]);

            if($select->rowCount()>0){
                $msg ="Email já cadastrado";
                echo($msg);
            }
            else{
                $insert = $pdo->prepare("INSERT INTO usuario(nome,email,senha,tipo_usuario) 
                VALUES(:nome,:email,:senha,:tipo_usuario)");

                $insert -> execute([
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => $senha,
                    'tipo_usuario' => $tipo_usuario
                ]);
                $msg="Registrado com sucesso!";
                header("Location: login.php");
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
            <h2>Registre-se</h2>
            <p class="msg"></p>
            <div class="form-group">
                <input type="text" name="nome" placeholder="Insira seu nome" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Insira seu email" class="form-control" required>
            </div>
            <div class="form-group">
                <select name="tipo_usuario" id="" class="form-control">
                    <option value="aluno">Aluno</option>
                    <option value="professor">Professor</option>
                </select>
            </div>
            <div class="form-group">
                <input type="password" name="senha" placeholder="Insira sua senha" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" name="csenha" placeholder="Confirme sua senha" class="form-control" required>
            </div>
            <button class="btn font-weight-bold" name="submit">Registre-se agora</button>
            <p>Já possui uma conta? <a href="login.php">Faça login :></a></p>
            <p class="text-primary">Teste bootstrap</p>

        </form>
    </div>
</body>
</html>