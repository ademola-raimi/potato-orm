<?php

namespace Demo;

use Demo\Tweet;

class ConnectionHandler
{
    $servername = "localhost";
    $username = "mac";
    $password = "london";

    // Create connection
    $conn = new mysqli($localhost, $mac, $london);

    // Check connection
    if ($conn->connectError) {
	    die("Connection failed: " . $conn->connectError);
    } 
	    return "Connected successfully";

}

?>
