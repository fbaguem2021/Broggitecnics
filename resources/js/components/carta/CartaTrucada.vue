<template>
  <Transition name="fade">
    <loader-splash v-if="!cartaIsLoaded"></loader-splash>
  </Transition>
  <!-- Mostrar alertas success -->
  <div v-if="alertSuccess != ''" id="carta-alert" class="alert alert-info alert-dismissible fade show" role="alert">
    {{ alertSuccess }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="restartAlert"></button>
  </div>
  <div id="card-wrapper">
    <div id="card-container">
      <div class="content">
        <div id="form">
          <div id="form-main" ref="formMain" class="expanded">
            <form-main
                :localitzacio-data="localitzacioData"
                :incident-data="incidentData"
                @get-carta-location="updateLoc"
                @get-carta-interlocutor="updateInterlocutor"
                @get-carta-incident="updateIncident"
                @get-map-search-string="updateSearchString"
                @form-main-is-loaded="formMainIsLoaded"
                >
              </form-main>
            <transition name="fade">
              <div v-show="notaIsExpaneded" class="blur-gradient"></div>
            </transition>
          </div>
          <div id="form-nota" @focusin="expandCompress" @focusout="expandCompress" ref="formNota">
            <form-nota @get-notaComuna="updateNotaCoumna"></form-nota>
          </div>
        </div>
        <div id="side">
          <div id="data">
            <data-carta
              :codi-trucada="codiTrucada"
              :is-loaded="cartaIsLoaded"
              @carta-durada="updateDurada"
              ></data-carta>
          </div>
          <!-- MAPA -->
          <div id="map">
            <MapApp id="mapa-app" :arraySearch="mapSearchString" @changeAlert="añadirAlerta" :alertCerrada="alertSuccess" @agenciasSeleccionadas="agenciasSeleccionadas" />
          </div>
          <div id="expedients" style="position: relative;">
                <form-expedients
                    :localitzacio="localitzacio"
                    :new-expedient-code="codiNewExpedient"></form-expedients>
            </div>
        </div>
        <div id="bg"></div>
      </div>
      <div class="buttons">
          <button id="cancel">Cancelar</button>
          <button id="submit" @click="insertCarta">Finalitzar</button>
      </div>
    </div>
  </div>
  <div id="stripes-1">
    <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
        <path d="M0 0H200V20H0V0Z" fill="#E2AA12" />
        <path d="M0 30H200V50H0V30Z" fill="#E1127A" />
        <path d="M0 60H200V80H0V60Z" fill="#12C7E2" />
    </svg>
  </div>
  <div id="stripes-2">
      <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
          <path d="M0 0H200V20H0V0Z" fill="#E2AA12" />
          <path d="M0 30H200V50H0V30Z" fill="#E1127A" />
          <path d="M0 60H200V80H0V60Z" fill="#12C7E2" />
      </svg>
  </div>
</template>
<script>
import LoaderSplash from './LoaderSplash.vue'
import FormMain from './form/FormMain.vue';
import FormNota from './form/FormNota.vue';
import FormExpedients from './form/FormExpedients.vue';
import DataCarta from './DataCarta.vue';
import MapApp from './mapa/MapApp.vue';
export default {
  emits: ['agenciasSeleccionadas'],
  components: {
    FormMain,
    FormNota,
    FormExpedients,
    MapApp,
    DataCarta,
    LoaderSplash
  },
  data() {
    return {
      isCartaDataLoaded: false,
      isFormMainLoaded: false,
      codiTrucada: '',
      codiNewExpedient: '',
      dataHoraTrucada: '',
      durada: 0,
      interlocutor: {},
      localitzacio: {},
      incident: {},
      notaCoumna: '',
      newInterlocutor: true,
      saveInterlocutor: false,
      mapSearchString: '',
      notaIsExpaneded: false,
      alertSuccess: "",
      localitzacioData: {},
      incidentData: {},
      idAgenciasSeleccionadas: []
    }
  },
  computed: {
    cartaIsLoaded() {
      const isLoaded = this.isCartaDataLoaded && this.isFormMainLoaded;
      return isLoaded
    },
    cartaIsValid(){
      const isValid = this.localitzacio.isValid && this.interlocutor.isValid && this.incident.isValid
      return isValid
    }
  },
  methods: {
    agenciasSeleccionadas(idAgencias){
      alert('funcion en carta')
      this.idAgenciasSeleccionadas = idAgencias
    },
    async getCartaData () {
      const self = this;
      await axios
              .get('cartaData')
              .then(response => {
                  self.localitzacioData = response.data.localitzacio
                  self.incidentData = response.data.incident
                  self.codiTrucada = self.getNewCodi(response.data.cartaLastCodi)
                  self.codiNewExpedient = self.getNewCodi(response.data.expedientLatCodi)
              })
              .catch((error) => {})
      this.isCartaDataLoaded = true
    },
    getNewCodi (codi) {
      let numberPart = parseInt(codi.match(/\d+/)[0]) + 1;
      let prefix = codi.replace(/\d+/, "");
      return prefix + numberPart.toString();
    },
    añadirAlerta(alert) {
      this.alertSuccess = alert
    },
    restartAlert() {
      this.alertSuccess=""
    },
    expandCompress() {
      this.$refs.formNota.classList.toggle('expanded');
      this.$refs.formMain.classList.toggle('expanded');

      if (this.$refs.formNota.classList.contains('expanded')) {
        this.notaIsExpaneded = true
      } else {
        this.notaIsExpaneded = false
      }
    },
    updateLoc (locString) {
      this.localitzacio = locString
    },
    updateInterlocutor(interlocutor) {
      this.newInterlocutor = interlocutor.isNewInerlocutor
      this.saveInterlocutor = interlocutor.saveInterlocutor
      this.interlocutor = interlocutor
    },
    updateIncident(incident) {
      this.incident = incident
    },
    updateNotaCoumna(nota) {
      this.notaCoumna = nota
    },
    updateDurada(seconds) {
      this.durada = seconds
    },
    updateSearchString(mapString) {
      this.mapSearchString = mapString
    },
    formMainIsLoaded(isLoaded) {
      this.isFormMainLoaded = isLoaded
    },
    insertCarta() {
      if (this.cartaIsValid){
        console.log("Carta is valid")
      } else {
        console.log("Carta it's not valid")
      }
    },
    insertInterlocutor () {

    },
    insertAgenciaHasEstat () {
      //insert agencias has estat
    },
    insertExpedient () {
      // Add rquest to insert Expedient
    },

  },
  mounted() {
    this.getCartaData()
    // setTimeout(()=>{ this.isLoaded = true}, 1000)
  },
}
</script>
<style scoped>

  #carta-alert {
    position: absolute;
    width: 50%;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
    box-shadow: 0 0 4px 0px rgba(0, 0, 0, .1)
  }
  .blur-gradient {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px;
      border-radius: 0 0 5px 5px;
      /* background: linear-gradient(to bottom, rgba(250, 80, 80, 0) 0%, rgba(250, 80, 80, 1) 100%); */
      background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(230, 230, 230, 1) 100%);
  }

  /* Vue transition animations */
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.4s ease;
    }
    .fade-enter-from, .fade-leave-to {
    opacity: 0;
    /* transform: translateY(100%); */
    }
  #card-wrapper {
    flex-grow: 1;
    margin: 20px;

  }
  #card-container {
    height: 100%;
    display: flex;
    flex-direction: column;
    min-width: 800px;
  }
  .content {
    position: relative;
    display: flex;
    height: 94%;
    gap: 1%
  }
  #bg {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-color: #ffffff;
    border-radius: 10px;
    z-index: -1;
  }
  #form, #side {
    height: 100%;
  }
  #form {
    display: flex;
    flex-direction: column;
    gap: 2%;
    width: 58%;
  }
  #side {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 42%;
  }
  #form-main, #form-nota, #map, #expedients, #data {
    border-radius: 10px;
    width: 100%;
    background-color: white;
    box-shadow: 0 0 4px 1px rgba(0, 0, 0, .12)
  }

  #form-main, #form-nota {
    border: 4px solid var(--primary);
    transition: height .4s ease-in-out;
    overflow: hidden;
  }
  #form-main {
    position: relative;
    display: flex;
    flex-direction: column;
    height: 56%;
  }
  #form-main.expanded {
    height: 80%;
  }
  #form-nota {
    padding: 20px 40px;
    height: 18%
  }
  #form-nota.expanded{
    height: 42%;
  }
  #data {
    border: 4px solid black;
    height: 9%;
  }
  #map {
    border: 4px solid var(--secondary);
    height: 44%;
  }
  #expedients {
    border: 4px solid var(--tertiary);
    height: 42%;
  }
  .buttons {
    flex-grow: 1;
    display: flex;
    justify-content: center;
    column-gap: 30px;
    padding-top: 1%;
    margin-left: auto;
    width: 42%;
  }
  .buttons #cancel {
    background-color: var(--danger);
  }
  .buttons #submit {
    background-color: var(--success);
  }
  .buttons #cancel, .buttons #submit {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 180px;
    border-radius: 5px;
    color: white;
    border: none;
  }
  #map {
    position: relative
  }
  #mapa-app {
    height: 100%;
  }
</style>