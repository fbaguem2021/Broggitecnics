<template>
    <!-- Muestra mapa en pequeño y al confirmar seleccion teleport a modal full screen -->
    <div id="smallMap" style="border: red 2px solid; height:300px; width:400px; margin-top:80px">
        <Teleport v-if="confirmar" to="#bigMap">
            <BaseMap style="height:100%; width:100%" :seleccion="seleccion" :send="confirmar" />
        </Teleport>
        <BaseMap v-else :seleccion="seleccion" :send="confirmar" />
    </div>

    <!-- Formulario para escoger agenicas en la carta de llamada -->
    <form>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="transit" id="transit" :value="1" v-model="seleccion">
            <label class="form-check-label" for="transit">Tránsit</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="mossos" id="mossos" :value="2" v-model="seleccion">
            <label class="form-check-label" for="mossos">Mossos</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bombers" id="bombers" :value="3" v-model="seleccion">
            <label class="form-check-label" for="bombers">Bombers</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="pLocal" id="pLocal" :value="4" v-model="seleccion">
            <label class="form-check-label" for="pLocal">P. Local</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="pMunicipal" id="pMunicipal" :value="5"
                v-model="seleccion">
            <label class="form-check-label" for="pMunicipal">P. Municipal</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gUrbana" id="gUrbana" :value="6" v-model="seleccion">
            <label class="form-check-label" for="gUrbana">G. Urbana</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="atCiutadana" id="atCiutadana" :value="7"
                v-model="seleccion">
            <label class="form-check-label" for="atCiutadana">Atenció Ciutadana</label>
        </div>
    </form>
    <button type="button" class="btn btn-primary" @click="confirmarSeleccion()" :restartJson="[]">
        <i class="fa-regular fa-pen-to-square"></i> Confirmar
    </button>

    <!-- modal full screen mapa -->
    <div class="modal" tabindex="-1" id="mapaModal">
        <div class="modal-fullscreen">
            <div class="modal-content">
                <div class="modal-body" id="bigMap" />
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        @click="resetConfirmar()">Tancar</button>
                    <button type="button" class="btn btn-primary" @click="resetConfirmar()">
                        <i class="fa-regular fa-pen-to-square"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BaseMap from './Mapa.vue'
import * as bootstrap from 'bootstrap'
export default {
    name: 'MapaOptions',
    components: {
        BaseMap
    },
    data() {
        return {
            seleccion: [],
            confirmar: false,
        };
    },
    methods: {
        confirmarSeleccion() {
            this.confirmar = true
            this.showBigMap()
        },
        resetConfirmar() {
            this.confirmar = false
        },
        showBigMap() {
            this.myModal = new bootstrap.Modal('#mapaModal')
            this.myModal.show()
        },

    },


}
</script>

<style></style>