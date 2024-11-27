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

      //=================================================================================

// Funcionalidade do modal
const modalseries = document.getElementById('modalseries');
const closeBtn = document.getElementById('close-btn');
const editForm = document.getElementById('edit-form');
const serieIdInput = document.getElementById('serie_id');
const avaliacaoInput = document.getElementById('avaliacao');
const dataAssistidaInput = document.getElementById('data_assistida');

// Função para abrir o modal e preencher os dados da série
function openModal(serieId) {
    // Envia um pedido GET para obter as informações da série
    fetch(`get_series_info.php?id=${serieId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                // Preenche os dados do formulário
                serieIdInput.value = data.serie_id;
                avaliacaoInput.value = data.avaliacao;
                dataAssistidaInput.value = data.data_assistida;

                // Exibe o modal
                modalseries.style.display = 'flex';
            }
        })
        .catch(error => console.error('Erro ao buscar dados da série:', error));
}

// Fecha o modal
closeBtn.addEventListener('click', () => {
    modalseries.style.display = 'none';
});

// Quando o formulário for enviado, envia os dados para atualizar
editForm.addEventListener('submit', (e) => {
    e.preventDefault();

    // Envia os dados via POST para atualização
    fetch('get_series_info.php', {
        method: 'POST',
        body: new FormData(editForm),
    })
    .then(response => response.text())
    .then(message => {
        alert(message);
        modalseries.style.display = 'none'; // Fecha o modal após a atualização
    })
    .catch(error => console.error('Erro ao atualizar série:', error));
});

      //===================================================================================


document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('menu-btn');
    const menuLateral = document.getElementById('menu-lateral');
    const listasContainer = document.getElementById('listas-container');
    const formCriarLista = document.getElementById('form-criar-lista');

    if (!menuBtn || !menuLateral || !listasContainer || !formCriarLista) {
        console.error('Um ou mais elementos necessários não foram encontrados!');
        return;
    }

    menuBtn.addEventListener('click', () => {
        menuLateral.classList.toggle('ativo');
    });

    // Função para exibir mensagens
    function exibirMensagem(tipo, texto) {
        const messageBox = document.createElement('div');
        messageBox.className = 'message-box ' + tipo;
        messageBox.textContent = texto;
        document.body.appendChild(messageBox);

        setTimeout(() => {
            messageBox.style.opacity = 0;
            setTimeout(() => {
                messageBox.remove();
            }, 500);
        }, 3000);
    }

    // Função para carregar listas e séries
    function fetchListas() {
        console.log('Carregando listas...');
        fetch('listas.php?action=fetch')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na resposta do servidor: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dados recebidos:', data); // Verifique os dados aqui
                if (!data.listas || !data.series) {
                    throw new Error('Dados inválidos recebidos.');
                }
    
                listasContainer.innerHTML = ''; // Limpa o container
    
                data.listas.forEach(item => {
                    const { lista, series: seriesAssociadas } = item;
    
                    const listaDiv = document.createElement('div');
                    listaDiv.classList.add('lista');
    
                    const listaTitulo = document.createElement('h4');
                    listaTitulo.textContent = lista.nome;
                    listaDiv.appendChild(listaTitulo);
    
                    const hamburguerBtn = document.createElement('button');
                    hamburguerBtn.classList.add('hamburguer-listas');
                    hamburguerBtn.innerHTML = '&#9776;';
                    listaDiv.appendChild(hamburguerBtn);
    
                    const menuOptions = document.createElement('div');
                    menuOptions.classList.add('menu-opcoes');
                    menuOptions.innerHTML = `
                        <button class="editar-nome">Editar Nome</button>
                        <button class="editar-series">Editar Séries</button>
                        <button class="apagar-lista">Apagar Lista</button>
                    `;
                    menuOptions.style.display = 'none';
                    listaDiv.appendChild(menuOptions);
    
                    hamburguerBtn.addEventListener('click', () => {
                        menuOptions.style.display = menuOptions.style.display === 'none' ? 'block' : 'none';
                    });
    
                    // Editar nome da lista
                    menuOptions.querySelector('.editar-nome').addEventListener('click', () => {
                        const novoNome = prompt('Digite o novo nome da lista:', lista.nome);
                        if (novoNome && novoNome.trim()) {
                            fetch('listas.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: `action=editar_nome&lista_id=${lista.id}&novo_nome=${encodeURIComponent(novoNome)}`
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        exibirMensagem('success', data.success);
                                        fetchListas();
                                        alert("Nome alterado com sucesso!");
                                    } else {
                                        exibirMensagem('error', data.error);
                                    }
                                })
                                .catch(error => console.error('Erro:', error));
                        }
                    });
    
                    // Apagar lista
                    menuOptions.querySelector('.apagar-lista').addEventListener('click', () => {
                        if (confirm('Tem certeza que deseja apagar esta lista?')) {
                            fetch('listas.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: `action=apagar_lista&lista_id=${lista.id}`
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        exibirMensagem('success', data.success);
                                        fetchListas();
                                        alert("Lista Apagada com sucesso!");
                                    } else {
                                        exibirMensagem('error', data.error);
                                    }
                                })
                                .catch(error => console.error('Erro:', error));
                        }
                    });
    
                    // Exibir séries associadas
                    const listaSeriesDiv = document.createElement('div');
                    listaSeriesDiv.classList.add('container-series');
    
                    if (seriesAssociadas && seriesAssociadas.length > 0) {
                        seriesAssociadas.forEach(serie => {
                            const serieDiv = document.createElement('div');
                            serieDiv.classList.add('serie');
    
                            const imagemContainer = document.createElement('div');
                            imagemContainer.classList.add('imagem-container');
                            imagemContainer.innerHTML = `
                                <img src="${serie.poster_url}" alt="Poster da série" class="imagem-serie">
                            `;
    
                            const botaoLixeira = document.createElement('button');
                            botaoLixeira.classList.add('botao-lixeira');
                            botaoLixeira.innerHTML = '🗑';
    
                            botaoLixeira.addEventListener('click', () => {
                                if (confirm(`Deseja remover a série "${serie.nome}"?`)) {
                                    fetch('listas.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                        body: `action=remover_serie&lista_id=${lista.id}&serie_id=${serie.id}`
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                exibirMensagem('success', data.success);
                                                fetchListas();
                                            } else {
                                                exibirMensagem('error', data.error);
                                            }
                                        })
                                        .catch(error => console.error('Erro:', error));
                                }
                            });
    
                            imagemContainer.appendChild(botaoLixeira);
                            serieDiv.appendChild(imagemContainer);
    
                            const serieTitulo = document.createElement('h5');
                            serieTitulo.textContent = serie.nome;
                            serieDiv.appendChild(serieTitulo);
    
                            listaSeriesDiv.appendChild(serieDiv);
                        });
                    } else {
                        const mensagem = document.createElement('p');
                        mensagem.textContent = 'Nenhuma série adicionada ainda.';
                        listaSeriesDiv.appendChild(mensagem);
                    }
    
                    listaDiv.appendChild(listaSeriesDiv);
                    listasContainer.appendChild(listaDiv);
                });
            })
            .catch(error => {
                console.error('Erro ao buscar listas:', error);
                exibirMensagem('error', 'Erro ao carregar listas.');
            });


            
    }
    

    formCriarLista.addEventListener('submit', (event) => {
        event.preventDefault();
        const nomeLista = formCriarLista.querySelector('input[name="nova_lista"]').value;
    
        if (!nomeLista.trim()) {
            alert('Digite um nome para a lista.');
            return;
        }
    
        fetch('listas.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=nova_lista&nova_lista=${encodeURIComponent(nomeLista)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                exibirMensagem('success', data.success);
                fetchListas(); // Atualiza as listas
                alert("Sua lista foi Criada!");
            } else {
                exibirMensagem('error', data.error);
            }
        })
        .catch(error => {
            console.error('Erro ao criar lista:', error);
            exibirMensagem('error', 'Erro ao criar lista.');
        });
    });
    

    // Inicializa a busca de listas ao carregar a página
    fetchListas();

    
});


