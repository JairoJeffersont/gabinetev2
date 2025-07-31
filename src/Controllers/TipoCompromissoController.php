<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\TipoCompromissoModel;


class TipoCompromissoController extends BaseController {

    public function __construct() {
        parent::__construct(new TipoCompromissoModel());
    }
}
