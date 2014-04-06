package main

import (
	"net/http"
	"strings"
	"os/exec"
	"log"
)

func checkError(err error) {
	if err != nil {
		log.Fatal(err)
	}
}

func getArpEntry(arpFile string, ip string) string {
	grepOut, err := exec.Command("grep", "-w" , ip, arpFile).Output()
	checkError(err)

	return string(grepOut[:])
}

func GetMacAddress(arpFile string, ip string) string {
	entry := getArpEntry(arpFile, ip)
	fields := strings.Fields(entry)
	// This is the mac address field
	return fields[3]
}

func Hello(res http.ResponseWriter, req *http.Request) string {
	ip := strings.Split(req.RemoteAddr, ":")[0]
	return GetMacAddress("/proc/net/arp", ip)
}
