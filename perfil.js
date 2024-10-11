document.getElementById('profile-img').addEventListener('click', function() {
    document.getElementById('profile-photo').click(); // Dispara o click no input do tipo file
});

document.getElementById('profile-photo').addEventListener('change', function(event) {
    const file = event.target.files[0]; // Pega o primeiro arquivo

    if (file) {
        const reader = new FileReader(); // Cria um leitor de arquivos

        reader.onload = function(e) {
            const imageElement = document.getElementById('profile-img'); // Seleciona a imagem de perfil
            imageElement.src = e.target.result; // Define o novo source para a imagem de perfil
        };

        reader.readAsDataURL(file); // Lê o arquivo como Data URL
    }
});
