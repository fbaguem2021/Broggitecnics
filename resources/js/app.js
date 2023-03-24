import './bootstrap';

import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';
import operadors from './components/Operadors.vue';
import expedients from './components/expedients/Main.vue';

createApp(operadors).mount('#operadors');
createApp(expedients).mount('expedients');