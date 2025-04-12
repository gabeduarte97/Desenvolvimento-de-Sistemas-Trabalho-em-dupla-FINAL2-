<?php
include 'header.php';
include 'dados.php';

$id = $_GET['id'] ?? null;
$carro = null;

foreach ($carros as $c) {
    if ($c['id'] === $id) {
        $carro = $c;
        break;
    }
}

if (!$carro) {
    echo "<p class='alert alert-danger'>Carro não encontrado.</p>";
    include 'footer.php';
    exit;
}
?>

<div class="container my-5">
    <h2 class="mb-4"><?= $carro['nome'] ?> (<?= $carro['ano'] ?>)</h2>
    <div class="row">
        <div class="col-md-6">
            <img src="imagens/<?= $carro['imagem'] ?>" class="img-fluid rounded shadow" alt="<?= $carro['nome'] ?>">
        </div>
        <div class="col-md-6">
            <p><strong>Marca:</strong> <?= $carro['marca'] ?></p>
            <p><strong>Categoria:</strong> <?= $carro['categoria'] ?></p>
            <p><strong>Ano:</strong> <?= $carro['ano'] ?></p>
            <hr>
            <p><?= $carro['descricao'] ?></p>
            <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
