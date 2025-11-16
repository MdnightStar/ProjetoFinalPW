<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 350px;">

        <h3 class="text-center mb-3">Login</h3>

        <?php
        if (isset($_SESSION['erro'])) {
            echo '<div class="alert alert-danger text-center">'.$_SESSION['erro'].'</div>';
            unset($_SESSION['erro']);
        }
        ?>

        <form method="POST" action="autenticar.php">
            <div class="mb-3">
                <label class="form-label">Usuário</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>

</body>
</html>
