package main

import (
	"net/http"
	"log"

	"github.com/go-martini/martini"
	"github.com/martini-contrib/render"
	"flag"
)

var configFile string

const defaultConfig = "./config/db.cfg"

func init() {
	flag.StringVar(&configFile, "config", defaultConfig, "Database config file")
}


func main() {
	flag.Parse()
	conf := ReadConfig(configFile)

	m := martini.Classic()
	m.Use(render.Renderer())

	var dataStore DataStore = CreateMysqlDataStoreFromConfig(conf)
	m.MapTo(dataStore, (*DataStore)(nil))

	m.Get("/register", RegForm);
	log.Fatal(http.ListenAndServe(":8080", m))
}
