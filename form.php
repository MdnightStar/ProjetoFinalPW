<?php
require "conexao.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stmt = $conn->prepare("INSERT INTO provas (mes, numero, conteudo, data) VALUES (?,?,?,?)");
    $stmt->execute([$_POST['mes'], $_POST['numeroEvento'], $_POST['infoEvento'], $_POST['dataEvento']]);
    header("Location: agenda.php");
    exit;
}

$mes = $_GET['mes'] ?? '';
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8"/>
<title>Adicionar Prova</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="card p-4">
    <h3>Adicionar Prova - <?= $mes ?></h3>
    <form method="post">
        <input type="hidden" name="mes" value="<?= $mes ?>">

        <label>Número da avaliação:</label>
        <input type="number" name="numeroEvento" class="form-control mb-2" required>

        <label>Conteúdo:</label>
        <textarea name="infoEvento" class="form-control mb-2" required></textarea>

        <label>Data:</label>
        <input type="date" name="dataEvento" class="form-control mb-3" required>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="agenda.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>
</body>
</html>