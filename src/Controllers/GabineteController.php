<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\GabineteModel;

class GabineteController extends BaseController {

    public function __construct() {
        parent::__construct(new GabineteModel());
    }
}
