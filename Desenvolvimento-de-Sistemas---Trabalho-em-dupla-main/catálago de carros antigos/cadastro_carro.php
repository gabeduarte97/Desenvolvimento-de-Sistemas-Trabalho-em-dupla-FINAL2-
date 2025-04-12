<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario'])) {
    $nome = $_POST['nome'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];

    // Processa a imagem
    $imagem = $_FILES['imagem']['name'];
    $imagem_tmp = $_FILES['imagem']['tmp_name'];
    $imagem_destino = 'imagens/' . $imagem;
    move_uploaded_file($imagem_tmp, $imagem_destino);

    // Novo carro com ID e ID do usuário (nome do usuário por enquanto)
    $novo_carro = [
        'id' => uniqid(),
        'nome' => $nome,
        'marca' => $marca,
        'ano' => $ano,
        'categoria' => $categoria,
        'descricao' => $descricao,
        'imagem' => $imagem,
        'id_usuario' => $_SESSION['usuario'] // pode trocar por ID real se tiver
    ];

    // Carrega JSON atual
    $carros = file_exists('carros.json') ? json_decode(file_get_contents('carros.json'), true) : [];

    // Adiciona o novo carro e salva
    $carros[] = $novo_carro;
    file_put_contents('carros.json', json_encode($carros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: index.php');
    exit;
} else {
    echo "Acesso negado.";
}
