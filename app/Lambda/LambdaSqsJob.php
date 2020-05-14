<?php
declare(strict_types=1);

namespace App\Lambda;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;

class LambdaSqsJob extends Job implements JobContract
{
    /**
     * The Amazon SQS job instance.
     *
     * @var array
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param Container $container
     * @param array $job
     */
    public function __construct(Container $container, array $job)
    {
        $this->container = $container;
        $this->job = $job;
    }

    /**
     * Get the raw body string for the job. We look for both `body` and
     * `Body` because lambda does not guarantee the case of the payload.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job['Body'] ?? $this->job['body'];
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        if (array_key_exists('Attributes', $this->job)) {
            return (int)$this->job['Attributes']['ApproximateReceiveCount'];
        }

        return (int)$this->job['attributes']['ApproximateReceiveCount'];
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job['MessageId'] ?? $this->job['messageId'];
    }

    public function getDisplayName(): string
    {
        $payload = $this->payload();
        if (!empty($payload['displayName'])) {
            return $payload['displayName'];
        }

        return $payload['job'] ?? '';
    }
}
