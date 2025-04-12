<?php

session_start();



// Função para carregar os usuários do arquivo JSON

function carregarUsuarios() {

  $usuarios = [];

  if (file_exists('usuarios.json')) {

    $usuarios = json_decode(file_get_contents('usuarios.json'), true);

  }

  return $usuarios;

}



// Função para salvar os usuários no arquivo JSON

function salvarUsuarios($usuarios) {

  file_put_contents('usuarios.json', json_encode($usuarios, JSON_PRETTY_PRINT));

}



$erro = '';

$sucesso = '';



// Processamento do formulário de cadastro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $usuario = $_POST['usuario'];

  $senha = $_POST['senha'];

  $senhaConfirmar = $_POST['senha_confirmar'];



  // Verifica se a senha e a confirmação são iguais

  if ($senha !== $senhaConfirmar) {

    $erro = 'As senhas não coincidem.';

  } else {

    $usuarios = carregarUsuarios();

     

    // Verifica se o nome de usuário já existe

    foreach ($usuarios as $usuarioExistente) {

      if ($usuarioExistente['usuario'] === $usuario) {

        $erro = 'Usuário já existe!';

        break;

      }

    }



    // Se não houver erro, cria o novo usuário

    if (!$erro) {

      $senhaHash = password_hash($senha, PASSWORD_DEFAULT);



      // Adiciona o novo usuário ao arquivo

      $usuarios[] = [

        'usuario' => $usuario,

        'senha_hash' => $senhaHash

      ];



      // Salva os dados no arquivo

      salvarUsuarios($usuarios);

       

      // Redireciona para a página de login após o cadastro bem-sucedido

      $sucesso = 'Cadastro realizado com sucesso! Você pode fazer login agora.';

      header("Location: login.php");

      exit;

    }

  }

}

?>



<!DOCTYPE html>

<html lang="pt-BR">

<head>

  <meta charset="UTF-8">

  <title>Cadastro de Usuário - Catálogo de Carros Antigos</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Seu CSS personalizado -->

  <link rel="stylesheet" href="estilo.css">

</head>

<body>

  <!-- Cabeçalho -->

  <header class="bg-dark text-white text-center py-3">

    <h1 class="mb-0">Catálogo de Carros Antigos</h1>

  </header>



  <!-- Menu de navegação -->

  <nav class="navbar navbar-expand-lg navbar-light bg-light">

    <div class="container-fluid">

      <a class="navbar-brand" href="index.php">Catálogo de Carros</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button>

      <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav ms-auto">

          <li class="nav-item">

            <a class="nav-link" href="index.php">Home</a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="filtrar.php">Filtrar</a>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="cadastro.php">Cadastro</a>

          </li>

          <?php if (isset($_SESSION['usuario'])): ?>

            <li class="nav-item">

              <a class="nav-link" href="logout.php">Sair (<?= htmlspecialchars($_SESSION['usuario']) ?>)</a>

            </li>

          <?php else: ?>

            <li class="nav-item">

              <a class="nav-link" href="login.php">Login</a>

            </li>

          <?php endif; ?>

        </ul>

      </div>

    </div>

  </nav>



  <main class="container my-4">

    <h2>Cadastro de Usuário</h2>



    <?php if ($erro): ?>

      <div class="alert alert-danger"><?= $erro ?></div>

    <?php endif; ?>



    <?php if ($sucesso): ?>

      <div class="alert alert-success"><?= $sucesso ?></div>

    <?php endif; ?>



    <form method="POST">

      <div class="mb-3">

        <label for="usuario" class="form-label">Usuário</label>

        <input type="text" name="usuario" class="form-control" required>

      </div>



      <div class="mb-3">

        <label for="senha" class="form-label">Senha</label>

        <input type="password" name="senha" class="form-control" required>

      </div>



      <div class="mb-3">

        <label for="senha_confirmar" class="form-label">Confirmar Senha</label>

        <input type="password" name="senha_confirmar" class="form-control" required>

      </div>



      <button type="submit" class="btn btn-primary">Cadastrar</button>

    </form>

  </main>



  <!-- Bootstrap JS -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>