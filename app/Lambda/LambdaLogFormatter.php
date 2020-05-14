<?php
declare(strict_types=1);

namespace App\Lambda;

use Monolog\Formatter\JsonFormatter;

class LambdaLogFormatter extends JsonFormatter
{
    public function format(array $record): string
    {
        $awsRequestId = env('AWS_REQUEST_ID', '');

        return $awsRequestId . ' ' . parent::format($record);
    }
}
