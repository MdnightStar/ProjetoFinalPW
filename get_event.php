<?php
require "conexao.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID não enviado'
    ]);
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM provas WHERE idProva = ?");
$stmt->execute([$id]);
$ev = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => $ev ? true : false,
    'event' => $ev
]);
?>
