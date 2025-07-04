<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\OrgaoModel;

class OrgaoController extends BaseController {

    public function __construct() {
        parent::__construct(new OrgaoModel());
    }
}
