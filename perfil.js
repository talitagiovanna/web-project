// Obter os botões e as seções
const atividadesBtn = document.getElementById('atividades-btn');
const seriesBtn = document.getElementById('series-btn');
const amigosBtn = document.getElementById('amigos-btn');
const listasBtn = document.getElementById('listas-btn');

const atividadesSection = document.getElementById('atividades-section');
const seriesSection = document.getElementById('series-section');
const amigosSection = document.getElementById('amigos-section');
const listasSection = document.getElementById('listas-section');

// Função para ativar a seção correta
function showSection(section) {
    // Resetar todas as seções
    atividadesSection.classList.remove('active');
    seriesSection.classList.remove('active');
    amigosSection.classList.remove('active');
    listasSection.classList.remove('active');

    // Mostrar a seção escolhida
    section.classList.add('active');
}

// Adicionar eventos de clique aos botões
atividadesBtn.addEventListener('click', () => showSection(atividadesSection));
seriesBtn.addEventListener('click', () => showSection(seriesSection));
amigosBtn.addEventListener('click', () => showSection(amigosSection));
listasBtn.addEventListener('click', () => showSection(listasSection));

const profileImg = document.getElementById('profile-img');  // Obtém a imagem de perfil
const profileUpload = document.getElementById('profile-photo');  // Obtém o input file escondido

// Função para carregar a imagem de perfil salva no localStorage, se houver
function loadProfileImage() {
    const storedImage = localStorage.getItem('profileImage');  // Obtém a imagem salva no localStorage
    if (storedImage) {
        profileImg.src = storedImage;  // Se houver uma imagem, define o src da imagem de perfil
    }
}

// Carregar a imagem ao carregar a página
window.addEventListener('load', loadProfileImage);

// Adiciona um ouvinte de evento para disparar o input ao clicar na imagem
profileImg.addEventListener('click', () => {
    profileUpload.click();  // Dispara o input file
});

// Adiciona um ouvinte para quando o usuário selecionar um arquivo
profileUpload.addEventListener('change', (event) => {
    const file = event.target.files[0];  // Obtém o arquivo selecionado

    if (file) {
        const reader = new FileReader();  // Cria um objeto FileReader

        reader.onload = function(e) {
            const imageUrl = e.target.result;
            profileImg.src = imageUrl;  // Atualiza a imagem de perfil com a nova imagem selecionada
            localStorage.setItem('profileImage', imageUrl);  // Salva a imagem no localStorage
        };

        reader.readAsDataURL(file);  // Lê o arquivo como URL de dados (base64)
    }
});

const modal = document.getElementById("edit-profile-modal");
    const btn = document.getElementById("edit-profile-btn");
    const span = document.getElementsByClassName("close-btn")[0];

    // Abrir o modal ao clicar no botão "Editar Perfil"
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Fechar o modal quando o usuário clicar no "x"
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Fechar o modal se o usuário clicar fora do modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function logout(event) {
        // Impede o comportamento padrão do link
        event.preventDefault();
    
        // Apaga todos os itens do localStorage
        localStorage.clear();
    
        // Redireciona o usuário manualmente após a limpeza
        window.location.href = 'index.html';
      }