<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\ProposicaoModel;


class ProposicaoController extends BaseController {

    public function __construct() {
        parent::__construct(new ProposicaoModel());
    }
  
}
