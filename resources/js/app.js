import './bootstrap';

import * as bootstrap from 'bootstrap'


const slider = document.querySelector('.switch input');
const side = document.getElementById('telef');


slider.addEventListener('change', function () {
    if (this.checked) {
        side.classList.add("expand");
        side.focus();
    } else {
        side.classList.remove("expand");

    }
})