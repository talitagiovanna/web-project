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
                .then(response => response.json())
                .then(data => {
                    listasContainer.innerHTML = ''; // Limpa o container
    
                    data.listas.forEach(item => {
                        const { lista, series: seriesAssociadas } = item;
                        const listaDiv = document.createElement('div');
                        listaDiv.classList.add('lista');
                        listaDiv.dataset.listaId = lista.id;
    
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
                            <button class="editar-series">Adicionar Séries</button>
                            <button class="apagar-lista">Apagar Lista</button>
                        `;
                        menuOptions.style.display = 'none';
                        listaDiv.appendChild(menuOptions);
    
                        hamburguerBtn.addEventListener('click', () => {
                            menuOptions.style.display = menuOptions.style.display === 'none' ? 'block' : 'none';
                        });
    
                        const seriesIdsAssociadas = seriesAssociadas.map(serie => serie.id);
    
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
                                            addNotification(`Você alterou o nome da lista: "${lista.nome}" para "${novoNome}"`);

                                        } else {
                                            exibirMensagem('error', data.error);
                                        }
                                    })
                                    .catch(error => console.error('Erro:', error));
                            }
                        });
                        
                        // Editar séries da lista
                        menuOptions.querySelector('.editar-series').addEventListener('click', () => {
                            const conteudoModal = document.getElementById('conteudo-modal');
                            const campoPesquisa = document.getElementById('pesquisar-series');
                        
                            conteudoModal.innerHTML = ''; // Limpar conteúdo anterior
                            campoPesquisa.value = ''; // Limpar o campo de pesquisa
                            
                            function exibirSeries(seriesFiltradas) {
                                conteudoModal.innerHTML = ''; // Limpa o conteúdo do modal
                            
                                if (seriesFiltradas.length === 0) {
                                    const mensagem = document.createElement('p');
                                    mensagem.textContent = 'Nenhuma série encontrada.';
                                    conteudoModal.appendChild(mensagem);
                                } else {
                                    seriesFiltradas.forEach(serie => {
                                        const serieDiv = document.createElement('div');
                                        serieDiv.classList.add('serie-modal');
                            
                                        const serieTitulo = document.createElement('h5');
                                        serieTitulo.textContent = serie.nome;
                                        serieDiv.appendChild(serieTitulo);
                            
                                        const imagemContainer = document.createElement('div');
                                        imagemContainer.classList.add('imagem-container');
                                        imagemContainer.innerHTML = `<img src="${serie.poster_url}" alt="Poster da série" class="imagem-serie">`;
                            
                                        // Botão para adicionar a série à lista
                                        const botaoAdicionar = document.createElement('button');
                                        botaoAdicionar.textContent = 'Adicionar à lista';
                                        botaoAdicionar.addEventListener('click', () => {
                                            const listaId = listaDiv.dataset.listaId; // Obtém o ID da lista
                                            fetch('listas.php', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                body: `action=adicionar_serie_lista&serie_id=${serie.id}&lista_id=${listaId}`
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    exibirMensagem('success', 'Série adicionada à lista com sucesso!');
                                                    fetchListas(); // Atualiza as listas
                                                } else {
                                                    exibirMensagem('error', data.error);
                                                }
                                            })
                                            .catch(error => console.error('Erro ao adicionar série:', error));
                                        });
                            
                                        serieDiv.appendChild(imagemContainer);
                                        serieDiv.appendChild(botaoAdicionar); // Adiciona o botão ao modal
                                        conteudoModal.appendChild(serieDiv);
                                    });
                                }
                            }
                            
                            campoPesquisa.addEventListener('input', () => {
                                const termoBusca = campoPesquisa.value.toLowerCase().trim();
                            
                                if (termoBusca === '') {
                                    conteudoModal.innerHTML = ''; // Limpa o modal se o campo de pesquisa estiver vazio
                                    return;
                                }
                            
                                fetch(`listas.php?action=fetch&pesquisa=${encodeURIComponent(termoBusca)}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const seriesFiltradas = data.series.filter(serie => !seriesIdsAssociadas.includes(serie.id));
                                        exibirSeries(seriesFiltradas);
                                    })
                                    .catch(error => console.error('Erro ao buscar as séries:', error));
                            });
                        
                            // Mostrar o modal
                            const modal = document.getElementById('modal-editar-series');
                            modal.style.display = 'block';
                        });
                        
                        // Fechar o modal quando o usuário clicar no botão "X"
                        document.getElementById('fechar-modal').addEventListener('click', () => {
                            const modal = document.getElementById('modal-editar-series');
                            modal.style.display = 'none';
                        });
                        
                        // Fechar o modal clicando fora do conteúdo
                        window.addEventListener('click', (event) => {
                            const modal = document.getElementById('modal-editar-series');
                            if (event.target === modal) {
                                modal.style.display = 'none';
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
                                            fetchListas(); // Atualiza as listas
                                        } else {
                                            exibirMensagem('error', data.error);
                                        }
                                    })
                                    .catch(error => console.error('Erro ao apagar lista:', error));
                            }
                        });
                        
                        // Exibir séries associadas à lista
                        const listaSeriesDiv = document.createElement('div');
                        listaSeriesDiv.classList.add('series-associadas');
                        
                        if (seriesAssociadas && seriesAssociadas.length > 0) {
                            seriesAssociadas.forEach(serie => {
                                const serieItem = document.createElement('div');
                                serieItem.classList.add('serie');
                                serieItem.innerHTML = `
                                    <h5>${serie.nome}</h5>
                                    <img src="${serie.poster_url}" alt="Poster da série" class="poster-serie">
                                `;
                                listaSeriesDiv.appendChild(serieItem);
                            });
                        } else {
                            const mensagem = document.createElement('p');
                            mensagem.textContent = 'Não há séries associadas a esta lista.';
                            listaSeriesDiv.appendChild(mensagem);
                        }
        
                        listaDiv.appendChild(listaSeriesDiv);
                        listasContainer.appendChild(listaDiv);
                    });
                })
                .catch(error => console.error('Erro ao carregar as listas:', error));
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
            addNotification(`Você criou uma lista chamada: "${nomeLista}`);
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

