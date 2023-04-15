import './bootstrap';

/* import 'bootstrap/dist/js/bootstrap.bundle.js'; */

/* createApp(operadors).mount('#operadors'); */
/* app.component('expedient-show', Expedient); */
import { createApp } from 'vue';
import expedients from './components/expedients/ExpedientsMain.vue';
import operadors from './components/operadors/Main.vue'
// import chartLoc from './components/graficos/barChart.vue'
import rankingLoc from './components/graficos/chartLocalizaciones.vue'
import chartLlamadas from './components/graficos/chartLlamadas.vue'

createApp(expedients).mount('expedients-main');
createApp(operadors).mount('operadors');
// createApp(chartLoc).mount('#chartLoc');
createApp(rankingLoc).mount('#rankingLoc')
createApp(chartLlamadas).mount('#chartLlamadas')
