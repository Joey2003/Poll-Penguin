<?php
// Prevent direct access
if(basename($_SERVER['PHP_SELF']) == basename(__FILE__))
{
    http_response_code(403); // Forbidden
    echo "Forbidden";
    exit();
}

//Connect to db
$servername = "127.0.0.1";
$username = "root";
$password = "50425175";
$dbname = "poll_penguin_revamp"; // Your database name
