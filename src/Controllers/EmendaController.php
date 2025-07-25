<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\EmendaModel;

class EmendaController extends BaseController {

    public function __construct() {
        parent::__construct(new EmendaModel());
    }
}
