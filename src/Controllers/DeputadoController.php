<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\DeputadoModel;

class DeputadoController extends BaseController {

    public function __construct() {
        parent::__construct(new DeputadoModel());
    }
}
