<?php
session_start();
require "conexao.php";

$usuario = $_POST['email'];
$senha   = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :senha LIMIT 1";
$stmt = $pdo->prepare($sql);

$stmt->bindValue(':usuario', $usuario);
$stmt->bindValue(':senha', $senha);

$stmt->execute();

if ($stmt->rowCount() === 1) {
    $_SESSION['logado'] = true;
    $_SESSION['usuario'] = $usuario;
    header("Location: painel.php");
    exit;
} else {
    $_SESSION['erro'] = "Usuário ou senha incorretos!";
    header("Location: login.php");
    exit;
}
?>
