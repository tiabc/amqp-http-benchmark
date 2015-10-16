<?php

if (!extension_loaded('amqp')) die('You must install php native amqp library, more info here: https://github.com/pdezwart/php-amqp' . PHP_EOL);
/*
 * Configuration
 */
$totalRequestsToSend = !empty($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 30000;
$queueHost = 'ec2-52-29-1-55.eu-central-1.compute.amazonaws.com';
$queueUsername = 'admin';
$queuePassword = 'rock4me';
$queueVhost = '/';
$queuePort = 5672;
$concurrency = 50;
////////////

$payload = require_once("requestPayload.php");

$queue = 'test-queue-direct-native-' . rand(0, 1000);
$exchange = 'test-exchange-direct-native' . rand(0, 1000);

// Create a channel
/** @var $exchanges AMQPExchange[] */
$exchanges = [];

for($i=0; $i<$concurrency; $i++) {
	$cnn = new AMQPConnection();
	$cnn->setHost($queueHost);
	$cnn->setLogin($queueUsername);
	$cnn->setPassword($queuePassword);
	$cnn->setVhost($queueVhost);
	$cnn->setPort($queuePort);
	$cnn->connect();

	$ch = new AMQPChannel($cnn);

	$ex = new AMQPExchange($ch);
	$ex->setName($exchange);
	$ex->setType("direct");
	$ex->declareExchange();

	$q = new AMQPQueue($ch);
	$q->setName($queue);
	$q->declareQueue();
	$q->bind($exchange);

	$exchanges[$i] = $ex;
}

$requestsPerSecond = [];

$start = microtime(true);
for ($i = 0; $i < $totalRequestsToSend; $i++) {
	$exchanges[rand(0, $concurrency - 1)]->publish($payload, null, AMQP_NOPARAM, array('content_type' => 'application/json'));

	$now = (string) time();
    if (!isset($requestsPerSecond[$now])) $requestsPerSecond[$now] = 0;
    $requestsPerSecond[$now]++;
}
$end = microtime(true);

echo str_repeat(PHP_EOL, 2);
echo "Total time: " . ($end - $start) . " seconds" . PHP_EOL;
echo "Average RPS: " . array_sum($requestsPerSecond) / count($requestsPerSecond) . " requests" . PHP_EOL;