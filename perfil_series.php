<?php
include('database.php');

// Obter o termo de pesquisa da URL ou do formulário
$nome_serie = isset($_GET['search']) ? $_GET['search'] : '';

// Verificar se o campo de pesquisa não está vazio
if (!empty($nome_serie)) {
    // Consulta para buscar as informações da série pelo nome
    $query = "SELECT * FROM series WHERE nome LIKE ?";
    $stmt = $conn->prepare($query);
    $search_term = "%" . $nome_serie . "%";  // Adiciona os símbolos % para pesquisa parcial
    $stmt->bind_param("s", $search_term);  // Bind para string
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verificar se a série foi encontrada
    if ($result->num_rows > 0) {
        $serie = $result->fetch_assoc();

        // Lidar com o envio de um novo comentário
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comentario'])) {
            $comentario = $_POST['comentario'];

            // Validar que o comentário não esteja vazio
            if (!empty($comentario)) {
                // Inserir o comentário no banco de dados
                $query_comentario = "INSERT INTO comentarios (serie_id, comentario) VALUES (?, ?)";
                $stmt_comentario = $conn->prepare($query_comentario);
                $stmt_comentario->bind_param("is", $serie['id'], $comentario);
                $stmt_comentario->execute();
            } else {
                echo "Comentário não pode ser vazio!";
            }
        }

        // Consultar os comentários mais recentes
        $query_comentarios = "SELECT * FROM comentarios WHERE serie_id = ? ORDER BY data_comentario DESC";
        $stmt_comentarios = $conn->prepare($query_comentarios);
        $stmt_comentarios->bind_param("i", $serie['id']);
        $stmt_comentarios->execute();
        $comentarios = $stmt_comentarios->get_result();

        // Carregar o HTML com o conteúdo dinâmico
        $html = file_get_contents('perfil_series.html');

        // Substituir os marcadores pelo conteúdo da série
        $html = str_replace('{{NOME_SERIE}}', htmlspecialchars($serie['nome']), $html);
        $html = str_replace('{{IMAGEM_SERIE}}', htmlspecialchars($serie['imagem']), $html);
        $html = str_replace('{{GENERO_SERIE}}', htmlspecialchars($serie['genero']), $html);
        $html = str_replace('{{SINOPSE_SERIE}}', htmlspecialchars($serie['sinopse']), $html);
        $html = str_replace('{{ELOCO_SERIE}}', htmlspecialchars($serie['elenco']), $html);
        $html = str_replace('{{ANO_LANCAMENTO_SERIE}}', htmlspecialchars($serie['ano_lancamento']), $html);
        $html = str_replace('{{CLASSIFICACAO_SERIE}}', htmlspecialchars($serie['classificacao']), $html);
        $html = str_replace('{{ID_SERIE}}', $serie['id'], $html);

        // Substituir os comentários pela lista de comentários no HTML
        $comentarios_html = '';
        while ($comentario = $comentarios->fetch_assoc()) {
            $comentarios_html .= '<div class="comentario">';
            $comentarios_html .= '<p>' . htmlspecialchars($comentario['comentario']) . '</p>';
            $comentarios_html .= '<span class="data">' . date('d/m/Y H:i', strtotime($comentario['data_comentario'])) . '</span>';
            $comentarios_html .= '</div>';
        }

        $html = str_replace('{{COMENTARIOS_RECBIDOS}}', $comentarios_html, $html);

        // Formulário para inserção de novos comentários
        $html .= '
            <h3>Adicionar Comentário</h3>
            <form method="POST">
                <textarea name="comentario" placeholder="Digite seu comentário" required></textarea>
                <button type="submit">Enviar</button>
            </form>
        ';

        // Exibir a página com o conteúdo dinâmico
        echo $html;

    } else {
        echo "Série não encontrada!";
    }
} else {
    echo "Informe o nome de uma série para buscar.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
