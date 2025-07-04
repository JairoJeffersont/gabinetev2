<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\PessoaTipoModel;

class PessoaTipoController extends BaseController {

    public function __construct() {
        parent::__construct(new PessoaTipoModel());
    }
}
