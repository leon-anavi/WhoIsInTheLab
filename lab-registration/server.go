package main

import (
	"net/http"
	"log"

	"github.com/go-martini/martini"
)

func main() {
	m := martini.Classic()
	m.Get("/", Hello);
	log.Fatal(http.ListenAndServe(":8080", m))
}
