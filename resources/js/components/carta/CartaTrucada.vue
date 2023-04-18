<template>
  <Transition name="fade">
    <loader-splash v-if="!isLoaded"></loader-splash>
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
                @get-map-search-string="updateSearchString" >
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
            <data-carta :codi-trucada="codiTrucada" :is-loaded="isLoaded"></data-carta>
          </div>
          <!-- MAPA -->
          <div id="map">
            <MapApp id="mapa-app" :arraySearch="mapSearchString" @changeAlert="añadirAlerta" :alertCerrada="alertSuccess" />
          </div>
          <div id="expedients">EXPEDIENTS</div>
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
import DataCarta from './DataCarta.vue';
import MapApp from './mapa/MapApp.vue';
export default {
  components: {
    FormMain,
    FormNota,
    MapApp,
    DataCarta,
    LoaderSplash
  },
  data() {
    return {
      isLoaded: false,
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
    }
  },
  methods: {
    getCartaData () {
      const self = this;
      axios
          .get('cartaData')
          .then(response => {
              self.localitzacioData = response.data.localitzacio
              self.incidentData = response.data.incident
              self.codiTrucada = self.getNewCodi(response.data.cartaLastCodi)
              self.codiNewExpedient = self.getNewCodi(response.data.expedientLatCodi)
          })
          .catch((error) => {})
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
      console.log(interlocutor)
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
    updateSearchString(mapString) {
      this.mapSearchString = mapString
    },
    insertCarta() {
      const cartaIsValid = this.localitzacio.isValid && this.interlocutor.isValid && this.incident.isValid
      if (cartaIsValid){
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
    }
  },
  mounted() {
    this.getCartaData()
    setTimeout(()=>{ this.isLoaded = true}, 3000)
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
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    /* border: 4px solid red */
  }
  #card-container {
    flex-grow: 1;
    margin: 20px 20px 40px 20px;
    /* border: 1px solid blue */
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
    display: flex;
    justify-content: center;
    column-gap: 30px;
    margin-left: auto;
    height: 10%;
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
    margin: 2.5% 0;
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