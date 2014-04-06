package main

import (
	"testing"
)

func TestGetMacAddressFromArpTable(t *testing.T) {
	want := "00:16:0a:13:96:7e"
	got := GetMacAddress("./test-data/arp-data", "192.168.50.1")
	if want != got {
		t.Errorf("expected %v, but was %v", want, got)
	}
}
