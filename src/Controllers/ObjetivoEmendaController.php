<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\ObjetivoEmendaModel;

class ObjetivoEmendaController extends BaseController {

    public function __construct() {
        parent::__construct(new ObjetivoEmendaModel());
    }
}
