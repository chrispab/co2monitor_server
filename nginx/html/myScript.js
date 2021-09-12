var co2series = [];
//define the chart
var chartT = new Highcharts.Chart({
  chart: { renderTo: "chart-co2", type: "spline", zoomType: "x" },
  title: { text: "CO2 Level - ppm" },
  series: [
    {
      data: [],
      name: "CO2 ppm",
    },
  ],
  plotOptions: {
    spline: {
      animation: true,
      dataLabels: { enabled: false },
    },
    series: { color: "#000000" },
  },
  xAxis: {
    type: "datetime",
    dateTimeLabelFormats: { second: "%H:%M:%S" },
    // dateTimeLabelFormats: { minute: '%H:%M' },
    // dateTimeLabelFormats: { hour: '%H:%M' },
  },
  yAxis: {
    title: { text: "CO2 - ppm" },
    min: 500,
    max: 1000,
    plotBands: [
      {
        color: "#9cc940", // Color value
        from: 500, // Start of the plot band
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
  global: { useUTC: false },
});

//   co2series = <?php  print_r($co2seriesJSON); ?>;
// co2series = [];
loadData();
// load data
function loadData(num_records=1000) {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    //   if (this.readyState == 4 && this.status == 200) {
    // var x = new Date().getTime();
    var y = parseInt(this.responseText);
    console.log("whole reponse text ");
    console.log(this.responseText);
    //get the co2 reading in var
    var json = JSON.parse(this.response);
    var data_array = []; //.co2;
    data_array = json.data; //.co2;
    console.log("data_array");
    console.log(data_array);

    co2_reading = data_array[0].co2;
    console.log(co2_reading);

    dt = data_array[0].sample_time;
    console.log(dt);

    var temp = data_array[0].temp;
    var humidity = data_array[0].humidity;

    // x=dt;
    // var x = new Date(dt).getTime();
    // console.log(x);
    y = parseInt(co2_reading);

    document.getElementById("meter_value").value = y;

    let str = document.getElementById("co2_level").innerHTML;
    encharloc = str.lastIndexOf(":");
    str.substring(0, encharloc);
    document.getElementById("co2_level").innerHTML =
      str.substring(0, encharloc + 2) + y;

    document.getElementById("co2_level").innerHTML =
      String(document.getElementById("co2_level").innerHTML).substring(
        0,
        String(document.getElementById("co2_level").innerHTML).lastIndexOf(
          ":"
        ) + 2
      ) + y;

    document.getElementById("temperature_level").innerHTML =
      String(document.getElementById("temperature_level").innerHTML).substring(
        0,
        String(
          document.getElementById("temperature_level").innerHTML
        ).lastIndexOf(":") + 2
      ) + temp;
    document.getElementById("humidity_level").innerHTML =
      String(document.getElementById("humidity_level").innerHTML).substring(
        0,
        String(document.getElementById("humidity_level").innerHTML).lastIndexOf(
          ":"
        ) + 2
      ) + humidity;

    document.getElementById("time").innerHTML =
      String(document.getElementById("time").innerHTML).substring(
        0,
        String(document.getElementById("time").innerHTML).indexOf(":") + 2
      ) + dt;

    var gaugeElement = document.getElementsByTagName("canvas")[0];

    gaugeElement.setAttribute("data-value", y);
    var gauge = document.gauges.get("co2-gauge");
    gauge.update();

    // co2series =
    // get apir of nums in 2 element Array
    // add 2 2 elem array to another main array -series

    var new_co2_series = [];
    var pair = [];

    data_array.forEach(myFunction);
    function myFunction(value, index, array) {
      // txt += value + "<br>";
      console.log("value");
      console.log(value);
      pair = [];
      pair.push(new Date(value.sample_time).getTime()); //cv top utc
      pair.push(parseInt(value.co2));
      console.log("pair");
      console.log(pair);
      new_co2_series.push(pair);
      console.log("new_co2_series");
      console.log(new_co2_series);
    }

    //reverse array
    new_co2_series.reverse();
    chartT.series[0].setData(new_co2_series, true, true, true);
    //   }
  };
  xhttp.open(
    "GET",
    "/get-n-records.php?api_key=tPmAT5Ab3j7F9&num_records=" + num_records,
    true
  );
  xhttp.send();
}
// var co2series = [["2021-09-10 16:42:31",695],["2021-09-10 16:42:46",695],["2021-09-10 16:43:01",693]];
console.log(co2series);

//update values on screen
setInterval(function () {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var x = new Date().getTime();
      var y = parseInt(this.responseText);
      console.log("whole reponse text ");
      console.log(this.responseText);
      //get the co2 reading in var
      var json = JSON.parse(this.response);
      var data_array = json.data; //.co2;
      console.log(data_array);
      co2_reading = data_array[0].co2;
      console.log(co2_reading);
      dt = data_array[0].sample_time;
      console.log(dt);

      var temp = data_array[0].temp;
      var humidity = data_array[0].humidity;
      // x=dt;
      (x = new Date(dt).getTime()), console.log(x);
      y = parseInt(co2_reading);
      //get time in a var

      if (chartT.series[0].data.length > 2160) {
        chartT.series[0].addPoint([x, y], true, true, true);
      } else {
        chartT.series[0].addPoint([x, y], true, false, true);
      }

      // var lastPoint = co2series[co2series.length-1][1];
      document.getElementById("meter_value").value = y;

      let str = document.getElementById("co2_level").innerHTML;
      encharloc = str.lastIndexOf(":");
      str.substring(0, encharloc);
      document.getElementById("co2_level").innerHTML =
        str.substring(0, encharloc + 2) + y;

      document.getElementById("co2_level").innerHTML =
        String(document.getElementById("co2_level").innerHTML).substring(
          0,
          String(document.getElementById("co2_level").innerHTML).lastIndexOf(
            ":"
          ) + 2
        ) + y;

      document.getElementById("temperature_level").innerHTML =
        String(
          document.getElementById("temperature_level").innerHTML
        ).substring(
          0,
          String(
            document.getElementById("temperature_level").innerHTML
          ).lastIndexOf(":") + 2
        ) + temp;
      document.getElementById("humidity_level").innerHTML =
        String(document.getElementById("humidity_level").innerHTML).substring(
          0,
          String(
            document.getElementById("humidity_level").innerHTML
          ).lastIndexOf(":") + 2
        ) + humidity;

      document.getElementById("time").innerHTML =
        String(document.getElementById("time").innerHTML).substring(
          0,
          String(document.getElementById("time").innerHTML).indexOf(":") + 2
        ) + dt;

      var gaugeElement = document.getElementsByTagName("canvas")[0];

      gaugeElement.setAttribute("data-value", y);
      var gauge = document.gauges.get("co2-gauge");
      gauge.update();
    }
  };
  xhttp.open("GET", "/get-data.php?api_key=tPmAT5Ab3j7F9", true);
  xhttp.send();
}, 15000);
