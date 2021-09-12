
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

  <link rel="stylesheet" href="mystyle.css">

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
        <meter class="co2_meter" id="meter_value" min="400" low="700" high="800" max="1200" optimum="600"
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
      dateTimeLabelFormats: { second: '%H:%M:%S' },
      // dateTimeLabelFormats: { minute: '%H:%M' },
      // dateTimeLabelFormats: { hour: '%H:%M' },
    },
    yAxis: {
      title: { text: "CO2 - ppm" },
      min: 400,
      max: 1000,
      plotBands: [
        {
          color: "#9cc940", // Color value
          from: 400, // Start of the plot band
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
          to: 1000, // End of the plot band
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



</script> 

</html>