<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\SituacaoCompromisso;


class SituacaoCompromissoController extends BaseController {

    public function __construct() {
        parent::__construct(new SituacaoCompromisso());
    }
}