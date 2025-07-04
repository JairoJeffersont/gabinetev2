<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\MunicipioModel;

class MunicipioController extends BaseController {

    public function __construct() {
        parent::__construct(new MunicipioModel());
    }
}
