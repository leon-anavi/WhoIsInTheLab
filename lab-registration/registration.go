package main

import (
	"net/http"
	"strings"

	"github.com/martini-contrib/render"
)

func RegForm(res http.ResponseWriter, req *http.Request, r render.Render) {
	ip := strings.Split(req.RemoteAddr, ":")[0]
	mac, err := GetMacAddress("/proc/net/arp", ip)
	if err != nil {
		strErr := err.Error()
		r.HTML(200, "register", strErr)
		return
	}
	r.HTML(200, "register", mac)
}
