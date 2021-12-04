<?php

// $servername = "127.0.0.1";
$servername = "mysql";
// REPLACE with your Database name
$dbname = "co2_monitor";
// REPLACE with Database user
$username = "co2sensor";
// REPLACE with Database user password
$password = "co2sensorpassword";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $sql = "SELECT id, co2, temp, humidity, sample_time FROM readings order by sample_time LIMIT 100";
$sql = "SELECT * FROM readings  WHERE `sample_time` > DATE_SUB(NOW(), INTERVAL '24' HOUR) ORDER BY sample_time DESC";

$result = $conn->query($sql);

while ($data = $result->fetch_assoc()) {
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'sample_time');

// ******* Uncomment to convert readings time array to your timezone ********
/*$i = 0;
foreach ($readings_time as $reading){
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    $readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading - 1 hours"));
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    //$readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 4 hours"));
    $i += 1;
}*/

$co2series = array();

foreach ($sensor_data as $reading) {
    // array_push($co2series,$reading['sample_time'],$reading['co2']);
    // Add to $arrJSON
    // $row = array($reading['sample_time'], $reading['co2']);


    // Split timestamp into [ Y, M, D, h, m, s ]
    // var t = "2010-06-09 13:12:01".split(/[- :]/);
    // var t = $reading['sample_time'].split(/[- :]/);

    // Apply each element to the Date function
    // var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
    $timezone = new \DateTimeZone('Europe/London');
    $d = DateTime::createFromFormat('Y-m-d H:i:s', $reading['sample_time'], $timezone);
    if ($d === false) {
        die("Incorrect date string");
    } else {
        // echo $d->getTimestamp();
    }

    // $rowarray = array($reading['sample_time'], $reading['co2'] );
    $rowarray = array($d->getTimestamp()*1000, $reading['co2'] );

    $co2series[] = $rowarray;
}
// encode
$co2seriesJSON = json_encode($co2series, JSON_NUMERIC_CHECK);
// $co2seriesJSON = $co2series;
// [["2021-09-10 16:42:31",695],["2021-09-10 16:42:46",695],["2021-09-10 16:43:01",693],["2021-09-10 16:43:16",694],["2021-09-10 16:43:31",695],["2021-09-10 16:43:47",694],["2021-09-10 16:44:02",693]]
// data: [[5, 2], [6, 3], [8, 2]]
//     series: [{
//         data: [
//             [Date.UTC(2010, 0, 1), 29.9],
//             [Date.UTC(2010, 2, 1), 71.5],
//             [Date.UTC(2010, 3, 1), 106.4]
//         ]
//     }]

// $co2Values = json_encode(array_reverse(array_column($sensor_data, 'co2')), JSON_NUMERIC_CHECK);

// g https://jsfiddle.net/BlackLabel/5wsL6euf/

  // https://jsfiddle.net/gh/get/library/pure/highcharts/highcharts/tree/master/samples/highcharts/series/data-array-of-arrays-datetime/

$result->free();
$conn->close();
