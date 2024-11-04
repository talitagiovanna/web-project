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
            <li><a href="pesquisarseries.php">Pesquisar</a></li>
            <li><a href="index.html">Sair</a></li>
        </ul>
    </nav>

    <!-- Cabeçalho -->
    <header>
        <h1>Cadastrar como Assistido</h1>
        <p>Selecione as séries que você já assistiu e cadastre aqui.</p>
    </header>

    <!-- Formulário para cadastrar série como assistida -->
    <main>
        <section class="form-section form-assistido">
            <h2>Selecione a Série Assistida</h2>
            <form action="salvar_assistido.php" method="POST">
                <label for="serie">Escolha a Série:</label>
                <select name="serie" id="serie" required>
                    <option value="">Selecione uma série</option>
                    <?php
                    // Conecte-se ao banco de dados
                    require 'database.php';
                    
                    // Execute a consulta para buscar as séries
                    $query = "SELECT id, nome FROM series";
                    $result = $conn->query($query);

                    // Verifique se existem séries no banco de dados
                    if ($result->num_rows > 0) {
                        // Exibe cada série como uma opção no dropdown
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhuma série disponível</option>";
                    }
                    ?>
                </select>

                <label for="data_assistida">Data que terminou de assistir:</label>
                <input type="date" name="data_assistida" id="data_assistida" required>

                <label for="avaliacao">Avalie a série:</label>
                <input type="number" name="avaliacao" id="avaliacao" min="1" max="10" required>

                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2023 Meu Site. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Aqui você pode adicionar funcionalidades extras, como a validação dos campos do formulário
    </script>
</body>
</html>

