<?php

require_once("vendor/autoload.php");

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/*
 * Configuration
 */
$totalRequestsToSend = !empty($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 30000;
$queueHost = 'ec2-52-29-1-55.eu-central-1.compute.amazonaws.com';
$queueUsername = 'admin';
$queuePassword = 'rock4me';
$queueVhost = '/';
$queuePort = 5672;
////////////

$payload = require_once("requestPayload.php");

$connection = new AMQPConnection($queueHost, $queuePort, $queueUsername, $queuePassword);

$channel = $connection->channel();

$queue = 'test-queue-direct';
$exchange = 'test-exchange-direct';
$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

$requestsPerSecond = [];

$start = microtime(true);
for ($i = 0; $i < $totalRequestsToSend; $i++) {
	$msg = new AMQPMessage($payload, array('content_type' => 'application/json'));
	$channel->basic_publish($msg, $exchange);
	$now = (string) time();
    if (!isset($requestsPerSecond[$now])) $requestsPerSecond[$now] = 0;
    $requestsPerSecond[$now]++;
}
$end = microtime(true);

$channel->close();
$connection->close();

echo str_repeat(PHP_EOL, 2);
echo "Total time: " . ($end - $start) . " seconds" . PHP_EOL;
echo "Average RPS: " . array_sum($requestsPerSecond) / count($requestsPerSecond) . " requests" . PHP_EOL;