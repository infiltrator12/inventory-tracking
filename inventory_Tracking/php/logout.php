<?php

//start or continue the session
session_start();

//clear all session data
session_unset();
session_destroy();

//return a success response
http_response_code(200);
echo "Logout Successfull!";
header("location: /inventory_Tracking/index.php");
