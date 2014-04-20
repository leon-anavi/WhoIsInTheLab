package main

type User struct {
	Id int32 `db:"user_id" json:"id"`
	FirstName string `db:"user_name1" json:"firstname"`
	LastName string `db:"user_name2" json:"lastname"`
}
