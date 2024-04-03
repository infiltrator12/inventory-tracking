<?php

function logMessage($message, $loglevel = "INFO")
{
    $timestamp = date("Y-m-d H:i:s");
    $logMessage = "[$loglevel] $timestamp - $message" . PHP_EOL;
    error_log($logMessage, 3, "http://localhost/inventory-Tracking/logfile.log");
}
