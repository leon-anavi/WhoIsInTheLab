package main

import (
	"net/http"
	"log"

	"github.com/go-martini/martini"
	"github.com/martini-contrib/render"
)

func main() {
	m := martini.Classic()
	m.Use(render.Renderer())
	m.Get("/register", RegForm);
	log.Fatal(http.ListenAndServe(":8080", m))
}
