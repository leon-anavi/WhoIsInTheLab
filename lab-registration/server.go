package main

import (
	"github.com/go-martini/martini"
	"net/http"
	"log"
	"./registration"
)

func main() {
	m := martini.Classic()
	m.Get("/", registration.Hello);
	log.Fatal(http.ListenAndServe(":8080", m))
}
