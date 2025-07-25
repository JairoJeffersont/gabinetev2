<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\SituacaoEmendaModel;

class SituacaoEmendaController extends BaseController {

    public function __construct() {
        parent::__construct(new SituacaoEmendaModel());
    }
}
