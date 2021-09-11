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

$sql = "SELECT id, co2, temp, humidity, sample_time FROM readings order by sample_time";

$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
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

// try data in this format

// data: [
//   [datetime, co2],
//   [1, 2],
//   [2, 8]
// ]
// x,y

$co2series = array();

foreach ($sensor_data as $reading ){
  // array_push($co2series,$reading['sample_time'],$reading['co2']);
                  // Add to $arrJSON
                  // $row = array($reading['sample_time'], $reading['co2']);


// Split timestamp into [ Y, M, D, h, m, s ]
// var t = "2010-06-09 13:12:01".split(/[- :]/);
// var t = $reading['sample_time'].split(/[- :]/);

// Apply each element to the Date function
// var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
$d = DateTime::createFromFormat('Y-m-d H:i:s', $reading['sample_time']);
if ($d === false) {
    die("Incorrect date string");
} else {
    echo $d->getTimestamp();
}

  // $rowarray = array($reading['sample_time'], $reading['co2'] );
  $rowarray = array($d->getTimestamp(), $reading['co2'] );

  $co2series[] = $rowarray;
}
// encode
$co2seriesJSON = json_encode($co2series,JSON_NUMERIC_CHECK);
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
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>CO2 Monitor</title>
  <!-- <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/> -->
  <link rel="shortcut icon" type="image/png" href="favicon-32x32.png"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200&display=swap" rel="stylesheet">

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="//cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.7/radial/gauge.min.js"></script>

  
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Titillium Web', sans-serif;
    }

    body {
      margin: 25px;
      background-color: rgb(127, 156, 126);
    }

    /* Style the header */
    .header {
      /* background-color: #f1f1f1; */
      padding: 10px;
      text-align: center;
    }

    /* Style the top navigation bar */
    .topnav {
      overflow: hidden;
      background-color: #333;
    }

    /* Style the topnav links */
    .topnav a {
      float: left;
      display: block;
      color: #f2f2f2;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }

    /* Change color on hover */
    .topnav a:hover {
      background-color: #ddd;
      color: black;
    }

    /* Create three unequal columns that floats next to each other */
    .column {
      float: left;
      padding: 10px;
      background-color: rgb(177, 107, 107);
      margin: 10px;

    }

    /* Left and right column */
    .column.left-side {
      width: 275px;
    }

    /* Middle column */
    .column.middle {
      width: 63%;
    }

    .chart {
      width: auto;
      height: 400px;
    }

    .column.right-side {
      width: 275px;
      /* height: 100%; */
    }

    .row {
      border: black;
      border-style: solid;
      background-color: rgb(109, 68, 68);
      border-radius: 10px;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    meter {
      width: 200px;
      height: 50px;
      border: 1px solid rgb(29, 27, 27);
      border-radius: 3px;

      padding-left: 0;
      padding-right: 0;
      margin-left: auto;
      margin-right: auto;
      display: block;
      /* width: 800px; */
    }

    /* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
    @media screen and (max-width: 600px) {

      .column.left-side,
      .column.right-side,

      .column.middle {
        width: 100%;
      }
    }
  </style>
</head>

<body>

  <div class="header">
    <h1>CO<sub>2</sub> Monitor</h1>
  </div>

  <div class="row">
    <div class="column left-side">
      <h2>Sensor</h2>
      <canvas id="co2-gauge" data-type="radial-gauge" data-width="250" data-height="250" data-units="CO2 ppm"
        data-title="false" data-value="400" data-min-value="400" data-max-value="1400"
        data-major-ticks="400,600,800,1000,1200,1400" data-minor-ticks="2" data-stroke-ticks="false"
        data-value-int="1" data-value-dec="0" data-font-value="courier" data-highlights='[
      { "from": 400, "to": 700, "color": "rgba(0,255,0,.5)" },
      { "from": 700, "to": 800, "color": "rgba(255,255,0,.5)" },
      { "from": 800, "to": 1400, "color": "rgba(255,0,0,.5)" }
  
  ]' data-color-plate="#222" data-color-major-ticks="#f5f5f5" data-color-minor-ticks="#ddd" data-color-title="#fff"
        data-color-units="#ccc" data-color-numbers="#eee" data-color-needle-start="rgba(240, 128, 128, 1)"
        data-color-needle-end="rgba(255, 160, 122, .9)" data-value-box="true" data-animation-rule="bounce"
        data-animation-duration="500" data-animated-value="true"></canvas>
    </div>

    <div class="column middle">
      <!-- <h2>middle</h2> -->
      <div id="chart-co2" class="chart"></div>
    </div>

    <div class="column right-side">
      <h2>Readings</h2>
      <h3 id="co2_level">CO<sub>2</sub> level(ppm): </h3>
      <h4 id="temperature_level">Temperature (C): </h4>
      <h4 id="humidity_level">Humidity (%): </h4>
      <h4 id="time">Time : </h4>
      <div>
        <meter class="co2_meter" id="meter_value" min="400" low="700" high="1400" max="1800" optimum="500"
          value="400"></meter>
      </div>
    </div>

  </div>

  <?php echo '<pre>'; ?>;

  <?php echo 'co2seriesJSON'; ?>;
  <?php echo $co2seriesJSON ?>;




</body>


<script>
var co2series = {};
  co2series = <?php  print_r($co2seriesJSON); ?>;
  // var co2series = [["2021-09-10 16:42:31",695],["2021-09-10 16:42:46",695],["2021-09-10 16:43:01",693]];
// console.log(co2series);
  var chartT = new Highcharts.Chart({
    chart: { renderTo: "chart-co2", type: 'spline', zoomType: 'x' },
    title: { text: "CO2 Level - ppm" },
    series: [{
        data: co2series
      }],
    plotOptions: {
      spline: {
        animation: true,
        dataLabels: { enabled: false },
      },
      series: { color: "#000000" },
      
    },
    xAxis: {
      type: 'datetime',
      dateTimeLabelFormats: { hour: '%H:%M' },
    },
    yAxis: {
      title: { text: "CO2 - ppm" },
      plotBands: [
        {
          color: "#9cc940", // Color value
          from: 300, // Start of the plot band
          to: 700, // End of the plot band
        },
        {
          color: "#f0cf56", // Color value
          from: 700, // Start of the plot band
          to: 800, // End of the plot band
        },
        {
          color: "#d13d44", // Color value
          from: 800, // Start of the plot band
          to: 1800, // End of the plot band
        },
      ],
    },

    credits: { enabled: false },
  });
  Highcharts.setOptions({
    global: { useUTC: false }
  });

//update co2 values on screen
  setInterval(function () {
    // var xhttp = new XMLHttpRequest();
    // xhttp.onreadystatechange = function () {
    //   if (this.readyState == 4 && this.status == 200) {
    //     var x = new Date().getTime(),
    //       y = parseInt(this.responseText);
    //     console.log(this.responseText);
    //     if (chartT.series[0].data.length > 2160) {
    //       chartT.series[0].addPoint([x, y], true, true, true);
    //     } else {
    //       chartT.series[0].addPoint([x, y], true, false, true);
    //     }

        // var points = chartT.series[0].groupedData;
    // var lastPoint = points[points.length - 1];
          var lastPoint = co2series[co2series.length-1][1];
        document.getElementById("meter_value").value = lastPoint;

        let str = document.getElementById("co2_level").innerHTML
        encharloc = str.lastIndexOf(":")
        str.substring(0, encharloc)
        document.getElementById("co2_level").innerHTML = str.substring(0, encharloc + 2) + lastPoint

        document.getElementById("co2_level").innerHTML = String(document.getElementById("co2_level").innerHTML).substring(0, String(document.getElementById("co2_level").innerHTML).lastIndexOf(":") + 2) + lastPoint;



        var gaugeElement = document.getElementsByTagName("canvas")[0];

        gaugeElement.setAttribute("data-value", lastPoint);
        var gauge = document.gauges.get("co2-gauge");
        gauge.update();
    //   }
    // };
    // xhttp.open("GET", "/co2", true);
    // xhttp.send();
    // every 15 secs
  }, 15000);
        // var s = document.getElementById(meter_value).value;
        // s.value = y;
                // document.getElementById("co2_level").innerHTML = y;

  //! fetch temperature periodically
  // setInterval(function () {
  //   var xhttp = new XMLHttpRequest();
  //   xhttp.onreadystatechange = function () {
  //     if (this.readyState == 4 && this.status == 200) {
  //       var x = new Date().getTime(),
  //         y = parseFloat(this.responseText);
  //       console.log(this.responseText);

  //       // document.getElementById("temperature_level").innerHTML = y;
  //       document.getElementById("temperature_level").innerHTML = String(document.getElementById("temperature_level").innerHTML).substring(0, String(document.getElementById("temperature_level").innerHTML).lastIndexOf(":") + 2) + y;

  //     }
  //   };
  //   xhttp.open("GET", "/temperature", true);
  //   xhttp.send();
  // }, 30000); // 30 secs

  // //! fetch humidity_level periodically
  // setInterval(function () {
  //   var xhttp = new XMLHttpRequest();
  //   xhttp.onreadystatechange = function () {
  //     if (this.readyState == 4 && this.status == 200) {
  //       var x = new Date().getTime(),
  //         y = parseFloat(this.responseText);
  //       console.log(this.responseText);
  //       // document.getElementById("humidity_level").innerHTML = y;
  //       document.getElementById("humidity_level").innerHTML = String(document.getElementById("humidity_level").innerHTML).substring(0, String(document.getElementById("humidity_level").innerHTML).lastIndexOf(":") + 2) + y;

  //     }
  //   };
  //   xhttp.open("GET", "/humidity", true);
  //   xhttp.send();
  // }, 30000); // 30 secs


  //! fetch time periodically
  // setInterval(function () {

  //   var x = new Date().getTime();
  //   // var datestringx = x.getHours() + ":" + x.getMinutes() + ":" + x.getSeconds();

  //   var d = new Date();

  //   var datestringd = d.getDate() + "-" + (d.getMonth() + 1) + "-" + d.getFullYear() + " " +
  //     d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();

  //   // console.log(x);
  //   // console.log(d);

  //   // document.getElementById("time").innerHTML = datestringd;
  //   // document.getElementById("time").innerHTML = document.getElementById("time").innerHTML.substring(0, document.getElementById("time").innerHTML.lastIndexOf(":") +2) + datestringd;
  //   document.getElementById("time").innerHTML = String(document.getElementById("time").innerHTML).substring(0, String(document.getElementById("time").innerHTML).indexOf(":") + 2) + datestringd;

  // }, 1000);
</script>

</html>