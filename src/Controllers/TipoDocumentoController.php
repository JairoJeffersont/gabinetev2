<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\TipoDocumentoModel;

class TipoDocumentoController extends BaseController {

    public function __construct() {
        parent::__construct(new TipoDocumentoModel());
    }
}
