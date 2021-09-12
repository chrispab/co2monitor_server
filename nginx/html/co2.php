
<?php include 'get_all_readings.php';?>
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
        data-title="false" data-value="400" data-min-value="400" data-max-value="1200"
        data-major-ticks="400,600,800,1000,1200" data-minor-ticks="2" data-stroke-ticks="false"
        data-value-int="1" data-value-dec="0" data-font-value="courier" data-highlights='[
      { "from": 400, "to": 700, "color": "rgba(0,255,0,.5)" },
      { "from": 700, "to": 800, "color": "rgba(255,255,0,.5)" },
      { "from": 800, "to": 1200, "color": "rgba(255,0,0,.5)" }
  
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
      <h2>Latest Reading</h2>
      <h3 id="co2_level">CO<sub>2</sub> level(ppm): </h3>
      <h4 id="temperature_level">Temperature (C): </h4>
      <h4 id="humidity_level">Humidity (%): </h4>
      <h4 id="time">Time : </h4>
      <div>
        <meter class="co2_meter" id="meter_value" min="400" low="700" high="1400" max="1200" optimum="500"
          value="400"></meter>
      </div>
    </div>

  </div>

  <?php echo '<pre>'; ?>;

  <!-- <?php echo 'co2seriesJSON'; ?>; -->
  <!-- <?php echo $co2seriesJSON ?>; -->




</body>


<script>
var co2series = {};
  co2series = <?php  print_r($co2seriesJSON); ?>;
  // co2series = [];

  // var co2series = [["2021-09-10 16:42:31",695],["2021-09-10 16:42:46",695],["2021-09-10 16:43:01",693]];
console.log(co2series);
  var chartT = new Highcharts.Chart({
    chart: { renderTo: "chart-co2", type: 'spline', zoomType: 'x' },
    title: { text: "CO2 Level - ppm" },
    series: [{
        data: co2series,
        name: "CO2 ppm"
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
      // dateTimeLabelFormats: { hour: '%H:%M' },
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
          to: 1200, // End of the plot band
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
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var x = new Date().getTime();
        var y = parseInt(this.responseText);
          console.log("whole reponse text ")
        console.log(this.responseText);
        //get the co2 reading in var
        var json = JSON.parse(this.response);
        var data_array = json.data;//.co2;
        console.log(data_array);
        co2_reading = data_array[0].co2;
        console.log(co2_reading);
        dt = data_array[0].sample_time;
        console.log(dt);

        var temp = data_array[0].temp;
        var humidity = data_array[0].humidity;
        // x=dt;
        x = new Date(dt).getTime(),
        console.log(x);
        y=parseInt(co2_reading);
        //get time in a var

        if (chartT.series[0].data.length > 2160) {
          chartT.series[0].addPoint([x, y], true, true, true);
        } else {
          chartT.series[0].addPoint([x, y], true, false, true);
        }
        // chartT.xAxis[0].isDirty = true;
        // chartT.redraw();
        // var points = chartT.series[0].groupedData;
    // var lastPoint = points[points.length - 1];
          var lastPoint = co2series[co2series.length-1][1];
        document.getElementById("meter_value").value = y;

        let str = document.getElementById("co2_level").innerHTML
        encharloc = str.lastIndexOf(":")
        str.substring(0, encharloc)
        document.getElementById("co2_level").innerHTML = str.substring(0, encharloc + 2) + y

        document.getElementById("co2_level").innerHTML = String(document.getElementById("co2_level").innerHTML).substring(0, String(document.getElementById("co2_level").innerHTML).lastIndexOf(":") + 2) + y;
        
        document.getElementById("temperature_level").innerHTML = String(document.getElementById("temperature_level").innerHTML).substring(0, String(document.getElementById("temperature_level").innerHTML).lastIndexOf(":") + 2) + temp;
        document.getElementById("humidity_level").innerHTML = String(document.getElementById("humidity_level").innerHTML).substring(0, String(document.getElementById("humidity_level").innerHTML).lastIndexOf(":") + 2) + humidity;

        document.getElementById("time").innerHTML = String(document.getElementById("time").innerHTML).substring(0, String(document.getElementById("time").innerHTML).indexOf(":") + 2) + dt;


        var gaugeElement = document.getElementsByTagName("canvas")[0];

        gaugeElement.setAttribute("data-value", y);
        var gauge = document.gauges.get("co2-gauge");
        gauge.update();
      }
    };
    xhttp.open("GET", "/get-data.php?api_key=tPmAT5Ab3j7F9", true);
    xhttp.send();
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