<?php
// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carros antigos
$carros = [
    [
        'id' => 'mustang67',
        'nome' => 'Ford Mustang',
        'marca' => 'Ford',
        'ano' => 1967,
        'categoria' => 'Esportivo',
        'imagem' => 'mustang.png',
        'descricao' => 'Ícone dos muscle cars americanos, o Mustang 67 marcou época com seu visual agressivo e potência bruta. Um verdadeiro símbolo de liberdade e velocidade.'
    ],
    [
        'id' => 'camaro69',
        'nome' => 'Chevrolet Camaro SS',
        'marca' => 'Chevrolet',
        'ano' => 1969,
        'categoria' => 'Esportivo',
        'imagem' => 'camaro.png',
        'descricao' => 'Rival direto do Mustang, o Camaro 69 SS trazia linhas robustas e motor V8 que fazia tremer o asfalto. Design marcante e alma de pista.'
    ],
    [
        'id' => 'maverick74',
        'nome' => 'Ford Maverick GT',
        'marca' => 'Ford',
        'ano' => 1974,
        'categoria' => 'Esportivo',
        'imagem' => 'maverick.png',
        'descricao' => 'Compacto, ágil e com motorzão V8. O Maverick GT era a resposta brasileira aos muscle cars americanos — ideal para os apaixonados por torque e estilo.'
    ],
    [
        'id' => 'impala67',
        'nome' => 'Chevrolet Impala',
        'marca' => 'Chevrolet',
        'ano' => 1967,
        'categoria' => 'Luxo',
        'imagem' => 'impala.png',
        'descricao' => 'Um clássico elegante com motor potente, o Impala 67 ficou famoso pelo seu conforto, estilo e presença marcante na cultura americana.'
    ],
    [
        'id' => 'charger70',
        'nome' => 'Dodge Charger R/T',
        'marca' => 'Dodge',
        'ano' => 1970,
        'categoria' => 'Esportivo',
        'imagem' => 'charger.png',
        'descricao' => 'Popularizado por filmes e séries, o Charger 70 é um monstro V8 com presença intimidadora. Puro poder sobre rodas.'
    ],
    [
        'id' => 'shelby68',
        'nome' => 'Shelby GT500',
        'marca' => 'Ford',
        'ano' => 1968,
        'categoria' => 'Esportivo',
        'imagem' => 'shelby.png',
        'descricao' => 'Versão nervosa do Mustang, o GT500 era preparado por Carroll Shelby e levava o desempenho ao extremo. Uma raridade cobiçada até hoje.'
    ],
    [
        'id' => 'firebird77',
        'nome' => 'Pontiac Firebird Trans Am',
        'marca' => 'Pontiac',
        'ano' => 1977,
        'categoria' => 'Esportivo',
        'imagem' => 'firebird.png',
        'descricao' => 'Um clássico com alma rebelde. Conhecido pelo visual agressivo e presença forte, o Trans Am virou símbolo da cultura automobilística dos anos 70.'
    ],
    [
        'id' => 'opala76',
        'nome' => 'Chevrolet Opala SS',
        'marca' => 'Chevrolet',
        'ano' => 1976,
        'categoria' => 'Esportivo',
        'imagem' => 'opala.png',
        'descricao' => 'Um muscle brasileiro! Com visual esportivo e mecânica robusta, o Opala SS conquistou gerações e ainda reina nos encontros de carros antigos.'
    ]
];

// Se ainda não existe uma lista de carros na sessão, cria uma lista vazia.
if (!isset($_SESSION['carros'])) {
    $_SESSION['carros'] = [];
}

// Combina os carros antigos com os carros da sessão (novos carros).
$carros = array_merge($carros, $_SESSION['carros']);
?>
