<?php
include 'conexao.php'; // Inclui a conexão com o JSON

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $usuarios = carregarUsuarios();

    $usuarioValido = null;
    foreach ($usuarios as $usuarioExistente) {
        if ($usuarioExistente['usuario'] === $usuario && password_verify($senha, $usuarioExistente['senha_hash'])) {
            $usuarioValido = $usuarioExistente;
            break;
        }
    }

    if ($usuarioValido) {
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger text-center"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuário</label>
                <input type="text" name="usuario" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap 5 JS (opcional, para funcionalidades como modais ou tooltips) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
