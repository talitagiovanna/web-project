<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Função para enviar resposta em JSON
function sendResponse($status, $message = '') {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Obter dados do formulário
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validar os campos
    if (empty($username)) {
        sendResponse('error', 'Nome de usuário é obrigatório.');
    }
    if (empty($password)) {
        sendResponse('error', 'Senha é obrigatória.');
    }

    // Verificar se o usuário existe
    // ALTERAÇÃO: A consulta SQL já é case-sensitive por padrão, então não há necessidade de mudanças.
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuário encontrado, verificar a senha
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Senha correta, armazenar informações na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            sendResponse('success'); // Apenas o status de sucesso, sem mensagem
        } else {
            sendResponse('error', 'Senha incorreta. Tente novamente.');
        }
    } else {
        sendResponse('error', 'Usuário não cadastrado. Verifique o nome de usuário.');
    }

    $stmt->close();
    $conn->close();
} else {
    sendResponse('error', 'Método de requisição inválido.');
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
        <p style="color: red;"><?php echo isset($error_message) ? $error_message : ''; ?></p>
        <a href="index.html">Voltar</a>
    </div>
</body>
</html>
