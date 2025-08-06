<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AutorProposicaoModel;
use App\Helpers\GetApi;
use App\Helpers\Validation;

class AutorProposicaoController extends BaseController {

    public function __construct() {
        parent::__construct(new AutorProposicaoModel());
    }
    
}
