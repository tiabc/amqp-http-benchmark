<?php

/*
 * Configuration
 */
$totalRequestsToSend = !empty($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 30000;
$rpcCallUrl = 'http://95.213.188.198:30390/createProduct';
////////////

$ch = curl_init($rpcCallUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, require_once("requestPayload.php"));

$requestsPerSecond = [];

$start = microtime(true);
for ($i = 0; $i < $totalRequestsToSend; $i++)
{
    $now = (string) time();
    curl_exec($ch);
    if (!isset($requestsPerSecond[$now])) $requestsPerSecond[$now] = 0;
    $requestsPerSecond[$now]++;
}
$end = microtime(true);

print_r($requestsPerSecond);

echo str_repeat(PHP_EOL, 2);
echo "Total time: " . ($end - $start) . " seconds" . PHP_EOL;
echo "Average RPS: " . array_sum($requestsPerSecond) / count($requestsPerSecond) . " requests" . PHP_EOL;
