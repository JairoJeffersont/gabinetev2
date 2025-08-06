<?php

namespace App\Helpers;


class Validation {
    public function slug(string $nome) {

        $nome = strtolower($nome);

        $titulos = ['dr.', 'dra.', 'sr.', 'sra.', 'srta.'];
        $nome = str_replace($titulos, '', $nome);

        $nome = preg_replace('/[^\p{L}\s-]/u', '', $nome);

        $nome = trim($nome);

        $nome = preg_replace('/\s+/', '-', $nome);

        return $nome;
    }
}
