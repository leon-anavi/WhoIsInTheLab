lab-registration
================

[![Build Status](https://travis-ci.org/ironsteel/lab-registration.png?branch=master)](https://travis-ci.org/ironsteel/lab-registration)

Hackafe device registration form for WhoIsInTheLab written in GO 

# How to build/run

1. If this is your first time dealing with GO read [this](http://golang.org/doc/code.html)
2. After cloning cd to project root dir and run **go get ./...**
3. Run **go build** to build the binary 
4. You need to setup mysql with the database from [whoIsInTheLab](https://github.com/leon-anavi/WhoIsInTheLab). The sql scripts are in the db/ directory.
5. Modify **config/db.cfg** according to your database setup.
6. Run the server with **./lab-registrarion**. You can optionaly specify a different config file location with **./lab-registration -config {path-to-config}**
7. Test the server with  [http://localhost:8080/users](http://localhost:8080/users).
8. If you try to open [http://localhost:8080/mac](http://localhost:8080/mac) you will get an error :).
   This is becouse 127.0.0.1 (eg. the client ip) is not present in the arp table.
   You should access it from a different host using the ip address assigned to your physical net adapter instead of localhost.
9. Profit :)


