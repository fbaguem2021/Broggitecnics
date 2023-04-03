import './bootstrap';

/* import 'bootstrap/dist/js/bootstrap.bundle.js'; */

/* createApp(operadors).mount('#operadors'); */
/* app.component('expedient-show', Expedient); */
import { createApp } from 'vue';
import expedients from './components/expedients/ExpedientsMain.vue';
import operadors from './components/operadors/Main.vue'
import carta from './components/carta/Main.vue'

createApp(carta).mount('carta-app');
createApp(expedients).mount('expedients-app');
createApp(operadors).mount('operadors');