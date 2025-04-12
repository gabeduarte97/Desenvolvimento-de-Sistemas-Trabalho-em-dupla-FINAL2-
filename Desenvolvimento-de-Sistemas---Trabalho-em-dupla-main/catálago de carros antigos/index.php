<?php
// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
include 'dados.php';

$mensagem = '';
$erro = '';

// Cadastro de novo usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cad_nome'], $_POST['cad_email'], $_POST['cad_senha'])) {
    $novo_usuario = [
        'nome' => $_POST['cad_nome'],
        'email' => $_POST['cad_email'],
        'senha' => password_hash($_POST['cad_senha'], PASSWORD_DEFAULT)
    ];

    if (!isset($_SESSION['usuarios'])) {
        $_SESSION['usuarios'] = [];
    }

    $_SESSION['usuarios'][] = $novo_usuario;
    $_SESSION['usuario'] = $novo_usuario['nome'];
    header('Location: index.php');
    exit;
}

// Adicionar novo carro ao catálogo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'], $_POST['marca'], $_POST['ano'], $_POST['categoria'], $_POST['descricao'], $_FILES['imagem'])) {
    if (isset($_SESSION['usuario'])) {
        $nome = $_POST['nome'];
        $marca = $_POST['marca'];
        $ano = $_POST['ano'];
        $categoria = $_POST['categoria'];
        $descricao = $_POST['descricao'];

        $imagem = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = 'imagens/' . $imagem;
        move_uploaded_file($imagem_tmp, $imagem_destino);

        $novo_carro = [
            'id' => uniqid(),
            'nome' => $nome,
            'marca' => $marca,
            'ano' => $ano,
            'categoria' => $categoria,
            'descricao' => $descricao,
            'imagem' => $imagem
        ];

        if (!isset($_SESSION['carros'])) {
            $_SESSION['carros'] = [];
        }

        $_SESSION['carros'][] = $novo_carro;
        $mensagem = "Carro adicionado com sucesso!";
    } else {
        $erro = "Você precisa estar logado para adicionar um carro.";
    }
}

// Filtragem
$categoria_selecionada = $_GET['categoria'] ?? '';
$resultados = [];
$carros = array_merge($carros, $_SESSION['carros'] ?? []);

if ($categoria_selecionada) {
    foreach ($carros as $carro) {
        if (strtolower($carro['categoria']) === strtolower($categoria_selecionada)) {
            $resultados[] = $carro;
        }
    }
} else {
    $resultados = $carros;
}
?>

<!-- Navegação -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Catálogo de Carros Antigos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="filtrar.php">Filtrar</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Sair (<?= htmlspecialchars($_SESSION['usuario']) ?>)</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Boas-vindas -->
<?php if (isset($_SESSION['usuario'])): ?>
    <div class="alert alert-success text-center mt-3">
        Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario']) ?>!
    </div>
<?php endif; ?>

<!-- Mensagens -->
<?php if ($mensagem): ?>
    <div class="alert alert-success text-center"><?= $mensagem ?></div>
<?php endif; ?>
<?php if ($erro): ?>
    <div class="alert alert-danger text-center"><?= $erro ?></div>
<?php endif; ?>

<!-- Cadastro de Usuário -->
<h2 class="text-center mt-4">👤 Cadastro de Usuário</h2>
<form method="POST" class="row g-3 justify-content-center mb-5">
    <div class="col-md-3"><input type="text" name="cad_nome" class="form-control" placeholder="Nome" required></div>
    <div class="col-md-3"><input type="email" name="cad_email" class="form-control" placeholder="E-mail" required></div>
    <div class="col-md-3"><input type="password" name="cad_senha" class="form-control" placeholder="Senha" required></div>
    <div class="col-md-3"><button type="submit" class="btn btn-success w-100">Cadastrar</button></div>
</form>

<!-- Filtro por Categoria -->
<h2 class="text-center mb-4">🔍 Filtrar por Categoria</h2>
<form method="GET" class="row g-3 mb-4 justify-content-center">
    <div class="col-auto">
        <select name="categoria" class="form-select" required>
            <option value="">-- Selecione uma categoria --</option>
            <?php
            $categorias = array_unique(array_map(fn($c) => $c['categoria'], $carros));
            foreach ($categorias as $categoria):
            ?>
                <option value="<?= $categoria ?>" <?= $categoria === $categoria_selecionada ? 'selected' : '' ?>><?= $categoria ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="index.php" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<!-- Adicionar Novo Carro -->
<?php if (isset($_SESSION['usuario'])): ?>
<h2 class="text-center mb-4">🚗 Adicionar Novo Carro</h2>
<form method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="row g-3 justify-content-center">
        <div class="col-md-3"><input type="text" name="nome" class="form-control" placeholder="Nome do Carro" required></div>
        <div class="col-md-3"><input type="text" name="marca" class="form-control" placeholder="Marca" required></div>
        <div class="col-md-3"><input type="number" name="ano" class="form-control" placeholder="Ano" required></div>
        <div class="col-md-3">
            <select name="categoria" class="form-select" required>
                <option value="">Categoria</option>
                <option value="esportivo">Esportivo</option>
                <option value="clássico">Clássico</option>
                <option value="muscle">Muscle</option>
                <option value="luxo">Luxo</option>
            </select>
        </div>
        <div class="col-md-3"><textarea name="descricao" class="form-control" placeholder="Descrição" required></textarea></div>
        <div class="col-md-3"><input type="file" name="imagem" class="form-control" required></div>
        <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Adicionar Carro</button></div>
    </div>
</form>
<?php endif; ?>

<!-- Lista de Carros -->
<h2 class="text-center mb-4">🚘 Catálogo de Carros Antigos</h2>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($resultados as $carro): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="imagens/<?= $carro['imagem'] ?>" class="card-img-top" alt="<?= $carro['nome'] ?>" data-bs-toggle="modal" data-bs-target="#carroModal<?= $carro['id'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $carro['nome'] ?></h5>
                    <p class="card-text"><strong>Marca:</strong> <?= $carro['marca'] ?><br><strong>Ano:</strong> <?= $carro['ano'] ?><br><strong>Categoria:</strong> <?= $carro['categoria'] ?></p>
                    <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#carroModal<?= $carro['id'] ?>">Ver mais</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="carroModal<?= $carro['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog"><div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $carro['nome'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="imagens/<?= $carro['imagem'] ?>" class="img-fluid mb-3" alt="<?= $carro['nome'] ?>">
                    <p><strong>Marca:</strong> <?= $carro['marca'] ?></p>
                    <p><strong>Ano:</strong> <?= $carro['ano'] ?></p>
                    <p><strong>Categoria:</strong> <?= $carro['categoria'] ?></p>
                    <p><?= $carro['descricao'] ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="detalhes.php?id=<?= $carro['id'] ?>" class="btn btn-primary">Ver detalhes</a>
                </div>
            </div></div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (count($resultados) === 0): ?>
    <p class="text-center text-danger">Nenhum carro encontrado para essa categoria.</p>
<?php endif; ?>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
