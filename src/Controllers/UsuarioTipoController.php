<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UsuarioTipoModel;

class UsuarioTipoController extends BaseController {

    public function __construct() {
        parent::__construct(new UsuarioTipoModel());
    }
}
