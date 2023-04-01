import './bootstrap';

/* import 'bootstrap/dist/js/bootstrap.bundle.js'; */

/* createApp(operadors).mount('#operadors'); */
/* app.component('expedient-show', Expedient); */
import { createApp } from 'vue';
import expedients from './components/expedients/ExpedientsMain.vue';

createApp(expedients).mount('expedients-main');