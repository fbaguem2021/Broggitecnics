<!-- TOP 10 llamadas por tipos de incidente -->
<template>
    <div class="chart-container" style="position: relative; height:50vh; width:50vw">
        <Doughnut v-if="loaded" :data="chartData" :options="chartOptions" />
    </div>
</template>
  
<script>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    name: 'doughnutChart',
    components: { Doughnut },
    data: () => ({
        loaded: false,
        chartData: {
            labels: [],
            datasets: [{
                label: "Nº de trucades",
                data: [],
                backgroundColor: []
            }]
        },
        chartOptions: {
            responsive: true,
            plugins: {
                legend: {
                    display: false // Ocultar leyenda
                }
            }
        }
    }),

    async mounted() {

        axios
            .get('/Broggitecnics/public/api/llamadasChart')
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
  '#FFD700'  // Dorado
                ]
                response.data.forEach((element, index) => {
                    this.chartData.labels.push(element.nom)
                    this.chartData.datasets[0].data.push(element.num_incidents)
                    this.chartData.datasets[0].backgroundColor.push(colors[index % colors.length])
                });
                this.loaded = true
                this.$emit('rankingData', this.chartData)

            })
            .catch(error => {
                this.isError = true
                console.error(error)
            })
    }
}

</script>
<style scoped></style>
  
  