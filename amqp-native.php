<?php

if (!extension_loaded('amqp')) die('You must install php native amqp library, more info here: https://github.com/pdezwart/php-amqp' . PHP_EOL);
/*
 * Configuration
 */
$totalRequestsToSend = !empty($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 30000;
$queueHost = 'localhost';
$queueUsername = 'guest';
$queuePassword = 'guest';
$queueVhost = '/';
$queuePort = 5672;
////////////

$payload = require_once("requestPayload.php");

$cnn = new AMQPConnection();
$cnn->setHost($queueHost);
$cnn->setLogin($queueUsername);
$cnn->setPassword($queuePassword);
$cnn->setVhost($queueVhost);
$cnn->setPort($queuePort);
$cnn->connect();

// Create a channel
$ch = new AMQPChannel($cnn);

$queue = 'test-queue-direct-native';
$exchange = 'test-exchange-direct-native';

$ex = new AMQPExchange($ch);
$ex->setName($exchange);
$ex->setType("direct");
$ex->declareExchange();

$q = new AMQPQueue($ch);
$q->setName($queue);
$q->declareQueue();
$q->bind($exchange);

$requestsPerSecond = [];

$start = microtime(true);
for ($i = 0; $i < $totalRequestsToSend; $i++) {
	$ex->publish($payload, null, AMQP_NOPARAM, array('content_type' => 'application/json'));

	$now = (string) time();
    if (!isset($requestsPerSecond[$now])) $requestsPerSecond[$now] = 0;
    $requestsPerSecond[$now]++;
}
$end = microtime(true);

echo str_repeat(PHP_EOL, 2);
echo "Total time: " . ($end - $start) . " seconds" . PHP_EOL;
echo "Average RPS: " . array_sum($requestsPerSecond) / count($requestsPerSecond) . " requests" . PHP_EOL;