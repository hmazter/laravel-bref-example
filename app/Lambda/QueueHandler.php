<?php
declare(strict_types=1);

namespace App\Lambda;

use Bref\Context\Context;
use Bref\Event\Sqs\SqsEvent;
use Bref\Event\Sqs\SqsHandler;
use Illuminate\Foundation\Application;
use Psr\Log\LoggerInterface;
use Throwable;

class QueueHandler extends SqsHandler
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handleSqs(SqsEvent $event, Context $context): void
    {
        putenv("AWS_REQUEST_ID={$context->getAwsRequestId()}");

        $logger = $this->app->make(LoggerInterface::class);
        $payload = $event->getRecords()[0]->toArray();

        $logger->info('received job', ['payload' => $payload]);
        $job = new LambdaSqsJob($this->app, $payload);

        try {
            $logger->info('Handling queue job', ['jobName' => $job->getDisplayName()]);
            $job->fire();
        } catch (Throwable $e) {
            // report exception before rethrowing it and pushing the failed job back in sqs
            report($e);
            throw $e;
        }
    }
}
