<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Série</title>
    <link rel="icon" href="assets/icon.png" type="image/x-icon">
    <style>
        @font-face {
            font-family: "Graphik Web Semibold Regular";
            src: url("https://db.onlinewebfonts.com/t/02fb003368979ba04752e4f3b4c4cebd.eot");
            src: url("https://db.onlinewebfonts.com/t/02fb003368979ba04752e4f3b4c4cebd.eot?#iefix")format("embedded-opentype"),
            url("https://db.onlinewebfonts.com/t/02fb003368979ba04752e4f3b4c4cebd.woff2")format("woff2"),
            url("https://db.onlinewebfonts.com/t/02fb003368979ba04752e4f3b4c4cebd.woff")format("woff"),
            url("https://db.onlinewebfonts.com/t/02fb003368979ba04752e4f3b4c4cebd.ttf")format("truetype"),
            url("https://db.onlinewebfonts.com/t/02fb003368979ba04752e4f3b4c4cebd.svg#Graphik Web Semibold Regular")format("svg");
        }

        @font-face {
            font-family: "Tiempos Headline Bold";
            src: url("https://db.onlinewebfonts.com/t/74613f6f784f2e332b85076579141743.eot");
            src: url("https://db.onlinewebfonts.com/t/74613f6f784f2e332b85076579141743.eot?#iefix")format("embedded-opentype"),
            url("https://db.onlinewebfonts.com/t/74613f6f784f2e332b85076579141743.woff2")format("woff2"),
            url("https://db.onlinewebfonts.com/t/74613f6f784f2e332b85076579141743.woff")format("woff"),
            url("https://db.onlinewebfonts.com/t/74613f6f784f2e332b85076579141743.ttf")format("truetype"),
            url("https://db.onlinewebfonts.com/t/74613f6f784f2e332b85076579141743.svg#Tiempos Headline Bold")format("svg");
        }

        body {
            font-family: "Graphik Web Semibold Regular";
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Impede a rolagem horizontal */
            background-color:rgb(0, 0, 0);
        }

        /* Navbar */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background: none; /* Sem cor de fundo */
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
        }

        .navbar .logo svg {
            height: 26px; /* Altura ajustada para combinar com o tamanho da fonte */
            margin-right: 10px;
        }

        .navbar .logo span {
            color: rgb(255, 255, 255);
            font-size: 24px;
            font-weight: bold;
            font-family: "Graphik Web Semibold Regular";
        }

        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0 auto; /* Centraliza horizontalmente */
            padding: 0;
            justify-content: center; /* Centraliza os links dentro do ul */
        }

        .navbar ul li {
            margin: 0 10px;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
        }

        .navbar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .navbar .search-bar {
            position: relative;
            flex: 1; /* Expande para ocupar o espaço restante */
            max-width: 100px; /* Largura máxima para desktop */
            margin-right: 150px;
        }

        .navbar .search-bar input {
            padding: 5px 30px 5px 10px; /* Reduzido o padding para ocupar menos espaço */
            width: 100%; /* Ocupa toda a largura do container */
            border-radius: 20px;
            border: 0px solid whitesmoke;
            background-color: transparent; /* Campo de pesquisa inicialmente transparente */
            transition: background-color 0.3s ease; /* Transição suave para o hover */
        }

        .navbar .search-bar input:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Cor de fundo branca com transparência ao passar o mouse */
        }

        .navbar .search-icon {
            position: absolute;
            top: 50%;
            right: -35px; /* Ajuste a posição do ícone */
            transform: translateY(-50%);
            width: 20px; /* Largura do ícone */
            height: 20px; /* Altura do ícone */
            background-image: url('search-icon.png'); /* Caminho para o ícone PNG */
            background-size: cover; /* Ajusta o tamanho do ícone */
            cursor: pointer; /* Transforma o cursor em uma mãozinha ao passar sobre o ícone */
        }
        /* Estilos do cabeçalho */
        header {
            background: url('assets/one-piece.jpg') no-repeat center center/cover;
            color: #fff;
            padding: 10rem 0;
            text-align: center;
            position: relative;
        }

        header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/one-piece.webp');
            background-size: contain; /* Exibir a imagem inteira */
            background-position: center; /* Centralizar a imagem */
            z-index: -1; /* Enviar para trás do conteúdo */
        }

        header h1, header p {
            position: relative;
            z-index: 2;
        }

        /* Estilos do conteúdo principal */
        main {
            padding: 2rem;
        }

        main section {
            margin-bottom: 2rem;
        }

        main h2 {
            margin-bottom: 1rem;
            color: #333;
        }
        /* Estilo geral do formulário */
        .form-section.form-assistido {
            background-color: rgba(255, 255, 255, 0.1); /* Fundo semi-transparente */
            padding: 2rem;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Títulos */
        .form-section.form-assistido h2 {
            color: #fff;
            font-family: "Tiempos Headline Bold";
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        /* Estilos dos labels */
        .form-section.form-assistido label {
            display: block;
            color: #fff;
            font-weight: bold;
            margin-top: 1rem;
            font-family: "Graphik Web Semibold Regular";
        }

        /* Campos de entrada */
        .form-section.form-assistido input[type="date"],
        .form-section.form-assistido input[type="number"],
        .form-section.form-assistido select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2); /* Fundo semi-transparente */
            color: #fff;
            font-family: "Graphik Web Semibold Regular";
            box-sizing: border-box; /* Garante o mesmo tamanho com bordas e paddings */
        }

        .form-section.form-assistido input[type="date"]::placeholder,
        .form-section.form-assistido input[type="number"]::placeholder,
        .form-section.form-assistido select option {
            color: #999; /* Placeholder com tom suave */
        }

        /* Botão de submissão */
        .form-section.form-assistido button[type="submit"] {
            width: 100%;
            padding: 0.8rem;
            margin-top: 1.5rem;
            background-color: orange;
            color: #000;
            font-family: "Tiempos Headline Bold";
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-section.form-assistido button[type="submit"]:hover {
            background-color: #ffa500; /* Tom mais claro ao passar o mouse */
        }

        /* Espaçamento entre elementos */
        .form-section.form-assistido form label + input,
        .form-section.form-assistido form label + select,
        .form-section.form-assistido form select + label {
            margin-top: 1rem;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .form-section.form-assistido {
                padding: 1.5rem;
            }

            .form-section.form-assistido h2 {
                font-size: 1.6rem;
            }
        }

    </style>
</head>
<body>
    <!-- Menu de navegação -->
    <nav class="navbar">
        <div class="logo">
            <svg viewBox="0 0 640 512" fill="orange" xmlns="http://www.w3.org/2000/svg"><path d="M206.9 245.1C171 255.6 146.8 286.4 149.3 319.3C160.7 306.5 178.1 295.5 199.3 288.4L206.9 245.1zM95.78 294.9L64.11 115.5C63.74 113.9 64.37 112.9 64.37 112.9c57.75-32.13 123.1-48.99 189-48.99c1.625 0 3.113 .0745 4.738 .0745c13.1-13.5 31.75-22.75 51.62-26c18.87-3 38.12-4.5 57.25-5.25c-9.999-14-24.47-24.27-41.84-27.02c-23.87-3.875-47.9-5.732-71.77-5.732c-76.74 0-152.4 19.45-220.1 57.07C9.021 70.57-3.853 98.5 1.021 126.6L32.77 306c14.25 80.5 136.3 142 204.5 142c3.625 0 6.777-.2979 10.03-.6729c-13.5-17.13-28.1-40.5-39.5-67.63C160.1 366.8 101.7 328 95.78 294.9zM193.4 157.6C192.6 153.4 191.1 149.7 189.3 146.2c-8.249 8.875-20.62 15.75-35.25 18.37c-14.62 2.5-28.75 .376-39.5-5.249c-.5 4-.6249 7.998 .125 12.12c3.75 21.75 24.5 36.24 46.25 32.37C182.6 200.1 197.3 179.3 193.4 157.6zM606.8 121c-88.87-49.38-191.4-67.38-291.9-51.38C287.5 73.1 265.8 95.85 260.8 123.1L229 303.5c-15.37 87.13 95.33 196.3 158.3 207.3c62.1 11.13 204.5-53.68 219.9-140.8l31.75-179.5C643.9 162.3 631 134.4 606.8 121zM333.5 217.8c3.875-21.75 24.62-36.25 46.37-32.37c21.75 3.75 36.25 24.49 32.5 46.12c-.7499 4.125-2.25 7.873-4.125 11.5c-8.249-9-20.62-15.75-35.25-18.37c-14.75-2.625-28.75-.3759-39.5 5.124C332.1 225.9 332.9 221.9 333.5 217.8zM403.1 416.5c-55.62-9.875-93.49-59.23-88.99-112.1c20.62 25.63 56.25 46.24 99.49 53.87c43.25 7.625 83.74 .3781 111.9-16.62C512.2 392.7 459.7 426.3 403.1 416.5zM534.4 265.2c-8.249-8.875-20.75-15.75-35.37-18.37c-14.62-2.5-28.62-.3759-39.5 5.249c-.5-4-.625-7.998 .125-12.12c3.875-21.75 24.62-36.25 46.37-32.37c21.75 3.875 36.25 24.49 32.37 46.24C537.6 257.9 536.1 261.7 534.4 265.2z"/></svg>
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

