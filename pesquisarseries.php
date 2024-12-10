<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Séries</title>
    <link rel="stylesheet" href="perfil_series.css">
    <script src="perfil_series.js"></script>
    <link rel="stylesheet" href="series.css">
    <style>
        /* Estilos do formulário e do resultado */
        .busca-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .busca-container input,
        .busca-container select {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            flex: 1;
        }

        .busca-container button {
            background-color: orange;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .busca-container button:hover {
            background-color: darkorange;
        }

        .resultado-container {
            margin-top: 20px;
        }

        .serie-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .serie-poster {
            width: 100px;
            height: 150px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }

        .serie-info {
            display: flex;
            flex-direction: column;
        }

        /* Adicione o hover para destacar o container da série */
        .serie-item:hover {
            background-color: #e6e6e6;
            cursor: pointer;
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
            <li>
                <a href="welcome-two.php">Início</a>
            </li>
            <li>
                <a href="perfil.php">Meu Perfil</a>
            </li>
            <li>
                <a href="series.html">Séries</a>
            </li>
            <li>
                <a href="pesquisarseries.php">Pesquisar</a>
            </li>
            <li>
                <a href="logout.php">Sair</a>
            </li>
        </ul>       
    </nav>

    <div class="container">
        <h1>Pesquisar Séries</h1>
        <div class="busca-container">
            <input type="text" id="nome-serie" placeholder="Nome da Série">
            <?php
            // Conexão com o banco de dados
            include('database.php');

            // Obter todos os gêneros únicos da tabela "series"
            $queryGeneros = "SELECT DISTINCT genero FROM series";
            $resultGeneros = $conn->query($queryGeneros);

            echo '<select id="genero">';
            echo '<option value="">Gênero</option>';
            if ($resultGeneros->num_rows > 0) {
                while ($row = $resultGeneros->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['genero']) . '">' . htmlspecialchars($row['genero']) . '</option>';
                }
            }
            echo '</select>';
            ?>
            <input type="text" id="ano-lancamento" placeholder="Ano de Lançamento">
            <button id="pesquisar-series">Buscar</button>
        </div>

        <div class="resultado-container" id="resultado-container" style="display: none;">
            <!-- Resultados da pesquisa serão exibidos aqui -->
        </div>
    </div>
    <div class="container">
    <h1>Pesquisar Amigos</h1>
    <div class="busca-container">
        <input type="text" id="nome-usuario" placeholder="Nome ou Username">
        <button id="pesquisar-amigos">Buscar</button>
    </div>

    <div class="resultado-container" id="resultado-amigos-container" style="display: none;">
        <!-- Resultados da pesquisa de amigos serão exibidos aqui -->
    </div>
</div>

    <script>
        document.getElementById('pesquisar-series').addEventListener('click', function () {
            const nomeSerie = document.getElementById('nome-serie').value;
            const genero = document.getElementById('genero').value;
            const anoLancamento = document.getElementById('ano-lancamento').value;

            // Criar uma requisição AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'pesquisar_series.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('resultado-container').style.display = 'block';
                    document.getElementById('resultado-container').innerHTML = xhr.responseText;

                    // Adicionar evento de clique em cada série-item
                    document.querySelectorAll(".serie-item").forEach(item => {
                        item.addEventListener("click", function () {
                            const serieId = this.dataset.id;
                            window.location.href = `perfil_series.php?id=${serieId}`;
                        });
                    });
                } else {
                    console.error('Erro na solicitação AJAX');
                }
            };


            // Enviar os dados para o PHP
            xhr.send(`nome=${encodeURIComponent(nomeSerie)}&genero=${encodeURIComponent(genero)}&ano=${encodeURIComponent(anoLancamento)}`);
        });

        document.getElementById('pesquisar-amigos').addEventListener('click', function () {
        const nomeUsuario = document.getElementById('nome-usuario').value;

        // Criar uma requisição AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'pesquisar_amigos.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('resultado-amigos-container').style.display = 'block';
                document.getElementById('resultado-amigos-container').innerHTML = xhr.responseText;

                // Adicionar ações se necessário
            } else {
                console.error('Erro na solicitação AJAX');
            }
        };

        // Enviar os dados para o PHP
        xhr.send(`nomeUsuario=${encodeURIComponent(nomeUsuario)}`);
    });

    document.getElementById('pesquisar-amigos').addEventListener('click', function () {
    const nomeUsuario = document.getElementById('nome-usuario').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'pesquisar_amigos.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('resultado-amigos-container').style.display = 'block';
            document.getElementById('resultado-amigos-container').innerHTML = xhr.responseText;

            // Adicionar evento de clique aos botões de seguir e deixar de seguir
            document.querySelectorAll('.seguir-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const userId = this.dataset.id;
                    const action = this.dataset.action;

                    // Enviar requisição AJAX para seguir ou deixar de seguir
                    const followXhr = new XMLHttpRequest();
                    followXhr.open('POST', 'pesquisar_amigos.php', true);
                    followXhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    followXhr.onload = function () {
                        if (followXhr.status === 200) {
                            // Atualizar o estado do botão dinamicamente
                            if (action === 'seguir') {
                                btn.innerText = 'Seguindo';
                                btn.dataset.action = 'deixar_de_seguir';
                                btn.classList.add('seguindo');
                            } else if (action === 'deixar_de_seguir') {
                                btn.innerText = 'Seguir';
                                btn.dataset.action = 'seguir';
                                btn.classList.remove('seguindo');
                            }
                        } else {
                            console.error('Erro ao atualizar o estado de seguimento');
                        }
                    };
                    followXhr.send(`action=${encodeURIComponent(action)}&seguido_id=${encodeURIComponent(userId)}`);
                });
            });
        } else {
            console.error('Erro na solicitação AJAX');
        }
    };

    xhr.send(`nomeUsuario=${encodeURIComponent(nomeUsuario)}`);
});
    </script>
</body>
</html>
