<?php
// Ativar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário e sanitizar
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

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

    // Verificar se o e-mail já está cadastrado
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($emailCount);
    $stmt->fetch();
    if ($emailCount > 0) {
        die("E-mail já cadastrado. Por favor, forneça um novo e-mail.");
    }
    $stmt->close();

    // Verificar se o nome de usuário já está cadastrado
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($usernameCount);
    $stmt->fetch();
    if ($usernameCount > 0) {
        die("Usuário já cadastrado. Por favor, escolha um nome de usuário diferente.");
    }
    $stmt->close();

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
        echo "Erro ao criar a conta: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
