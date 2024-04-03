<?php

//dbase configuration constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'inventory');

//creae databse if it does not exist
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
$DB_NAME = 'inventory';
$conn->query("CREATE DATABASE IF NOT EXISTS $DB_NAME");
$conn->close();
