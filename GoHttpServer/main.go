package main

import (
	"io"
	"net/http"
	"encoding/json"
	"time"
	"io/ioutil"
	"fmt"

	"github.com/nu7hatch/gouuid"
	"github.com/streadway/amqp"
)

var rmqConnection *amqp.Connection

type CreateProductResponse struct{
	UUID, Timestamp string
}

func main() {
	connectQueue()

	http.HandleFunc("/createProduct", createProduct)
	http.ListenAndServe(":30390", nil)
}

func connectQueue() error {
	var err error
	rmqConnection, err = amqp.Dial("amqp://guest:guest@localhost:5672/")

	if err != nil {
		return fmt.Errorf("Dial: %s", err)
	}

	channel, err := rmqConnection.Channel()
	if err != nil {
		return fmt.Errorf("Channel: %s", err)
	}

	if err := channel.ExchangeDeclare(
		"test-queue", // name
		"direct",     // type
		true,         // durable
		false,        // auto-deleted
		false,        // internal
		false,        // noWait
		nil,          // arguments
	); err != nil {
		return fmt.Errorf("Exchange Declare: %s", err)
	}

	return nil
}

func publishToQueue(body []byte) error {
	channel, err := rmqConnection.Channel()
	if err != nil {
		return fmt.Errorf("Channel: %s", err)
	}

	if err := channel.Publish(
		"test-queue", // publish to an exchange
		"test-key",   // routing to 0 or more queues
		false,        // mandatory
		false,        // immediate
		amqp.Publishing{
			Headers:         amqp.Table{},
			ContentType:     "application/json",
			ContentEncoding: "UTF-8",
			Body:            body,
			DeliveryMode:    amqp.Persistent,
			Priority:        0,
		},
	); err != nil {
		return fmt.Errorf("Exchange Publish: %s", err)
	}

	return nil
}

func createProduct(w http.ResponseWriter, r *http.Request) {

	uuid, _ := uuid.NewV4()

	body, _ := ioutil.ReadAll(r.Body)

	type createProductResponse struct {
		UUID string
		Timestamp string
	}

	response := CreateProductResponse{
		UUID: uuid.String(),
		Timestamp: time.Now().Format(time.RFC850),
	}

	publishToQueue(body)

	jsonResponse, _ := json.Marshal(response)

	io.WriteString(w, string(jsonResponse))
}
