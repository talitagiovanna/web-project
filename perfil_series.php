<?php
include('database.php');

$serieId = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($serieId)) {
    $query = "SELECT * FROM series WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $serieId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $serie = $result->fetch_assoc();

        $html = file_get_contents('perfil_series.html');

        $html = str_replace('{{NOME_SERIE}}', htmlspecialchars($serie['nome']), $html);
        $html = str_replace('{{IMAGEM_SERIE}}', htmlspecialchars($serie['poster_url']), $html);
        $html = str_replace('{{GENERO_SERIE}}', htmlspecialchars($serie['genero']), $html);
        $html = str_replace('{{SINOPSE_SERIE}}', htmlspecialchars($serie['sinopse']), $html);
        $html = str_replace('{{ELOCO_SERIE}}', htmlspecialchars($serie['elenco']), $html);
        $html = str_replace('{{ANO_LANCAMENTO_SERIE}}', htmlspecialchars($serie['ano_lancamento']), $html);
        $html = str_replace('{{ID_SERIE}}', $serie['id'], $html);

        $query_comentarios = "SELECT * FROM comentarios WHERE serie_id = ? ORDER BY data_comentario DESC";
        $stmt_comentarios = $conn->prepare($query_comentarios);
        $stmt_comentarios->bind_param("i", $serie['id']);
        $stmt_comentarios->execute();
        $comentarios = $stmt_comentarios->get_result();

        $comentarios_html = '';
        while ($comentario = $comentarios->fetch_assoc()) {
            $comentarios_html .= '<div class="comentario">';
            $comentarios_html .= '<p>' . htmlspecialchars($comentario['comentario']) . '</p>';
            $comentarios_html .= '<span class="data">' . date('d/m/Y H:i', strtotime($comentario['data_comentario'])) . '</span>';
            $comentarios_html .= '</div>';
        }

        $html = str_replace('{{COMENTARIOS_RECBIDOS}}', $comentarios_html, $html);
        echo $html;

    } else {
        echo "Série não encontrada!";
    }
} else {
    echo "ID da série não especificado!";
}

$conn->close();
?>
