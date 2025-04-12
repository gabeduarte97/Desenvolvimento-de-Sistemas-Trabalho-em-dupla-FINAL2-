<?php

function formatarTexto($texto) {
    return ucfirst(strtolower($texto));
}

function filtrarPorCategoria($itens, $categoria) {
    $resultado = [];
    foreach ($itens as $item) {
        if (strtolower($item['categoria']) == strtolower($categoria)) {
            $resultado[] = $item;
        }
    }
    return $resultado;
}
