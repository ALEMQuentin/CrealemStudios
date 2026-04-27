<?php

require_once __DIR__ . '/../admin/controllers/Kernel.php';

use App\Controllers\Admin\Kernel;

$kernel = new Kernel();
$kernel->handle();
