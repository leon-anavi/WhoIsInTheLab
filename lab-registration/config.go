package main

import (
	"github.com/rakyll/globalconf"
	"flag"
	"log"
)


type Config struct {
	Host string
	Username string
	Password string
	Database string
}

func ReadConfig(filename string) (Config) {

	dbFlagSet := flag.NewFlagSet("mysql", flag.ExitOnError)

	flagUser := dbFlagSet.String("username", "", "Database username")
	flagPass := dbFlagSet.String("password", "", "Database password")
	flagHost := dbFlagSet.String("host", "localhost", "Database host")
	flagDbName := dbFlagSet.String("database", "", "Database name")

	globalconf.Register("mysql", dbFlagSet)


	conf, err := globalconf.NewWithOptions(&globalconf.Options{
		Filename: filename,
	})

	if err != nil {
		log.Fatalf(err.Error())
	}

	conf.ParseAll()

	return Config{
		Username: *flagUser,
		Host: *flagHost,
		Password: *flagPass,
		Database: *flagDbName,
	}
}

