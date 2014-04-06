package main

import (
	"net/http"
	"fmt"
	"strings"
	"os/exec"
	"io/ioutil"
	"log"
)

func check_error(err error) {
	if err != nil {
		fmt.Println(err)
		log.Fatal(err)
	}
}

func exec_arp_search(arp_file string, ipAddr string) string {
	arp_list := exec.Command("cat", arp_file)
	out, err := arp_list.Output()
	check_error(err)

	ioutil.WriteFile("/tmp/arp_list", out, 0644)

	out_grep, err := exec.Command("grep", "-w" , ipAddr, "/tmp/arp_list").Output()
	check_error(err)

	return string(out_grep[:])

}

func GetMacAddress(arp_file string, ipaddr string) string {
	arp_info := exec_arp_search(arp_file, ipaddr)
	fields := strings.Fields(arp_info)
	return fields[3]
}

func Hello(res http.ResponseWriter, req *http.Request) string {
	ipaddr := strings.Split(req.RemoteAddr, ":")[0]
	return GetMacAddress("/proc/net/arp", ipaddr)
}
