package main

type User struct {
	Id int32 `db:"user_id"`
	FirstName string `db:"user_name1"`
	LastName string `db:"user_name2"`
}
