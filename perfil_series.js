function submitSearchForm() {
    var inputSearch = document.getElementById('search-input').value; // Captura o valor do input

    // Redireciona para o perfil_series.php com o parâmetro de busca
    window.location.href = 'perfil_series.php?search=' + encodeURIComponent(inputSearch);
}
