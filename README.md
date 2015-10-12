## Benchmark Results

#### AMQP (php amqp-native.php 100000)

Total time: 12.69 seconds

Average RPS: 7692 requests

#### HTTP (php http.php 100000)

Total time: 71.11 seconds

Average RPS: 1388 requests

***AMQP has at least 5.5x of the performance of Http implementation***

## Run it yourself

I spun up a temporary ec2 instance for easier testing, it contains a RabbitMQ installation and the Http wrapper implemented in Go. The hostname is: ec2-52-29-1-55.eu-central-1.compute.amazonaws.com

You need to clone this repo, run the following three commands:
- ```php http.php 30000```
- ```php amqp.php 30000```
- ```php amqp-native.php 30000``` (You need to install the pecl amqp extension)

## Golang Server

This is a very stripped down version of the HTTP wrapper, things that would definitly decrease performance further are:

1. **Auth Layer** (Is automatically handled by RabbitMQ in the case of AMQP)
2. **More Latency;** Http Wrapper and the RabbitMQ would be in a different servers.
3. **More Latency;** In case of a loadbalancer or a reverse proxy in front of the wrapper
