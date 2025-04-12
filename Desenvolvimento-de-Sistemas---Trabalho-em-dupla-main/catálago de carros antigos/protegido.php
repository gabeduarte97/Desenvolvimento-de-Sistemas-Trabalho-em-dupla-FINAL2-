<?php

session_start();



// Verifica se o usuário não está logado e redireciona para o login

if (!isset($_SESSION['usuario_id'])) {

  header('Location: login.php');

  exit; // Finaliza o script para garantir que o redirecionamento seja feito imediatamente

}



// Caminho do arquivo JSON onde os carros serão armazenados

$arquivo_carros = 'carros.json';



// Função para obter os carros armazenados no arquivo JSON

function obterCarros() {

  global $arquivo_carros;

  if (file_exists($arquivo_carros)) {

    $conteudo = file_get_contents($arquivo_carros);

    return json_decode($conteudo, true);

  }

  return [];

}



// Função para salvar os carros no arquivo JSON

function salvarCarros($carros) {

  global $arquivo_carros;

  file_put_contents($arquivo_carros, json_encode($carros, JSON_PRETTY_PRINT));

}



// Processar cadastro de carros

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Recebe os dados do formulário

  $nome = $_POST['nome'] ?? '';

  $marca = $_POST['marca'] ?? '';

  $ano = $_POST['ano'] ?? '';

  $categoria = $_POST['categoria'] ?? '';

  $descricao = $_POST['descricao'] ?? '';



  // Verificar se a imagem foi enviada corretamente

  if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {

    $imagem_nome = $_FILES['imagem']['name'];

    $imagem_tmp = $_FILES['imagem']['tmp_name'];

    $imagem_destino = 'imagens/' . $imagem_nome;



    // Verifica se o arquivo enviado é uma imagem

    $imagem_tipo = mime_content_type($imagem_tmp);

    if (strpos($imagem_tipo, 'image/') === false) {

      echo '<div class="alert alert-danger">Erro: O arquivo enviado não é uma imagem válida!</div>';

    } else {

      // Movendo o arquivo para a pasta de imagens

      move_uploaded_file($imagem_tmp, $imagem_destino);

    }

  } else {

    $imagem_nome = ''; // Se não enviar a imagem, o campo ficará vazio

  }



  // Validação dos campos obrigatórios

  if ($nome && $marca && $ano && $categoria && $imagem_nome) {

    $carros = obterCarros(); // Obtém os carros já cadastrados no arquivo JSON



    $novo_id = uniqid(); // Gera um ID único para o carro

    $novo_carro = [

      'id' => $novo_id,

      'usuario_id' => $_SESSION['usuario_id'], // Associa o carro ao ID do usuário logado

      'nome' => $nome,

      'marca' => $marca,

      'ano' => $ano,

      'categoria' => $categoria,

      'imagem' => $imagem_nome,

      'descricao' => $descricao

    ];



    // Adiciona o novo carro no array de carros

    $carros[] = $novo_carro;



    // Salva os carros de volta no arquivo JSON

    salvarCarros($carros);



    echo '<div class="alert alert-success">🚗 Carro cadastrado com sucesso!</div>';

  } else {

    echo '<div class="alert alert-danger">Preencha todos os campos obrigatórios!</div>';

  }

}

?>



<h2 class="mb-4 text-center">📋 Cadastro de Novo Carro Antigo</h2>



<!-- Formulário para cadastro de carro -->

<form method="POST" enctype="multipart/form-data" class="row g-3 mb-5">

  <div class="col-md-6">

    <label class="form-label">Nome *</label>

    <input type="text" name="nome" class="form-control" required>

  </div>

  <div class="col-md-6">

    <label class="form-label">Marca *</label>

    <input type="text" name="marca" class="form-control" required>

  </div>

  <div class="col-md-4">

    <label class="form-label">Ano *</label>

    <input type="number" name="ano" class="form-control" required>

  </div>

  <div class="col-md-4">

    <label class="form-label">Categoria *</label>

    <input type="text" name="categoria" class="form-control" required>

  </div>

  <div class="col-md-4">

    <label class="form-label">Imagem *</label>

    <input type="file" name="imagem" class="form-control" required>

  </div>

  <div class="col-12">

    <label class="form-label">Descrição (opcional)</label>

    <textarea name="descricao" class="form-control" rows="3"></textarea>

  </div>

  <div class="col-12 text-end">

    <button type="submit" class="btn btn-success">Cadastrar</button>

  </div>

</form>



<hr>



<h4 class="mb-3">🚘 Carros Cadastrados por Você:</h4>



<?php

// Exibe apenas os carros do usuário logado

$carros_usuario = [];

$carros = obterCarros();

foreach ($carros as $carro) {

  if ($carro['usuario_id'] == $_SESSION['usuario_id']) {

    $carros_usuario[] = $carro;

  }

}



if (count($carros_usuario) > 0): ?>

  <!-- Exibe os carros cadastrados -->

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

    <?php foreach ($carros_usuario as $carro): ?>

      <div class="col">

        <div class="card h-100">

          <img src="imagens/<?= htmlspecialchars($carro['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($carro['nome']) ?>">

          <div class="card-body">

            <h5 class="card-title"><?= htmlspecialchars($carro['nome']) ?></h5>

            <p class="card-text">

              <strong>Marca:</strong> <?= htmlspecialchars($carro['marca']) ?><br>

              <strong>Ano:</strong> <?= htmlspecialchars($carro['ano']) ?><br>

              <strong>Categoria:</strong> <?= htmlspecialchars($carro['categoria']) ?>

            </p>

            <p class="card-text small"><?= htmlspecialchars($carro['descricao']) ?></p>

          </div>

        </div>

      </div>

    <?php endforeach; ?>

  </div>

<?php else: ?>

  <p>Nenhum carro cadastrado ainda.</p>

<?php endif; ?>



<?php include 'footer.php'; ?>