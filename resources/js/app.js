import './bootstrap';

import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';
// import operadors from './components/Operadors.vue';
import operadors from './components/operadors/Main.vue'

// createApp(operadors).mount('#operadors');
createApp(operadors).mount('operadors');
