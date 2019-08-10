<?php
declare(strict_types=1);

use App\Lambda\LambdaSqsJob;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

require __DIR__ . '/vendor/autoload.php';

// Create the application
/** @var Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap the console Kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

lambda(function ($event) use ($app) {
    // get the first (and only) queue message from the event
    $payload = $event['Records'][0];
    $job = new LambdaSqsJob($app, $payload);

    /** @var Worker $worker */
    $worker = $app->make(Worker::class);
    $worker->process('lambda', $job, new WorkerOptions());

    return 'Handled ' . $job->getJobId();
});
