var co2series = [];
//define the chart
var chartT = new Highcharts.Chart({
  chart: {
    renderTo: "container",
    type: "spline",
    zoomType: "x",
    height: (9 / 16 * 100) + '%' // 16:9 ratio
  },
  title: {
    text: "CO2 Level - ppm",
  },
  subtitle: {
    text:
      document.ontouchstart === undefined
        ? "Click and drag in the plot area to zoom in"
        : "Pinch the chart to zoom in",
  },
  series: [
    {
      type: "area",
      data: [],
      name: "CO2 ppm",
      color: "#121212",
    },
  ],
  plotOptions: {
    spline: {
      animation: true,
      dataLabels: { enabled: false },
    },
    // series: { color: "#121212" },
  },

  xAxis: {
    type: "datetime",
    dateTimeLabelFormats: { second: "%H:%M:%S" },
    // dateTimeLabelFormats: { minute: '%H:%M' },
    // dateTimeLabelFormats: { hour: '%H:%M' },
    labels: {
      style: {
          color: 'black'
      }
  }
  },
  yAxis: {
    title: { text: "CO2 - ppm" },
    min: 400,
    max: 1000,
    plotBands: [
      {
        color: "rgba(0, 255, 0, 0.5)", // Color value
        from: 300, // Start of the plot band
        to: 700, // End of the plot band
      },
      {
        color: "rgba(255,198,0, 0.5)", // Color value
        from: 701, // Start of the plot band
        to: 800, // End of the plot band
      },
      {
        // color: "#d13d44", // Color value
        color: "rgba(255, 0, 0, 0.5)",
        from: 801, // Start of the plot band
        to: 2000, // End of the plot band
      },
    ],
    labels: {
      style: {
          color: 'black'
      }
  }
  },

  plotOptions: {
    area: {
      fillColor: {
        // linearGradient: {
        //   x1: 0,
        //   y1: 0,
        //   x2: 0,
        //   y2: 1,
        // },
        linearGradient: [0, 0, 0, 500],
        stops: [
          // [0, Highcharts.getOptions().colors[0]],
          // [
          //   1,
          //   Highcharts.color(Highcharts.getOptions().colors[0])
          //     .setOpacity(0)
          //     .get("rgba"),
          // ],
          [0, "rgba(0, 0, 0, 1)"],
          [1, "rgba(0, 255, 0, 0.5)"],
        ],
      },
      marker: {
        radius: 2,
      },
      lineWidth: 1,
      states: {
        hover: {
          lineWidth: 1,
        },
      },
      threshold: null,
    },
  },

  credits: {
    enabled: false,
  },
});

Highcharts.setOptions({
  global: { useUTC: false },
});

//load last n hours of data
// var maxCo2 = 0; //to store max co2 reading

loadLast_n_HoursData();
function loadLast_n_HoursData(nHours = 4) {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    //   if (this.readyState == 4 && this.status == 200) {
    // var x = new Date().getTime();
    console.log("n hours = " + nHours);

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

    document.getElementById("co2_level").innerHTML = y;

    document.getElementById("temperature_level").innerHTML = temp;

    document.getElementById("humidity_level").innerHTML = humidity;

    document.getElementById("time").innerHTML = dt;

    var gaugeElement = document.getElementsByTagName("canvas")[0];

    gaugeElement.setAttribute("data-value", y);
    var gauge = document.gauges.get("co2-gauge");
    gauge.update();

    // co2series =
    // get apir of nums in 2 element Array
    // add 2 2 elem array to another main array -series

    var new_co2_series = [];
    var pair = [];

    var maxCo2 = 1000; //set max to a min of
    var minCo2 = 400; //set min to a min of

    console.log("current max co2 " + maxCo2);
    data_array.forEach(myFunction);
    function myFunction(value, index, array) {
      // txt += value + "<br>";
      // console.log("value");
      // console.log(value);
      pair = [];
      pair.push(new Date(value.sample_time).getTime()); //cv top utc
      pair.push(parseInt(value.co2));
      //is this the biggest c02 reading?
      if (parseInt(value.co2) > maxCo2) {
        maxCo2 = parseInt(value.co2);
        // console.log("new max co2 " + maxCo2);
        // console.log(maxCo2);
      }
      if (parseInt(value.co2) < minCo2) {
        minCo2 = parseInt(value.co2);
        // console.log("new max co2 " + maxCo2);
        // console.log(maxCo2);
      }
      // console.log("pair");
      // console.log(pair);
      new_co2_series.push(pair);
      // console.log("new_co2_series");
      // console.table(new_co2_series);
    }
    console.log("new max co2 " + maxCo2);

    //set chart max to max co2 reading in new_co2_series array
    chartT.yAxis[0].update({ max: maxCo2 });
    chartT.yAxis[0].update({ min: minCo2 });

    // chartT.yAxis[0].update({ max: 1500 });

    new_co2_series.reverse(); //reverse array

    chartT.series[0].setData(new_co2_series, true, true, true);
    //set chart max to max co2 reading in new_co2_series array
    // chartT.setExtremes(null, 100);

    //   }
  };
  xhttp.open(
    "GET",
    "/get-n-hours.php?api_key=tPmAT5Ab3j7F9&nHours=" + nHours,
    true
  );
  xhttp.send(); // console.log("new_co2_series");
  // console.table(new_co2_series);
}

//load last n records
// loadData();
// load data
function loadData(num_records = 1000) {
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

    document.getElementById("co2_level").innerHTML = y;

    document.getElementById("temperature_level").innerHTML = temp;

    document.getElementById("humidity_level").innerHTML = humidity;

    document.getElementById("time").innerHTML = dt;

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

      document.getElementById("co2_level").innerHTML = y;

      document.getElementById("temperature_level").innerHTML = temp;

      document.getElementById("humidity_level").innerHTML = humidity;

      document.getElementById("time").innerHTML = dt;

      var gaugeElement = document.getElementsByTagName("canvas")[0];

      gaugeElement.setAttribute("data-value", y);
      var gauge = document.gauges.get("co2-gauge");
      gauge.update();
    }
  };
  xhttp.open("GET", "/get-data.php?api_key=tPmAT5Ab3j7F9", true);
  xhttp.send();
}, 15000);
