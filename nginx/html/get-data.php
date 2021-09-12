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



//default get to the co2 monitor server is to get ALL latest sensor readings - last sample in the db
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // echo "GET request made 2";
    $api_key = clean_input($_GET["api_key"]);//get the incoming key from the request
    if ($api_key == $api_key_value) {
        // $value1 = clean_input($_GET["value1"]);
        // $value2 = clean_input($_GET["value2"]);
        // $value3 = clean_input($_GET["value3"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM readings  ORDER BY ID DESC LIMIT 1";
        
        $result = $conn->query($sql);

        while ($data = $result->fetch_assoc()){
            $sensor_data[] = $data;
            response(200,"GOOD API key recieved from GET request",$sensor_data);
        }

        // if ($conn->query($sql) === true) {
        //     response(200,"GOOD API key recieved from GET request",99.99);
        // } else {
        //     response(200,"CANNOT CONN TO DB",99.99);
        // }
    
        $conn->close();
    } else {
        response(200,"Bad API key recieved from GET request",99.99);
    }

}

function response($status,$status_message,$data)
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
