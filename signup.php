<?php
// Ativar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar os campos
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }
    if (strlen($username) < 3) {
        die("Nome de usuário deve ter pelo menos 3 caracteres.");
    }
    if (strlen($password) < 6) {
        die("Senha deve ter pelo menos 6 caracteres.");
    }

    // Hash da senha
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Inserir no banco de dados
    $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $passwordHash);

    if ($stmt->execute()) {
        // Redirecionar para a nova página
        header("Location: welcome.html");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
