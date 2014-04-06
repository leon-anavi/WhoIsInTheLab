package main

import (
	"testing"
)

func TestGetMacAddressFromArpTable(t *testing.T) {
	ipaddr := "192.168.50.1"
	expected_mac := "00:16:0a:13:96:7e"
	actual := GetMacAddress("./test-data/arp-data", ipaddr)
	if actual != expected_mac {
		t.Errorf("expected %v, but was %v", expected_mac, actual)
	}
}
