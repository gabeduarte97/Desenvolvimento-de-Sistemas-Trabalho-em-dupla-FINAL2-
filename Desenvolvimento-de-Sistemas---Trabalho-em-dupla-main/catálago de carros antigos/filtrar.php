<?php
include 'header.php';
include 'dados.php';

$categoria_selecionada = $_GET['categoria'] ?? '';
$resultados = [];

if ($categoria_selecionada) {
    foreach ($carros as $carro) {
        if (strtolower($carro['categoria']) === strtolower($categoria_selecionada)) {
            $resultados[] = $carro;
        }
    }
}
?>

<h2 class="text-center mb-4">🔍 Filtrar por Categoria</h2>

<form method="GET" class="row g-3 mb-4 justify-content-center">
    <div class="col-auto">
        <select name="categoria" class="form-select" required>
            <option value="">-- Selecione uma categoria --</option>
            <?php
            // Pega categorias únicas
            $categorias = array_unique(array_map(fn($c) => $c['categoria'], $carros));
            foreach ($categorias as $categoria):
            ?>
                <option value="<?= $categoria ?>" <?= $categoria === $categoria_selecionada ? 'selected' : '' ?>>
                    <?= $categoria ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="filtrar.php" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<?php if ($categoria_selecionada): ?>
    <h4 class="text-center mb-3">Resultados para: <em><?= htmlspecialchars($categoria_selecionada) ?></em></h4>

    <?php if (count($resultados) > 0): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($resultados as $carro): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="imagens/<?= $carro['imagem'] ?>" class="card-img-top img-fluid" alt="<?= $carro['nome'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $carro['nome'] ?></h5>
                            <p class="card-text">
                                <strong>Marca:</strong> <?= $carro['marca'] ?><br>
                                <strong>Ano:</strong> <?= $carro['ano'] ?>
                            </p>
                            <a href="detalhes.php?id=<?= $carro['id'] ?>" class="btn btn-primary">Ver mais</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-danger">Nenhum carro encontrado para essa categoria.</p>
    <?php endif; ?>
<?php endif; ?>

<?php include 'footer.php'; ?>
