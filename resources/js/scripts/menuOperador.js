import Chart from 'chart.js/auto';

const callTypeIncidents = document.getElementById('callTypeChart').getContext('2d');
const avCallDuration = document.getElementById("avCallChart").getContext("2d");

var avCallData = {
    datasets: [
        {
          data: [60, 40],
          backgroundColor: ["#75E050", "white"],
          borderWidth: 1,
        },
      ],
}
var avCallOptions = {
    aspectRatio: 2,
    cutout: '65%',
    circumference: 180,
    rotation: -90,
    plugins: {
    },
    events: []
}
var avCallDurationChart = new Chart(avCallDuration, {
  type: "doughnut",
  data: avCallData,
  options: avCallOptions
});

const callTypeData = {
    labels: ['Incendi', 'Fuita', 'Asistència sanitaria', 'Seguretat', 'Trànsit', 'Civisme', 'Meterologia' ,'Medi ambient', 'Altres incidències', 'Accident'],
    datasets: [
        {
        data: [3, 5, 3, 7, 2, 6, 8, 6, 3, 8],
        backgroundColor: 'rgba(161, 228, 177, .1)',
        borderColor: 'rgba(161, 228, 177, 1)',
        fill: true,
        borderWidth: 2,
        tension: .4,
        },
        {
        data: [10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
        backgroundColor: 'rgba(255, 255, 255, 0)',
        borderColor: 'rgba(255, 255, 2555, 0)', //rgba(0, 0, 0, 1)
        },
        {
        data: [, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        backgroundColor: 'rgba(255, 255, 255, 0)',
        borderColor: 'rgba(255, 255, 255, 0)', //rgba(0, 0, 0, 1)
        }
    ]
};

const callTypeChart = new Chart(callTypeIncidents, {
    type: 'radar',
    data: callTypeData,
    options: {
        animation: {
            duration: 0
        },
        aspectRatio: 1.5,
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            r: {
                pointLabels: {
                font: {
                    size: 12
                }
                }
            }
        },
        layout: {
            padding: 10
        }
        
    },

});