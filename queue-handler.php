<?php
declare(strict_types=1);

use App\Lambda\QueueHandler;

require __DIR__ . '/vendor/autoload.php';

// Create the application
echo 'Creating application' . PHP_EOL;
/** @var Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap the console Kernel
echo 'bootstrapping console kernel' . PHP_EOL;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

return new QueueHandler($app);
