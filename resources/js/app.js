import './bootstrap';

/* import 'bootstrap/dist/js/bootstrap.bundle.js'; */

/* createApp(operadors).mount('#operadors'); */
/* app.component('expedient-show', Expedient); */
import { createApp } from 'vue';
import expedients from './components/expedients/ExpedientsMain.vue';
import operadors from './components/operadors/Main.vue'
import chartLoc from './components/graficos/localidadesChart.vue'
import barChart from './components/graficos/barChart.vue'

createApp(expedients).mount('expedients-main');
createApp(operadors).mount('operadors');
createApp(chartLoc).mount('#chartLoc');
createApp(barChart).mount('#barChart')
