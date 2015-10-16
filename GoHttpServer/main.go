package main

import (
	"net/http"
	"encoding/json"
	"time"
	"io/ioutil"
	"fmt"

	"github.com/nu7hatch/gouuid"
	"github.com/streadway/amqp"
	"runtime"
)

var amqpChannel *amqp.Channel

type CreateProductResponse struct{
	UUID, Timestamp string
}

func main() {
	runtime.GOMAXPROCS(runtime.NumCPU())
	if err := connectQueue(); err != nil {
		fmt.Println("Error connecting to rmq: " + err.Error())
		return
	}

	fmt.Println("Application started.")

	http.HandleFunc("/createProduct", createProduct)
	http.ListenAndServe(":30390", nil)
}

func connectQueue() error {
	rmqConnection, err := amqp.Dial("amqp://guest:guest@localhost:5672/")
	if err != nil {
		return fmt.Errorf("Dial: %s", err)
	}

	amqpChannel, err = rmqConnection.Channel()
	if err != nil {
		return fmt.Errorf("Channel: %s", err)
	}

	_, err = amqpChannel.QueueDeclare(
		"test-queue-go",
		true,
		false,
		false,
		false,
		nil,
	)
	if err != nil {
		return fmt.Errorf("Queue declare error: %s", err)
	}

	if err := amqpChannel.ExchangeDeclare(
		"test-queue", // name
		"direct",     // type
		true,         // durable
		false,        // auto-deleted
		false,        // internal
		false,        // noWait
		nil,          // arguments
	); err != nil {
		return fmt.Errorf("Exchange declare error: %s", err)
	}

	if err := amqpChannel.QueueBind("test-queue-go", "test-key", "test-queue", false, nil); err != nil {
		return fmt.Errorf("QueueBind error: %s", err)
	}

	return nil
}

func publishToQueue(body []byte) error {

	if err := amqpChannel.Publish(
		"test-queue", 
		"test-key",   
		false,        
		false,        
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

	if err := publishToQueue(body); err != nil {
		fmt.Println(err.Error())
		w.Write([]byte(err.Error()))
	} else {
		response := CreateProductResponse{
			UUID: uuid.String(),
			Timestamp: time.Now().Format(time.RFC850),
		}

		jsonResponse, _ := json.Marshal(response)
		w.Write(jsonResponse)
	}
}
