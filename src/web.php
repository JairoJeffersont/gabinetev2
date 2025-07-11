<?php

$pagina = isset($_GET['secao']) ? $_GET['secao'] :  header('Location: ?secao=home');

$rotas = [
    'login' => '../src/Views/login/login.php',
    'sair' => '../src/Views/includes/sair.php',
    'recuperar-senha' => '../src/Views/login/recuperar-senha.php',
    'nova-senha' => '../src/Views/login/nova-senha.php',
    'novo-usuario' => '../src/Views/login/novo-usuario.php',
    'meu-gabinete' => '../src/Views/meu-gabinete/index.php',
    'cadastro' => '../src/Views/login/cadastro.php',
    'home' => '../src/Views/home/home.php',
    'tipos-orgaos' => '../src/Views/orgaos/tipos-orgaos.php',
    'tipo-orgao' => '../src/Views/orgaos/tipo-orgao.php',
    'orgaos' => '../src/Views/orgaos/orgaos.php',
    'orgao' => '../src/Views/orgaos/orgao.php',
    'tipos-pessoas' => '../src/Views/pessoas/tipos-pessoas.php',
    'tipo-pessoa' => '../src/Views/pessoas/tipo-pessoa.php',
    'pessoas' => '../src/Views/pessoas/pessoas.php',
    'pessoa' => '../src/Views/pessoas/pessoa.php',
    'pessoas-relatorios' => '../src/Views/pessoas/pessoas-relatorio.php',
    'imprimir-relatorio' => '../src/Views/pessoas/imprimir-relatorio.php'
];

if (array_key_exists($pagina, $rotas)) {
    include $rotas[$pagina];
} else {
    include '../src/Views/errors/404.php';
}
