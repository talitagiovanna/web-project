<?php
  // Inicia a sessão
  session_start();

  // Destrói todas as variáveis de sessão
  session_unset();

  // Destrói a sessão
  session_destroy();

  // Limpa o cookie de sessão (para garantir que a sessão não seja mantida)
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');  // Expira o cookie de sessão
  }

  // Redireciona o usuário para a página inicial ou de login
  header("Location: index.html");  // Ou para login.php, se for o caso
  exit();
?>
