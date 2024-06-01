<?php
// Ativar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos de usuário e senha foram enviados
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Obtém os dados enviados pelo formulário
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Consulta o banco de dados para verificar o usuário
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o usuário existe no banco de dados
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Verifica se a senha está correta
            if (password_verify($password, $row['password'])) {
                // Credenciais corretas, retorna uma resposta de sucesso em JSON
                $response = array(
                    "success" => true,
                    "message" => "Login bem-sucedido!"
                );
            } else {
                // Senha incorreta, retorna uma resposta de erro em JSON
                $response = array(
                    "success" => false,
                    "message" => "Senha incorreta. Tente novamente."
                );
            }
        } else {
            // Usuário não encontrado, retorna uma resposta de erro em JSON
            $response = array(
                "success" => false,
                "message" => "Usuário não encontrado."
            );
        }

        $stmt->close();
    } else {
        // Campos de usuário e senha não foram enviados, retorna uma resposta de erro em JSON
        $response = array(
            "success" => false,
            "message" => "Campos de usuário e senha não foram fornecidos."
        );
    }
} else {
    // A solicitação não é do tipo POST, retorna uma resposta de erro em JSON
    $response = array(
        "success" => false,
        "message" => "A solicitação deve ser do tipo POST."
    );
}

// Retorna a resposta em formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>


