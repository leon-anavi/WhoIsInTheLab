
package main

import (
	"strings"
	"os/exec"
	"log"
	"fmt"
)

func checkError(err error) {
	if err != nil {
		log.Fatal(err)
	}
}

func getArpEntry(arpFile string, ip string) (string, error) {
	grepOut, err := exec.Command("grep", "-w" , ip, arpFile).Output()
	if err != nil {
		return "", err
	}
	return string(grepOut[:]), nil
}

func GetMacAddress(arpFile string, ip string) (string, error) {
	if len(ip) == 0 {
		return "", fmt.Errorf("ip address cannot be empty")
	}
	entry, err := getArpEntry(arpFile, ip)
	if err != nil {
		return "", fmt.Errorf("Cannot find MAC address for ip: %v", ip)
	}
	fields := strings.Fields(entry)
	// This is the mac address field
	return fields[3], nil
}
