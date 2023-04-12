<template>
  <!-- Mostrar alertas success -->
  <div v-if="alertSuccess != ''" class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ alertSuccess }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="restartAlert()"></button>
  </div>

  <div id="card-wrapper">
    <div id="card-container">
      <div class="content">
        <div id="form">
          <div id="form-main" ref="formMain" class="expanded">
            <form-main 
                @get-carta-location="updateLoc"
                @get-carta-interlocutor="updateInterlocutor"
                @get-carta-incident="updateIncident"
                @get-map-search-string="updateSearchString">
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
          <div id="data">DATA</div>
          <!-- MAPA -->
          <div id="map">
            <MapApp :arraySearch="mapSearchString" @changeAlert="añadirAlerta" :alertCerrada="alertSuccess" />
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
</template>
<script>
import FormMain from './form/FormMain.vue';
import FormNota from './form/FormNota.vue';
// import MapApp from './mapa/MapApp.vue';
export default {
  components: {
    FormMain,
    FormNota,
    // MapApp
  },
  data() {
    return {
      interlocutor: {},
      localitzacio: {},
      incident: {},
      notaCoumna: '',
      newInterlocutor: true,
      saveInterlocutor: false,
      mapSearchString: '',
      notaIsExpaneded: false,
      alertSuccess: ""
    }
  },
  methods: {
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
    updateSearchString(mapString) {
      this.mapSearchString = mapString
    },
  }
}
</script>
<style scoped>
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
    background-color: white;
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
  #form-main, #form-nota, #data, #map, #expedients{
    border-radius: 10px;
    width: 100%;
  }
  #data {
    border: 4px solid black;
    height: 10%;
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
</style>