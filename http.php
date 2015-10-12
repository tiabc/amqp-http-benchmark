<?php

require_once("vendor/autoload.php");

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/*
 * Configuration
 */
$totalRequestsToSend = !empty($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 30000;
$concurrency = 50;
$rpcCallUrl = 'http://127.0.0.1:30390/createProduct';
////////////

$client = new Client();

$requests = function ($total) use ($rpcCallUrl) {
    $uri = $rpcCallUrl;
    $payload = require_once("requestPayload.php");
    for ($i = 0; $i < $total; $i++) {
        yield new Request('POST', $uri, [], $payload);
    }
};

$requestsPerSecond = [];
$failedRequestsPerSecond = [];

$pool = new Pool($client, $requests($totalRequestsToSend), [
    'concurrency' => $concurrency,
    'fulfilled' => function ($response, $index) use ($requestsPerSecond) {
        $now = (string) time();
        if (!isset($requestsPerSecond[$now])) $requestsPerSecond[$now] = 0;
        $requestsPerSecond[$now]++;
    },
    'rejected' => function ($reason, $index) use ($requestsPerSecond) {
        $now = (string) time();
        if (!isset($failedRequestsPerSecond[$now])) $failedRequestsPerSecond[$now] = 0;
        $failedRequestsPerSecond[$now]++;
    },
]);

// Initiate the transfers and create a promise
$start = microtime(true);
$promise = $pool->promise();

// Force the pool of requests to complete.
$promise->wait();
$end = microtime(true);

echo str_repeat(PHP_EOL, 2);
echo "Total time: " . ($end - $start) . " seconds" . PHP_EOL;
echo "Average RPS: " . array_sum($requestsPerSecond) / count($requestsPerSecond) . " requests" . PHP_EOL;
echo "Failed Requests: " . array_sum($failedRequestsPerSecond) . " requests" . PHP_EOL;