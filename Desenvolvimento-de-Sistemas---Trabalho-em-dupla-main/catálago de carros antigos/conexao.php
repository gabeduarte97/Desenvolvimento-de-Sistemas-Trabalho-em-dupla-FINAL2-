<?php
function carregarUsuarios() {
    $usuarios = file_get_contents('usuarios.json');
    return json_decode($usuarios, true); // Retorna um array de usuários
}

function salvarUsuarios($usuarios) {
    $usuariosJson = json_encode($usuarios, JSON_PRETTY_PRINT);
    file_put_contents('usuarios.json', $usuariosJson);
}
?>
