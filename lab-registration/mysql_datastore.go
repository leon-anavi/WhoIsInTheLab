package main

import (
	 "log"
	"fmt"

	"github.com/jmoiron/sqlx"
	_"github.com/go-sql-driver/mysql"
)

type MySqlDatastore struct {
	db *sqlx.DB
}

func CreateMySqlDatastore(user, pass, host, dbName string) (DataStore) {
	db, err := sqlx.Connect("mysql", user + ":" + pass + "@tcp(" + host +")/" + dbName)
	if (err != nil) {
		log.Fatal(err)
	}
	var dataStore DataStore = &MySqlDatastore{db}
	return dataStore
}

func CreateMysqlDataStoreFromConfig(config Config) (DataStore) {
	return CreateMySqlDatastore(config.Username, config.Password, config.Host, config.Database)
}


func (d *MySqlDatastore) GetAllUsers() ([]SimpleUser, error) {
	users := []SimpleUser{}

	dbError := d.db.Select(&users, "SELECT user_id, user_name1, user_name2 from who_users")
	if dbError != nil {
		return nil, dbError
	}
	return users, dbError
}

func (d *MySqlDatastore) GetUser(id int) (User, error) {
	user := User{}
	err := d.db.Get(&user, "SELECT * FROM who_users WHERE user_id=?", id)
	if err != nil {
		return user, fmt.Errorf("User with id %d not found", id)
	}

	return user, nil
}


