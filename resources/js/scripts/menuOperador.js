import Chart from 'chart.js/auto';

var ctx = document.getElementById("myChart").getContext("2d");

var data = {
    datasets: [
        {
          data: [50, 50],
          backgroundColor: ["#75E050", "white"],
          borderWidth: 1,
        },
      ],
}

var options = {
aspectRatio: 2,
cutout: '70%',
circumference: 180,
rotation: -90,
events: []
}

var myChart = new Chart(ctx, {
  type: "doughnut",
  data: data,
  options: options
});