<?php

$pagina = isset($_GET['secao']) ? $_GET['secao'] :  header('Location: ?secao=home');

$rotas = [
    'login' => '../src/Views/login/login.php',
    'sair' => '../src/Views/includes/sair.php',
    'recuperar-senha' => '../src/Views/login/recuperar-senha.php',
    'nova-senha' => '../src/Views/login/nova-senha.php',
    'novo-usuario' => '../src/Views/login/novo-usuario.php',
    'editar-usuario' => '../src/Views/meu-gabinete/editar-usuario.php',
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
    'tipos-documentos' => '../src/Views/documentos/tipos-documentos.php',
    'tipo-documento' => '../src/Views/documentos/tipo-documento.php',
    'documentos' => '../src/Views/documentos/documentos.php',
    'emendas-status' => '../src/Views/emendas/situacaos_emenda.php',
    'emendas-objetivos' => '../src/Views/emendas/objetivos_emenda.php',
    'emenda-status' => '../src/Views/emendas/situacao_emenda.php',
    'emenda-objetivo' => '../src/Views/emendas/objetivo_emenda.php',
    'emendas' => '../src/Views/emendas/emendas.php',
    'emenda' => '../src/Views/emendas/emenda.php',

];

if (array_key_exists($pagina, $rotas)) {
    include $rotas[$pagina];
} else {
    include '../src/Views/errors/404.php';
}

