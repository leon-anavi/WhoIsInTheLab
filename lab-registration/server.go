package main

import (
	"github.com/go-martini/martini"
	"net/http"
	"log"
)

func main() {
	m := martini.Classic()
	m.Get("/", Hello);
	log.Fatal(http.ListenAndServe(":8080", m))
}
