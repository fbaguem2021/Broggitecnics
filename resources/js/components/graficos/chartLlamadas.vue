<template>
    <div class="row" style="height: 41%">
        <div class="pt-4 text-center" id="titulo">
            <h1><i class="bi bi-trophy me-3"></i>Top 10 trucades per municipi</h1>
        </div>

        <!-- Hacer v-for en el div id= lista -->
        <div v-if="isData" id="lista" class="container-fluid">
            <div class="col-11 mx-auto">
                <div class="row justify-content-center">
                    <div v-for="(data, index) in dataRanking" class="burbuja rounded-4 text-center me-2 mb-3 "
                        :style="{ backgroundColor: data[2] }">
                        <h5 class="pt-2">{{ data[0] }}</h5>
                        <p>Nº de trucades: {{ data[1] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row chartD"  style="height: 59%">        
            <doughnutChart class="chartD" @rankingData="getRankingData" />
    </div>
</template>

<script>
import doughnutChart from './doughnutChart.vue';
export default {
    data() {
        return {
            dataRanking: {},
            isData: false
        }
    },
    components: {
        doughnutChart
    },
    methods: {
        getRankingData(info) {
            let arrayFinal = {}
            for (let index = 0; index < info.labels.length; index++) {
                arrayFinal[index] = {
                    0: info.labels[index],
                    1: info.datasets[0].data[index],
                    2: info.datasets[0].backgroundColor[index]
                }
            }
            this.dataRanking = arrayFinal
            this.isData = true
        },
        printRanking() {

        }
    }
}
</script>

<style scoped>
.burbuja {
    height: 10vh;
    width: 13vw;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

}
.chartD{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>