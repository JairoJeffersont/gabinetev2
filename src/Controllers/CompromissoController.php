<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\CompromissoModel;
use Exception;
use App\Helpers\Logger;


class CompromissoController extends BaseController {
    private Logger $logger;

    public function __construct() {
        parent::__construct(new CompromissoModel());
        $this->logger = new Logger();
    }    
}
