<template>
  <!-- Message app that displays an absolute alert at the top -->
  <message-app ref="messageApp"></message-app>

  <!-- Splash loading animation -->
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
        <!-- Carta left block -->
        <div id="form">
          <!-- Form main Interlocutor, Localitzacio and Incident -->
          <div id="form-main" ref="formMain" class="expanded">
            <form-main :localitzacio-data="localitzacioData" :incident-data="incidentData" @get-carta-location="updateLoc"
              @get-carta-interlocutor="updateInterlocutor" @get-carta-incident="updateIncident"
              @get-map-search-string="updateSearchString" @form-main-is-loaded="formMainIsLoaded"
              @form-main-error="showError">
            </form-main>
            <!-- Bottom main form blur gradient to indicate that there is content overflowing in the form main block when nota is expanded -->
            <Transition name="fade">
              <div v-show="notaIsExpaneded" class="blur-gradient"></div>
            </Transition>
          </div>
          <!-- Form nota comuna -->
          <div id="form-nota" @focusin="expandCompress" @focusout="expandCompress" ref="formNota">
            <form-nota @get-notaComuna="updateNotaCoumna"></form-nota>
          </div>
        </div>

        <!-- Carta right block -->
        <div id="side">
          <div id="data">
            <data-carta :codi-trucada="codiTrucada" :is-loaded="cartaIsLoaded" @carta-durada="updateDurada"></data-carta>
          </div>
          <!-- mapa -->
          <div id="map">
            <MapApp id="mapa-app" :arraySearch="mapSearchString" @changeAlert="añadirAlerta" :alertCerrada="alertSuccess"
              @agenciasSeleccionadas="agenciasSeleccionadas" />
          </div>
          <div id="expedients" style="position: relative;">
            <!-- <form-expedients></form-expedients> -->
          </div>
        </div>
        <div id="bg"></div>
      </div>
      <div class="buttons">
        <button id="cancel">Cancelar</button>
        <button id="submit" @click="insertFinal()">Finalitzar</button>
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
import MessageApp from '../MessageApp.vue';
export default {
  emits: ['agenciasSeleccionadas'],
  components: {
    FormMain,
    FormNota,
    // FormExpedients,
    MapApp,
    DataCarta,
    LoaderSplash,
    MessageApp
  },
  data() {
    return {
      isCartaDataLoaded: false,
      isFormMainLoaded: false,
      codiTrucada: '',
      codiNewExpedient: '',
      //expedient: objeto que contiene {id, codi, estat_id}
      expedient: { id: null, codi: null, estat_id: null },
      isNewExpedient: true,
      dataHoraTrucada: null,
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
      idAgenciasSeleccionadas: [],
      userId: 0,
    }
  },
  computed: {
    cartaIsLoaded() {
      const isLoaded = this.isCartaDataLoaded && this.isFormMainLoaded;
      return isLoaded
    },
    cartaIsValid() {
      const isValid = this.localitzacio.isValid && this.interlocutor.isValid && this.incident.isValid
      return isValid
    }
  },
  methods: {
    agenciasSeleccionadas(idAgencias) {
      alert('funcion en carta')
      this.idAgenciasSeleccionadas = idAgencias
    },
    async getCartaData() {
      const self = this;

      await axios
        .get('cartaData')
        .then(response => {
          self.localitzacioData = response.data.localitzacio
          self.incidentData = response.data.incident
          self.codiTrucada = self.getNewCodi(response.data.cartaLastCodi)
          self.codiNewExpedient = self.getNewCodi(response.data.expedientLatCodi)
          self.isCartaDataLoaded = true
        })
        .catch((error) => {
          self.showError(error)
        })
        .catch((error) => { })

      let username = this.getCookie('username')
      // obtener ID usuario:
      await axios
        .get(`usuari-buscar-id?username=` + username)
        .then(response => {
          self.userId = response.data.data[0].id;
          console.log(response.data)
          console.log(response.data.data[0].id)
        })
        .catch((error) => { 'error al obtenir el usuari' });
      this.isCartaDataLoaded = true


    },
    getNewCodi(codi) {
      let numberPart = parseInt(codi.match(/\d+/)[0]) + 1;
      let prefix = codi.replace(/\d+/, "");
      return prefix + numberPart.toString();
    },
    añadirAlerta(alert) {
      this.alertSuccess = alert
    },
    restartAlert() {
      this.alertSuccess = ""
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
    updateLoc(locString) {
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
    getCookie(name) {
      const cookies = document.cookie.split(';');
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith(name)) {
          return decodeURIComponent(cookie.substring(name.length + 1));
        }
      }
    },

    async insertNewInterlocutor() {
      let me = this
      await axios.post('/postInterlocutor', {
        data: {
          telefon: me.interlocutor.telefon,
          antecedents: me.interlocutor.antecedents,
          nom: me.interlocutor.nom,
          cognoms: me.interlocutor.cognom
        }
      })
        .then(response => {
          console.log(response.data);
          me.interlocutor.id = response.data.idInterlocutor
        })
        .catch(error => {
          console.log(error);
        });
    },

    async insertExpedient() {
      let me = this
      await axios.post('/expedient', {
        data: {
          codi: me.expedient.codi,
          estat_id: me.expedient.estat_id,
        }
      })
        .then(response => {
          console.log('EXPEDIENT GUARDAT')
          console.log(response.data);
          me.expedient.id = response.data.idExpedient
        })
        .catch(error => {
          console.log(error);
        });
    },

    async insertCarta() {
      let me = this
      await axios.post('/cartesTrucades', {
        data: {
          codi_trucada: me.codiTrucada,
          data_hora_trucada: me.dataHoraTrucada,
          durada: me.durada,
          interlocutors_id: me.interlocutor.id,
          telefon: me.interlocutor.telefon,
          nom: me.interlocutor.nom,
          cognoms: me.interlocutor.cognom,
          nota_comuna: me.notaCoumna.input,
          tipus_localitzacions_id: me.localitzacio.tipusLoc,
          descripcio_localitzacio: me.localitzacio.descripcioLoc,
          detall_localitzacio: me.localitzacio.detallsLoc,
          altres_ref_localitzacio: me.localitzacio.altresRefs,
          municipis_id: me.localitzacio.municipi,
          provincies_id: me.localitzacio.provincia,
          incidents_id: me.incident.incident,
          usuaris_id: me.userId,
          expedients_id: me.expedient.id,
        }
      })
        .then(response => {
          console.log(response.data);
          this.$refs.messageApp.createMessageAlert("La carta s'ha guardat exitosament", "success")
        })
        .catch(error => {
          this.$refs.messageApp.createErrorAlert("Hi ha agut un error amb la base de dades: "+error, "danger")
        });
    },

    async insertFinal() {
      if (this.cartaIsValid) {
        if (this.interlocutor.saveInterlocutor) {
          await this.insertNewInterlocutor()
        }
        if (this.isNewExpedient) {
          this.expedient.codi = this.codiNewExpedient
          this.expedient.estat_id = 1
          await this.insertExpedient()
        }
        await this.insertCarta();

      } else {
        console.log("Carta it's not valid")
        this.$refs.messageApp.createMessageAlert("No s'ha pogut guardar la carta hi han camps requerits sense omplir", "warning")
      }
    },

    insertAgenciaHasEstat() {
      //insert agencias has estat
    },

    showError(error) {
      this.$refs.messageApp.createErrorAlert(error)
    }
  },
  mounted() {
    this.getCartaData()
    let inputDate = new Date().toISOString();
    this.dataHoraTrucada = inputDate.replace("T", " ").slice(0, -5);
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
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
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

#form,
#side {
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

#form-main,
#form-nota,
#map,
#expedients,
#data {
  border-radius: 10px;
  width: 100%;
  background-color: white;
  box-shadow: 0 0 4px 1px rgba(0, 0, 0, .12)
}

#form-main,
#form-nota {
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

#form-nota.expanded {
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

.buttons #cancel,
.buttons #submit {
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