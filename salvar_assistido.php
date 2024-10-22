<?php
// Iniciar a conexão com o banco de dados
$servername = "localhost";
$username = "root"; 
$password = "ROOT";
$dbname = "myserieslist";

// Conectando ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializa a variável para a mensagem
$mensagem = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebendo os dados do formulário
    $serie = $_POST['serie'];
    $data = $_POST['data'];
    $avaliacao = $_POST['avaliacao'];

    // Validação básica (caso necessário)
    if (empty($serie) || empty($data) || empty($avaliacao)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        // Inserindo os dados no banco de dados
        $sql = "INSERT INTO series_assistidas (serie, data_assistida, avaliacao) VALUES ('$serie', '$data', '$avaliacao')";

        if ($conn->query($sql) === TRUE) {
            $mensagem = "Série cadastrada com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar série: " . $conn->error;
        }
    }
}

// Fechando a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar como Assistido - MySeriesList</title>
    <link rel="icon" href="assets/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="series.css">
</head>
<body>
    <!-- Menu de navegação -->
    <nav class="navbar">
        <div class="logo">
            <svg viewBox="0 0 640 512" fill="orange" xmlns="http://www.w3.org/2000/svg"><path d="..."/></svg>
            <span>My Series List</span>
        </div>
        <ul>
            <li><a href="welcome-two.html">Início</a></li>
            <li><a href="perfil.html">Meu Perfil</a></li>
            <li><a href="series.html">Séries</a></li>
            <li><a href="index.html">Sair</a></li>
        </ul>
        <div class="search-bar">
            <form action="perfil_series.php" method="GET" id="search-form">
                <input type="text" name="search" required id="search-input" placeholder="Pesquisar">
                <button type="submit" style="display: none;"></button>
                <svg class="search-icon" fill="#fff" onclick="submitSearchForm()"><path d="..."/></svg>
            </form>
        </div>
    </nav>

    <!-- Cabeçalho -->
    <header>
        <h1>Cadastrar como Assistido</h1>
        <p>Selecione as séries que você já assistiu e cadastre aqui.</p>
    </header>

    <!-- Formulário para cadastrar série como assistida -->
    <main>
        <section class="form-section">
            <h2>Selecione a Série Assistida</h2>
            <form action="" method="POST">
                <label for="serie">Escolha a Série:</label>
                <select name="serie" id="serie" required>
                    <option value="">Selecione uma série</option>
                    <option value="serie1">Série 1</option>
                    <option value="serie2">Série 2</option>
                    <!-- Adicionar mais opções conforme as séries disponíveis no seu banco de dados -->
                </select>

                <label for="data">Data que terminou de assistir:</label>
                <input type="date" name="data" id="data" required>

                <label for="avaliacao">Avalie a série:</label>
                <input type="number" name="avaliacao" id="avaliacao" min="1" max="10" required>

                <button type="submit">Cadastrar</button>
            </form>

            <!-- Mensagem de feedback -->
            <?php if (!empty($mensagem)): ?>
                <div class="feedback-message">
                    <p><?php echo htmlspecialchars($mensagem); ?></p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2023 Meu Site. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
