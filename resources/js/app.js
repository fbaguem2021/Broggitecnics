import './bootstrap';

import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';
import 'mapbox-gl/dist/mapbox-gl.css';

import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css';

import agenciasPrimarias from './components/AgenciasPrimarias.vue'
createApp(agenciasPrimarias).mount('#agenciasPrimarias')

import mapa from './components/Mapa.vue'
createApp(mapa).mount('#mapa')

import mapaApp from './components/MapApp.vue'
createApp(mapaApp).mount('#mapaApp')
