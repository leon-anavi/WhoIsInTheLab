package main

import (
	"net/http"
	"log"

	"github.com/go-martini/martini"
	"github.com/martini-contrib/encoder"
	"flag"
)

var configFile string

const defaultConfig = "./config/db.cfg"

func init() {
	flag.StringVar(&configFile, "config", defaultConfig, "Database config file")
}

func ReqInterceptor(c martini.Context, w http.ResponseWriter, r *http.Request) {
	c.MapTo(encoder.JsonEncoder{}, (*encoder.Encoder)(nil))
	w.Header().Set("Content-Type", "application/json")
}


func main() {
	flag.Parse()
	conf := ReadConfig(configFile)

	m := martini.New()
	m.Use(martini.Recovery())
	m.Use(martini.Logger())
	m.Use(ReqInterceptor)
	var dataStore DataStore = CreateMysqlDataStoreFromConfig(conf)
	m.MapTo(dataStore, (*DataStore)(nil))

	r := martini.NewRouter()
	r.Get("/mac", GetMac)
	r.Get("/users", GetUsers)
	m.Action(r.Handle)

	log.Fatal(http.ListenAndServe(":8080", m))
}
