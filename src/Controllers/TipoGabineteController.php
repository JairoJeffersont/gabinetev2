<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\TipoGabineteModel;


class TipoGabineteController extends BaseController {

    public function __construct() {
        parent::__construct(new TipoGabineteModel());
    }
}
