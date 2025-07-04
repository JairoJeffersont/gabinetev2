<?php



use App\Helpers\FindFiles;


require_once __DIR__ . '../../vendor/autoload.php';

$a = new FindFiles();

print_r($a->listarArquivos('../src/Controllers', 'url'));
