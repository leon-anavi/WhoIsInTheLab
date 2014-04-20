package main

import (
	"net/http"
	"strings"
	"github.com/martini-contrib/encoder"
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
