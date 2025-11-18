
<?php
$host = '127.0.0.1';
$db = 'testeorganizer'; // O nome do seu banco de dados
$user = 'root'; // Usuário padrão do XAMPP
$pass = ''; // Senha padrão (vazia)
    try {
    // A linha de conexão
    $pdo = new PDO("mysql:host=$host;port= 3308;dbname=$db;charset=utf8", $user,$pass);
    //echo "Conexao realizada com sucesso";
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem
    die("Erro na conexão: " . $e->getMessage());
    }
?>