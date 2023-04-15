<!-- TOP 10 municipios con más incidentes -->
<template>
  <div class="chart-container" style="position: relative; height:50vh; width:50vw">
    <Bar v-if="loaded" :data="chartData" :options="chartOptions" />
</div>
    
  
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
  name: 'BarChart',
  components: { Bar },
  data: () => ({
    loaded: false,
    chartData: {

      labels: [],
      datasets: [{
        label: "Nº d'incidents",
        data: [],
        backgroundColor: []
      }]
    },
    chartOptions: {
      responsive: true,
      plugins: {
        legend: {
          display: false // Hide the legend
        }
      },
      scales: {
    y: {
      precision: 0 // Set precision to 0
    }
  }
    }
  }),

  async mounted() {

    axios
      .get('/Broggitecnics/public/api/incidentesChart')
      .then(response => {
        console.log(response)
        const colors = ['#FF4136', // Rojo vivo
  '#2ECC40', // Verde brillante
  '#FF851B', // Naranja brillante
  '#7FDBFF', // Azul claro
  '#FFDC00', // Amarillo brillante
  '#B10DC9', // Magenta vivo
  '#01FF70', // Verde brillante (otra variante)
  '#F012BE', // Rosa fuerte
  '#3D9970', // Verde oscuro
  '#003399'  // Dorado
]
        response.data.forEach((element, index) => {
          this.chartData.labels.push(element.nom)
          this.chartData.datasets[0].data.push(element.num_expedients)
          this.chartData.datasets[0].backgroundColor.push(colors[index % colors.length])
        });
        this.loaded = true
        this.$emit('rankingData',this.chartData)

      })
      .catch(error => {
        this.isError = true
        console.error(error)
      })
  }
}

</script>
<style scoped></style>

