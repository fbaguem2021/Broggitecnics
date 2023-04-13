import './bootstrap';
import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';
import 'mapbox-gl/dist/mapbox-gl.css';
import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css';
import agenciasPrimarias from './components/carta/mapa/AgenciasPrimarias.vue'
import expedients from './components/expedients/ExpedientsMain.vue';
import operadors from './components/operadors/Main.vue'
import carta from './components/carta/CartaTrucada.vue'
import mapa from './components/carta/mapa/Mapa.vue'
import mapaApp from './components/carta/mapa/MapApp.vue'

// createApp(agenciasPrimarias).mount('#agenciasPrimarias')
// createApp(mapa).mount('#mapa')
// createApp(mapaApp).mount('#mapaApp')
createApp(carta).mount('carta-app');
createApp(expedients).mount('expedients-app');
// createApp(operadors).mount('operadors');