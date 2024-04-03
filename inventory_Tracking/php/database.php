<!-- This file creates tables automatically. -->
<?php

require_once 'config.php';

try {
    $db = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //add the database creation query
    $createDatabase = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    $db->exec($createDatabase);

    //connect to the specific database
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
// creating tables now

try {
    $createAllocationsTable = "CREATE TABLE allocations (
                            serialnumber varchar(40) PRIMARY KEY ,
                            assettag VARCHAR(40) NOT NULL, employeenumber varchar(40) not null, employeename varchar(40) not null, 
                            allocationdate varchar(40), deallocationdate varchar(40))";
    $db->exec($createAllocationsTable);
    echo "Allocations Table Created.";

    $createLoginsTable = "CREATE TABLE logins (
                            id INT PRIMARY KEY AUTO_INCREMENT,
                            username VARCHAR(255) NOT NULL,
                            password VARCHAR(255) NOT NULL, roles varchar(40))";
    $db->exec($createLoginsTable);
    echo "Logins Table Created.";

    $createUsersTable = "CREATE TABLE users (employeenumber varchar(40) primary key,
                        employeesurname varchar(40), employeefirstname varchar(40),
                        groups varchar(20), department varchar(30))";

    $db->exec($createUsersTable);
    echo "Users Table created successfully";

    $createLaptopsTable = "CREATE TABLE laptops (serialnumber varchar(40) primary key,
                            assettag varchar(40), model varchar(40), storagecapacity varchar(40),
                            storagetype varchar(40), ramcapacity varchar(40), employeename varchar(40),
                            employeenumber varchar(40), department varchar(40), dateissued varchar(40),
                            devicecondition varchar(100), allocation varchar(30), description varchar(50))";
    $db->exec($createLaptopsTable);
    echo "Laptops Table created successfully";

    $createVirtualTable = "CREATE TABLE vservers (id int(11) primary key AUTO_INCREMENT,
                            name varchar(40), host varchar(40), provisionedspace varchar(20),
                            usedspace varchar(20), operatingsystem varchar(30), memorysize varchar(40),
                            ipaddress varchar(40), application varchar(40), environment varchar(40))";
    $db->exec($createVirtualTable);
    echo "Virtual Servers Table created successfully";

    $createDevicesTable = "CREATE TABLE devices (assettag varchar(40) primary key, 
                            model varchar(40), make varchar(40), type varchar(30),
                            ipaddress varchar(40))";
    $db->exec($createDevicesTable);
    echo "Devices Table created successfully";

    $createPhysicalTable = "CREATE TABLE pservers (serialnumber varchar(40) primary key,
                           assettag varchar(40), model varchar(40), make varchar(40),
                            host varchar(30), ipaddress varchar(40))";
    $db->exec($createPhysicalTable);
    echo "Physical Servers Table Created Successfully.";

    $createHistoryTable = "CREATE TABLE history (device_serialnumber varchar(40) primary key, device_assettag varchar(30),
                            employeenumber varchar(30), employeesurname varchar(40),
                            startdate varchar(20), enddate varchar(20))";
    $db->exec($createHistoryTable);
    echo "History Table created successfully.";

    $createPhonesTable = "CREATE TABLE phones (assettag varchar(30) primary key, extension varchar(10),
                            mac_address varchar(40), model varchar(30), employeenumber varchar(40),
                            employeefirstname varchar(40), department varchar(20))";
    $db->exec($createPhonesTable);
    echo "Phones Tables created successfully.";

    $createConditionsTable = "CREATE TABLE conditions(serialnumber varchar(20) primary key, assettag varchar(20),
                                employeenumber varchar(30), emploeename varchar(30), conditions varchar(100))";
    $db->exec($createConditionsTable);
    echo "Conditions Table created successfully.";
} catch (PDOException $e) {
    echo "Error creating Tables: " . $e->getMessage();
}
