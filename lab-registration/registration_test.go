package main

import (
	"testing"
)

func TestGetMacAddressFromArpTable(t *testing.T) {
	want := "00:16:0a:13:96:7e"
	got, _ := GetMacAddress("./test-data/arp-data", "192.168.50.1")
	if want != got {
		t.Errorf("expected %v, but was %v", want, got)
	}
}

func TestFailureWithInvalidArpFile(t *testing.T) {
	_, err := GetMacAddress("/some/nonexisting/file", "192.168.50.1")

	if err == nil {
		t.Errorf("should return error since file dosent exist")
	}
}

func TestFailureWithNonExistingIp(t *testing.T) {
	_, err := GetMacAddress("./test-data/arp-data", "192.168.50.234")

	if err == nil {
		t.Errorf("should return error since this ip dosen't exist")
	}
}

func TestFailureWithEmptyIp(t *testing.T) {
	_, err := GetMacAddress("./test-data/arp-data", "")

	if err == nil {
		t.Errorf("should return error since ip address is empty")
	}
}
