<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar os campos
    if (empty($username)) {
        $error_message = "Nome de usuário é obrigatório.";
    } elseif (empty($password)) {
        $error_message = "Senha é obrigatória.";
    } else {
        // Verificar se o usuário existe
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Usuário encontrado, verificar a senha
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Senha correta, redirecionar para welcome.html
                $_SESSION['username'] = $username;
                header("Location: welcome.html");
                exit();
            } else {
                $error_message = "Senha incorreta.";
            }
        } else {
            $error_message = "Usuário não está cadastrado.";
        }
        $stmt->close();
    }
    $conn->close();
} else {
    $error_message = "Método de requisição inválido.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-dialog-content">
        <h2>Erro de Login</h2>
        <p style="color: red;"><?php echo $error_message; ?></p>
        <a href="index.html">Voltar</a>
    </div>
</body>
</html>
