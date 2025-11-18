<?php
require "conexao.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stmt = $conn->prepare("INSERT INTO provas (month, number, context, date) VALUES (?,?,?,?)");
    $stmt->execute([$_POST['month'], $_POST['eventNumber'], $_POST['eventInfo'], $_POST['eventDate']]);
    header("Location: agenda.php");
    exit;
}

$month = $_GET['month'] ?? '';
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
    <h3>Adicionar Prova - <?= $month ?></h3>
    <form method="post">
        <input type="hidden" name="month" value="<?= $month ?>">

        <label>Número da avaliação:</label>
        <input type="number" name="eventNumber" class="form-control mb-2" required>

        <label>Conteúdo:</label>
        <textarea name="eventInfo" class="form-control mb-2" required></textarea>

        <label>Data:</label>
        <input type="date" name="eventDate" class="form-control mb-3" required>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="agenda.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>
</body>
</html>