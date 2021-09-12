<?php

$servername = "mysql";
// REPLACE with Database user
$username = "co2sensor";
// REPLACE with Database user password
$password = "co2sensorpassword";
// REPLACE with your Database name
$dbname = "co2_monitor";
// Keep this API Key value to be compatible with the ESP32 code provided in the project page. If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key = "";  // init all values at start of call to this script


// get N sensor readings - last samples
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $api_key = clean_input($_GET["api_key"]);//get the incoming key from the request
    if ($api_key == $api_key_value) {
        $num_records = clean_input($_GET["num_records"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM readings  ORDER BY ID DESC LIMIT " . $num_records;
        
        $result = $conn->query($sql);

        while ($data = $result->fetch_assoc()) {
            $sensor_data[] = $data;
        }
        response(200, "GOOD API key recieved from GET request", $sensor_data);

        $conn->close();
    } else {
        response(200, "Bad API key recieved from GET request", 99.99);
    }
}

function response($status, $status_message, $data)
{
    header("HTTP/1.1 ".$status);
    
    $response['status']=$status;
    $response['status_message']=$status_message;
    $response['data']=$data;
    
    $json_response = json_encode($response);
    echo $json_response;
}


function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
