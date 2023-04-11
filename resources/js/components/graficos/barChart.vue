<template>
    <Bar v-if="loaded" :options="chartOptions" :data="chartData" />
    <button @click="addLabel">PRUEBA</button>
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
    
    chartData: null,
    chartOptions: {
          responsive: true
        }
  }),
  async mounted () {
    this.loaded = false

    try {
      const { userlist } = await fetch('/api/userlist')
      this.chartdata = userlist

      this.loaded = true
    } catch (e) {
      console.error(e)
    }
  }
}












  // export default {
  //   name: 'BarChart',
  //   components: { Bar },
  // //   computed: {
  // //   chartData() {
  // //     return {
  // //       title: ['slfdkhskld'],
  // //       labels: ['January', 'Februar', 'March', 'April', 'May', 'june'], 
  // //       datasets: [{ data: [40, 20, 12, 15, 12, 3] }]
  // //     }
  // //   },
  // //   chartOptions() {
  // //     return {
  // //       responsive: true
  // //     }
  // //   }
  // // },
  //   data() {
  //     return {
  //       chartData: {
  //         labels: [ 'January', 'Februar', 'March', 'April' ],
  //         datasets: [ { data: [40, 20, 12, 15] } ]
  //       },
  //       chartOptions: {
  //         responsive: true
  //       }
  //     }
  //   },
  //   methods: {
      
  //   addLabel() {
  //     alert('add')
  //     this.chartData.labels.push('May') // add a new label to the labels array
  //     this.chartData.datasets[0].data.push(30)
  //     // this.$refs.chart.update()
  //   }
  //   // updateChart() {
  //   //   // generate new data
  //   //   const newData = [20, 25, 35, 30]

  //   //   // update chart data
  //   //   this.chartData.datasets[0].data = newData

  //   //   // update chart
  //   //   this.$refs.chart.update()
  //   // }
  // }
  // }
  </script>