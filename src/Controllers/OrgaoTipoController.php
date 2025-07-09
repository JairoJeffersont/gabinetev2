<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\OrgaoTipoModel;

class OrgaoTipoController extends BaseController {

    public function __construct() {
        parent::__construct(new OrgaoTipoModel());
    }

    

}
