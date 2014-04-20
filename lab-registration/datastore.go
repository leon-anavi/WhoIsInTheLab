package main

type DataStore interface {
	GetAllUsers() ([]User, error)
}
