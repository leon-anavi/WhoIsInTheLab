package main

import (
	"net/http"
	"log"

	"github.com/go-martini/martini"
	"github.com/martini-contrib/render"
)

func main() {
	conf := ReadConfig("./config/db.cfg")

	m := martini.Classic()
	m.Use(render.Renderer())

	var dataStore DataStore = CreateMysqlDataStoreFromConfig(conf)
	m.MapTo(dataStore, (*DataStore)(nil))

	m.Get("/register", RegForm);
	log.Fatal(http.ListenAndServe(":8080", m))
}
