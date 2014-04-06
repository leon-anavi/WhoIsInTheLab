lab-registration
================

[![Build Status](https://travis-ci.org/ironsteel/lab-registration.png?branch=master)](https://travis-ci.org/ironsteel/lab-registration)

Hackafe device registration form for WhoIsInTheLab written in GO 

# How to build/run

1. If this is your first time dealing with GO read [this](http://golang.org/doc/code.html)
2. After cloning cd to project root dir and run *go get ./...*
3. To run the server type *go install* to build the binary and then *lab-registration* to run the server
4. Server will be running on [http://localhost:8080] (http://localhost:8080/)
5. If you try to open the address in a browser you will get and error :).
   This is becouse 127.0.0.1 (eg. the client ip) is not present in the arp table.
   You should access it from a different host using the ip address assigned to your physical net adapter instead of localhost.
6. Profit :)


