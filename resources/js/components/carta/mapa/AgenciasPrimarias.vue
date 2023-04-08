<template>
    <!-- Muestra mapa en pequeño y al confirmar seleccion teleport a modal full screen -->
    <div id="smallMap">
        <Teleport v-if="isMounted" :disabled="!isTeleport" :to="targetTp">
            <BaseMap v-if="targetTp == '#smallMap'" style="height:32vh; width:100%" :seleccion="seleccion" :send="confirmar"
                @restarSend="restartConfirm" @cambiarSeleccion="cambiarSeleccion" @getAgencia="getAgenciaSeleccionada"
                :agenciasFinales="agenciaSeleccionada" :direccionIncidente="arraySearch" />
            <BaseMap v-else style="height:100%; width:100%" :seleccion="seleccion" :send="confirmar"
                :agenciasFinales="agenciaSeleccionada" @restarSend="restartConfirm" @cambiarSeleccion="cambiarSeleccion"
                @getAgencia="getAgenciaSeleccionada" :direccionIncidente="arraySearch" />
        </Teleport>
    </div>

    <div class="ps-2 mt-1">
        <form>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="transit" id="transit" :value="1"
                    @change="handleCheckboxChange($event, 1)">
                <label class="form-check-label" for="transit">Tránsit</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="mossos" id="mossos" :value="2"
                    @change="handleCheckboxChange($event, 2)">
                <label class="form-check-label" for="mossos">Mossos</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="bombers" id="bombers" :value="3"
                    @change="handleCheckboxChange($event, 3)">
                <label class="form-check-label" for="bombers">Bombers</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="pLocal" id="pLocal" :value="4"
                    @change="handleCheckboxChange($event, 4)">
                <label class="form-check-label" for="pLocal">P. Local</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="pMunicipal" id="pMunicipal" :value="5"
                    @change="handleCheckboxChange($event, 5)">
                <label class="form-check-label" for="pMunicipal">P. Municipal</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="gUrbana" id="gUrbana" :value="6"
                    @change="handleCheckboxChange($event, 6)">
                <label class="form-check-label" for="gUrbana">G. Urbana</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="atCiutadana" id="atCiutadana" :value="7"
                    @change="handleCheckboxChange($event, 7)">
                <label class="form-check-label" for="atCiutadana">Atenció Ciutadana</label>
            </div>
        </form>
    </div>

    <!-- BOTONES MAPA -->
    <div id="botonesMapa">
        <button id="botonAmpliar" type="button" class="btn btn-outline-primary btnWithHelp" @click="ampliarMap()"
            v-on:mouseover="showHelpMessageAfterDelay('ampliar')" v-on:mouseout="hideHelpMessage">
            <i class="bi bi-box-arrow-up-right"></i>
            <span id="helMsgAmpliar" class="help-message" v-if="showHelpMessage && eleccio == 'ampliar'">Ampliar el
                mapa</span>
        </button>
        <button type="button" class="btn btn-outline-primary btnWithHelp" @click="checkSeleccion()"
            v-on:mouseover="showHelpMessageAfterDelay('seleccio')" v-on:mouseout="hideHelpMessage">
            <i class="bi bi-eye"></i>
            <span id="helMsgSeleccion" class="help-message" v-if="showHelpMessage && eleccio == 'seleccio'">Veure agencies
                seleccionades</span>
        </button>
        <button type="button" class="btn btn-outline-danger btnWithHelp" @click="eliminarSeleccio()"
            v-on:mouseover="showHelpMessageAfterDelay('eliminar')" v-on:mouseout="hideHelpMessage">
            <i class="bi bi-trash "></i>
            <span id="helpMsgEliminar" class="help-message" v-if="showHelpMessage && eleccio == 'eliminar'">
                Eliminar totes les agencies seleccionades
            </span>
        </button>
    </div>

    <!-- modal full screen mapa -->
    <div class="modal" tabindex="-1" id="mapaModal">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <div v-if="alert != ''" class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ alert }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="restartAlert()"></button>
                    </div>
                </div>
                <div class="modal-body" id="bigMap"></div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div id="imprimirAgencias" class="col-sm-10 d-grid gap-2 d-md-flex justify-content-md-start">
                            <div class="m-e-2">
                                <h6>SELECCIÓ: </h6>
                            </div>
                            <div id="agenciasSeleccionadas">
                                {{ stringAgencias }}
                            </div>
                        </div>
                        <div class="col-sm-2 d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-danger btnWithHelp" @click="eliminarSeleccio()"
                                v-on:mouseover="showHelpMessageAfterDelay('eliminar')" v-on:mouseout="hideHelpMessage">
                                <i class="bi bi-trash "></i>
                                <span id="helpMsgEliminar" class="help-message"
                                    v-if="showHelpMessage && eleccio == 'eliminar'">
                                    Eliminar totes les agencies seleccionades
                                </span>
                            </button>
                            <button type="button" class="btn btn-outline-primary" @click="setConfAgenci()">
                                <i class="bi bi-box-arrow-in-down-left"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal ver seleccion -->
    <div class="modal" tabindex="-1" id="seleccionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agencies Seleccionades:</h5>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p v-for="agencia in agenciaSeleccionada"> {{ agencia[1] }} </p>
                </div>
            </div>
        </div>
    </div>

    <!-- modal substituir agencia -->
    <div class="modal" tabindex="-1" id="cambioSeleModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Has seleccionat un tipus d'agència ja seleccionada prèviament. Vols
                        substituir-la o mantenir la teva selecció?</h5>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-outline-danger" @click="eliminarEstaAgencia()">
                        Substitueix
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        Cancel·la
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
    props: ['arraySearch'],
    emits: ['añadirAlert'],
    data() {
        return {
            seleccion: [],
            confirmar: false,
            myModal: {},
            modalSelec: {},
            modalCambioSeleccion: {},
            targetTp: "#smallMap",
            isTeleport: false,
            agenciaSeleccionada: [],
            stringAgencias: "",
            isMapSelect: false,
            isMounted: false,
            isDelete: false,
            showHelpMessage: false,
            timeoutId: null,
            eleccio: "",
            nuevaSeleccion: 0,
            alert: ""
        };
    },

    watch: {
        arraySearch: {
            immediate: true,
            handler(newVal, oldVal) {
                this.confirmar = true
            }
        },
        isMapSelect: function (newVal, oldVal) {

            this.stringAgencias = ""
            this.agenciaSeleccionada.forEach(element => {
                let insert = ""
                if (element[2] == 2) {
                    insert = element[1].substr(44)
                } else {
                    insert = element[1]
                }
                this.stringAgencias += insert + ' || '

            });
        }
    },

    mounted() {
        this.myModal = new bootstrap.Modal('#mapaModal')
        this.modalSelec = new bootstrap.Modal("#seleccionModal")
        this.modalCambioSeleccion = new bootstrap.Modal("#cambioSeleModal")
        this.isMounted = true;
    },

    methods: {
        restartAlert(){  
                this.alert=""
        },
        showHelpMessageAfterDelay(eleccio) {
            switch (eleccio) {
                case 'ampliar':
                    this.eleccio = 'ampliar'
                    break;
                case 'seleccio':
                    this.eleccio = 'seleccio'
                    break;
                case 'eliminar':
                    this.eleccio = 'eliminar'
                    break;
            }
            this.timeoutId = setTimeout(() => {
                this.showHelpMessage = true
            }, 3000)
        },
        hideHelpMessage() {
            clearTimeout(this.timeoutId)
            this.showHelpMessage = false
        },
        handleCheckboxChange(event, value) {
            if (!event.target.checked) {
                this.seleccion = this.seleccion.filter(v => v !== value)

            } else {
                this.seleccion.push(value)
            }
            this.confirmar = true
        },
        eliminarSeleccio() {
            this.agenciaSeleccionada = []
            document.getElementById('agenciasSeleccionadas').innerHTML = ""
            //Envia mensaje para la alert de la carta
            this.$emit("añadirAlert", "S'han eliminat totes les agències prèviament seleccionades")
        },
        checkSeleccion() {
            this.modalSelec.show()
        },
        restartConfirm() {
            this.confirmar = false
        },
        cambiarSeleccion(newSel) {
            let me = this
            this.nuevaSeleccion = newSel
            this.modalCambioSeleccion.show()
            

        },
        eliminarEstaAgencia() {
            this.agenciaSeleccionada.forEach(element => {
                if (element[2] == this.nuevaSeleccion.properties.title.AgenciesPrimaries_id) {
                    this.agenciaSeleccionada.splice(this.agenciaSeleccionada.indexOf(element));
                    this.agenciaSeleccionada.push([this.nuevaSeleccion.properties.title.id, this.nuevaSeleccion.properties.title.nom, this.nuevaSeleccion.properties.title.AgenciesPrimaries_id]);
                    this.$emit("añadirAlert", "S'ha substituït l'agència correctament")
                    this.alert = "S'ha substituït l'agència correctament"
                }
                this.modalCambioSeleccion.hide()
            });

        },
        getAgenciaSeleccionada(arrayAgencia) {

            this.agenciaSeleccionada = arrayAgencia
            this.isMapSelect = !this.isMapSelect

        },
        confirmarSeleccion() {
            this.confirmar = true
            this.isTeleport = false
            this.targetTp = "#smallMap"
        },
        ampliarMap() {
            this.confirmar = true
            this.targetTp = "#bigMap"
            this.isTeleport = true
            this.showBigMap()
        },
        resetConfirmar() {
            this.confirmar = false
        },
        showBigMap() {
            this.$nextTick(() => {
                this.myModal.show()
            })
        },
        setConfAgenci() {
            this.confirmar = true
            this.isTeleport = false
            this.targetTp = "#smallMap"
            this.myModal.hide()
        }
    },



}
</script>

<style scoped>
#botonesMapa {
    position: absolute;
    left: 85%;
    bottom: 19%;
    z-index: 3;
}


#helMsgAmpliar {
    transform: translateY(-180%);
}

#helMsgSeleccion {
    transform: translateY(-160%);
}

#helpMsgEliminar {
    transform: translateY(-148%);
}

.btnWithHelp {
    position: relative;
    z-index: 1;
}

.help-message {
    position: absolute;
    top: 100%;
    left: 10%;
    padding: 0.5rem;
    background-color: grey;
    border: 1px solid grey;
    border-radius: 0.25rem;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    font-size: 0.75rem;
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
    width: auto;
    z-index: 3;
}



.help-message.show {
    opacity: 1;
}</style>
