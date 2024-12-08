<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id']; // ID do usuário, que pode vir da sessão após o login

// Consulta para pegar o username e name do usuário
$sql = "SELECT username, name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $name);
$stmt->fetch();
$stmt->close();

// Verifica se o nome está definido (não é null), caso contrário usa o username
$user_display_name = $name ? $name : $username;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>&lrm;Welcome to My Series List &bull; My Series List</title>
    <link rel="icon" href="assets/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <style>
        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0 auto;
            padding: 0;
            justify-content: center;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <svg viewBox="0 0 640 512" fill="orange" xmlns="http://www.w3.org/2000/svg"><path d="M206.9 245.1C171 255.6 146.8 286.4 149.3 319.3C160.7 306.5 178.1 295.5 199.3 288.4L206.9 245.1zM95.78 294.9L64.11 115.5C63.74 113.9 64.37 112.9 64.37 112.9c57.75-32.13 123.1-48.99 189-48.99c1.625 0 3.113 .0745 4.738 .0745c13.1-13.5 31.75-22.75 51.62-26c18.87-3 38.12-4.5 57.25-5.25c-9.999-14-24.47-24.27-41.84-27.02c-23.87-3.875-47.9-5.732-71.77-5.732c-76.74 0-152.4 19.45-220.1 57.07C9.021 70.57-3.853 98.5 1.021 126.6L32.77 306c14.25 80.5 136.3 142 204.5 142c3.625 0 6.777-.2979 10.03-.6729c-13.5-17.13-28.1-40.5-39.5-67.63C160.1 366.8 101.7 328 95.78 294.9zM193.4 157.6C192.6 153.4 191.1 149.7 189.3 146.2c-8.249 8.875-20.62 15.75-35.25 18.37c-14.62 2.5-28.75 .376-39.5-5.249c-.5 4-.6249 7.998 .125 12.12c3.75 21.75 24.5 36.24 46.25 32.37C182.6 200.1 197.3 179.3 193.4 157.6zM606.8 121c-88.87-49.38-191.4-67.38-291.9-51.38C287.5 73.1 265.8 95.85 260.8 123.1L229 303.5c-15.37 87.13 95.33 196.3 158.3 207.3c62.1 11.13 204.5-53.68 219.9-140.8l31.75-179.5C643.9 162.3 631 134.4 606.8 121zM333.5 217.8c3.875-21.75 24.62-36.25 46.37-32.37c21.75 3.75 36.25 24.49 32.5 46.12c-.7499 4.125-2.25 7.873-4.125 11.5c-8.249-9-20.62-15.75-35.25-18.37c-14.75-2.625-28.75-.3759-39.5 5.124C332.1 225.9 332.9 221.9 333.5 217.8zM403.1 416.5c-55.62-9.875-93.49-59.23-88.99-112.1c20.62 25.63 56.25 46.24 99.49 53.87c43.25 7.625 83.74 .3781 111.9-16.62C512.2 392.7 459.7 426.3 403.1 416.5zM534.4 265.2c-8.249-8.875-20.75-15.75-35.37-18.37c-14.62-2.5-28.62-.3759-39.5 5.249c-.5-4-.625-7.998 .125-12.12c3.875-21.75 24.62-36.25 46.37-32.37c21.75 3.875 36.25 24.49 32.37 46.24C537.6 257.9 536.1 261.7 534.4 265.2z"/></svg>
            <span>My Series List</span>
        </div>
        <ul>
            <li><a href="welcome-two.php">Início</a></li>
            <li><a href="perfil.php">Meu Perfil</a></li>
            <li><a href="series.html">Séries</a></li>
            <li><a href="pesquisarseries.php">Pesquisar</a></li>
            <li><a href="logout.php">Sair</a></li>
            <li class="dropdown">
                <a href="#" id="notification-toggle">Notificações (<span id="notification-count">0</span>)</a>
                <div id="notification-menu" class="dropdown-menu">
                    <!-- Lista de notificações será carregada aqui -->
                    <p>Sem notificações novas.</p>
                </div>
            </li>
        </ul>       
    </nav>

    <header class="header">
        <img src="assets/header-image3.jpg" alt="Header Image">
        <a href="#"class="image-title">
            <span class="vertical-text" style="transform: translateY(-1100%) translateX(181%) rotate(-90deg);">My Vampire Diaries: 8º temporada (2017)</span>
        </a>
        <div class="overlay-text" style="bottom: 20%; font-size: 35px;">
            Bem vindo de volta, <a href="perfil.php" style="color: white;"><?php echo $user_display_name; ?></a>.
            <br>
            <div class="small-text" style="font-size: 16px; font-family: Graphik Web Semibold Regular; color: white">
                Esta página inicial será personalizada à medida que você seguir membros ativos no My Series List.
            </div>
        </div>
    </header>

    <section class="series-section">
        <h2 style="color: orange;">NOVO NO MY SERIES LIST</h2>
        <div class="series-grid">
            <div class="series-item">
                <img src="assets/serie1.jpg" alt="Série 1">
                <div class="series-info">
                    <p>Lock & Key</p>
                    <p>Gênero: Fantasia, Aventura, Drama, Sobrenatural</p>
                    <p>Ano: 2020-2022</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/serie2.jpg" alt="Série 2">
                <div class="series-info">
                    <p>Eu Nunca...</p>
                    <p>Gênero: Comédia, Drama</p>
                    <p>Ano: 2020-2023</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/serie3.jpg" alt="Série 3">
                <div class="series-info">
                    <p>De Volta aos 15</p>
                    <p>Gênero: Drama, Ficção Científica</p>
                    <p>Ano: 2022-Presente</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/serie4.jpg" alt="Série 4">
                <div class="series-info">
                    <p>O Silo</p>
                    <p>Gênero: Ficção, Distopia, Drama</p>
                    <p>Ano: 2023-Presente</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/serie5.jpg" alt="Série 5">
                <div class="series-info">
                    <p>O Mandaloriano</p>
                    <p>Gênero: Ação, Aventura</p>
                    <p>Ano: 2019-Presente</p>
                </div>
            </div>
        </div>
    </section>

    <section class="popular-section">
        <h2 style="color: orange;">POPULAR NO MY SERIES LIST</h2>
        <div class="series-grid">
            <div class="series-item">
                <img src="assets/popular1.jpg" alt="Popular 1">
                <div class="series-info">
                    <p>The Atypical Family</p>
                    <p>Gênero: Romance, Fantasia, Psicológico</p>
                    <p>Ano: 2024</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/popular2.jpg" alt="Popular 2">
                <div class="series-info">
                    <p>Queen of Tears</p>
                    <p>Gênero: Comédia, Romance, Drama</p>
                    <p>Ano: 2024</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/popular3.jpg" alt="Popular 3">
                <div class="series-info">
                    <p>Um Dia</p>
                    <p>Gênero: Romance, Drama</p>
                    <p>Ano: 2024</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/popular4.jpg" alt="Popular 4">
                <div class="series-info">
                    <p>Night Has Come</p>
                    <p>Gênero: Thriller, Mistério, Horror</p>
                    <p>Ano: 2023</p>
                </div>
            </div>
            <div class="series-item">
                <img src="assets/popular5.jpg" alt="Popular 5">
                <div class="series-info">
                    <p>Daily Dose of Sunshine</p>
                    <p>Gênero: Drama, Médico, Vida</p>
                    <p>Ano: 2023</p>
                </div>
            </div>
        </div>
    </section>
    
    

    <footer class="footer">
        <p>&copy; 2024 MySeriesList Limited. Todos os direitos reservados.</p>
    </footer>
</body>