// Função para adicionar uma notificação
function addNotification(message) {
    const formData = new FormData();
    formData.append('action', 'add');  // Define a ação como 'add'
    formData.append('message', message);  // Passa a mensagem a ser salva

    fetch('notifications.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificação adicionada:', data.message);
            loadNotifications(); // Atualiza as notificações
        } else {
            console.error('Erro ao adicionar notificação:', data.message);
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
    });
}

// Função para carregar notificações
function loadNotifications() {
    const formData = new FormData();
    formData.append('action', 'get');  // Define a ação como 'get'

    fetch('notifications.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const menu = document.getElementById('notification-menu');
        if (Array.isArray(data)) {  // Verifica se o retorno é um array
            if (data.length === 0) {
                menu.innerHTML = '<p>Sem notificações novas.</p>';
                return;
            }

            // Atualiza a lista de notificações
            menu.innerHTML = '';
            data.forEach(notification => {
                const div = document.createElement('div');
                div.textContent = notification.message;

                // Marcar notificação como lida ao clicar
                div.addEventListener('click', () => markNotificationAsRead(notification.id));

                menu.appendChild(div);
            });

            // Atualiza o contador de notificações não lidas
            const unreadCount = data.filter(notification => !notification.is_read).length;
            document.getElementById('notification-count').textContent = unreadCount;
        } else {
            console.error('Erro: Dados recebidos não são um array de notificações');
        }
    })
    .catch(error => console.error('Erro ao carregar notificações:', error));
}

// Função para marcar notificação como lida
function markNotificationAsRead(notificationId) {
    fetch('notifications.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'mark-read',
            notification_id: notificationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificação marcada como vista.');
            loadNotifications();  // Atualiza as notificações
            deleteReadNotifications();  // Exclui notificações lidas
        } else {
            console.error('Erro ao marcar notificação como vista:', data.message);
        }
    })
    .catch(error => console.error('Erro ao marcar notificação:', error));
}

// Função para excluir notificações lidas do banco
function deleteReadNotifications() {
    fetch('notifications.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'delete-read'  // Ação para excluir notificações lidas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificações lidas excluídas.');
        } else {
            console.error('Erro ao excluir notificações lidas:', data.message);
        }
    })
    .catch(error => console.error('Erro ao excluir notificações lidas:', error));
}

// Função para excluir notificações antigas
function deleteOldNotifications() {
    fetch('notifications.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'delete-old'  // Ação para excluir notificações antigas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificações antigas excluídas.');
        } else {
            console.error('Erro ao excluir notificações antigas:', data.message);
        }
    })
    .catch(error => console.error('Erro ao excluir notificações antigas:', error));
}

// Inicializa a busca de listas ao carregar a página
fetchListas();
loadNotifications();

// Exemplo de execução periódica da exclusão de notificações antigas
setInterval(deleteOldNotifications, 24 * 60 * 60 * 1000); // Exclui notificações antigas a cada 24 horas

});

function showTab(tabId) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));

    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(button => button.classList.remove('active'));

    document.getElementById(`${tabId}-tab`).classList.add('active');
    document.querySelector(`.tab-btn[onclick="showTab('${tabId}')"]`).classList.add('active');
}
