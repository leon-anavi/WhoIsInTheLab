package main

import (
	"net/http"
	"strings"
	"strconv"

	"github.com/martini-contrib/encoder"
	"github.com/go-martini/martini"
)


func GetMac(res http.ResponseWriter, req *http.Request, enc encoder.Encoder) (int, []byte) {
	ip := strings.Split(req.RemoteAddr, ":")[0]
	mac, err := GetMacAddress("/proc/net/arp", ip)
	if err != nil {
		return http.StatusNotFound, encoder.Must(enc.Encode(NewError(ErrMacNotFound, err.Error())))
	}
	return http.StatusOK, encoder.Must(enc.Encode(map[string]string {"mac":mac,}))
}

func GetUsers(dataStore DataStore, enc encoder.Encoder) (int, []byte) {
	users, err := dataStore.GetAllUsers()
	if err != nil {
		return http.StatusNoContent, encoder.Must(enc.Encode(NewError(ErrInternal, "Can't get users try again later")))
	}
	return http.StatusOK, encoder.Must(enc.Encode(users))
}

func GetUser(dataStore DataStore, enc encoder.Encoder, params martini.Params) (int, []byte) {
	id, err := strconv.Atoi(params["id"])
	if err != nil {
		return http.StatusNotFound, encoder.Must(enc.Encode(NewError(ErrInternal, "Invalid id")))
	}

	user, dbErr := dataStore.GetUser(id)
	if dbErr != nil {
		return http.StatusNotFound, encoder.Must(enc.Encode(NewError(ErrUserNotFound, dbErr.Error())))
	}
	return http.StatusOK, encoder.Must(enc.Encode(user))
}
