<?php include 'get_all_readings.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CO2 Monitor</title>
    <!-- <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/> -->
    <link rel="shortcut icon" type="image/png" href="favicon-32x32.png" />
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
            <canvas id="co2-gauge" data-type="radial-gauge" data-width="250" data-height="250" data-units="CO2 ppm" data-title="false" data-value="500" data-min-value="400" data-max-value="1000" data-major-ticks="400,500,600,700,800,900,1000" data-minor-ticks="2" data-stroke-ticks="false" data-value-int="1" data-value-dec="0" data-font-value="courier" data-highlights='[
      { "from": 400, "to": 700, "color": "rgba(0,255,0,.5)" },
      { "from": 700, "to": 800, "color": "rgba(255,255,0,.7)" },
      { "from": 800, "to": 1000, "color": "rgba(255,0,0,.5)" }
  
  ]' data-color-plate="#222" data-color-major-ticks="#f5f5f5" data-color-minor-ticks="#ddd" data-color-title="#fff" data-color-units="#ccc" data-color-numbers="#eee" data-color-needle-start="rgba(240, 128, 128, 1)" data-color-needle-end="rgba(255, 160, 122, .9)" data-value-box="true" data-animation-rule="bounce" data-animation-duration="500" data-animated-value="true"></canvas>
        </div>

        <div class="column middle">
            <!-- <h2>middle</h2> -->
            <div id="chart-co2" class="chart"></div>
        </div>

        <div class="column right-side">
            <h2>Latest Reading</h2>
            <!-- <h3 id="co2_level">CO<sub>2</sub> level(ppm): </h3> -->
            <!-- <div style="clear: both">
                <h3 style="float: left">CO<sub>2</sub> level(ppm): </h3>
                <h2 style="float: right">placeholder</h2>
            </div> -->
            <!-- <hr /> -->
            <div>
                <h3 class="h-inline">CO<sub>2</sub> level(ppm): </h3>
                <h2 class="h-inline" id="co2_level">value</h2>
            </div>
            <div>
                <h4 class="h-inline">Temperature (C): </h4>
                <h3 class="h-inline" id="temperature_level">value</h2>
            </div>
            <div>
                <h4 class="h-inline">Humidity (%): </h4>
                <h3 class="h-inline" id="humidity_level">Humidity (%): </h3>

            </div>
            <div>
                <h4 class="h-inline">Time : </h4>
                <h4 class="h-inline" id="time">Value : </h4>

            </div>

            <div>
                <meter class="co2_meter" id="meter_value" min="400" low="700" high="800" max="1000" optimum="600" value="500"></meter>
            </div>
            <p></p>

            <hr />
            <div>
                <button type="button" onclick="loadLast_n_HoursData(1)">1 hour</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(2)">2 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(3)">3 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(4)">4 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(6)">6 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(8)">8 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(12)">12 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(24)">24 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(48)">48 hours</button>
            </div>
            <div>
                <button type="button" onclick="loadLast_n_HoursData(76)">76 hours</button>
            </div>                        
        </div>

    </div>


    <?php echo '<pre>'; ?>;

    <!-- <?php echo 'co2seriesJSON'; ?>; -->
    <!-- <?php echo $co2seriesJSON ?>; -->

</body>

<script src="myScript.js"></script>

</html>