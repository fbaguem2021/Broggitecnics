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
            <form-main 
                :localitzacio-data="localitzacioData" 
                :incident-data="incidentData"
                @get-carta-location="updateLoc"
                @get-carta-interlocutor="updateInterlocutor"
                @get-carta-incident="updateIncident"
                @get-map-search-string="updateSearchString"
                @form-main-error="showError"
                >
              </form-main>
            <!-- Bottom main form blur gradient to indicate that there is content overflowing in the form main block when nota is expanded -->
            <Transition name="fade">
              <div v-show="notaIsExpaneded" class="blur-gradient"></div>
            </Transition>
          </div>
          <!-- Form nota comuna -->
          <div id="form-nota" @focusin="expandCompress" @focusout="expandCompress" ref="formNota">
            <form-nota 
              @get-notaComuna="updateNotaCoumna"
              
              ></form-nota>
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
            <form-expedients></form-expedients>
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
import MessageApp from '../MessageApp.vue';
export default {
  emits: ['agenciasSeleccionadas'],
  components: {
    FormMain,
    FormNota,
    FormExpedients,
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
        })
        .catch((error) => { })
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
    insertCarta() {
      if (this.cartaIsValid) {
        console.log("Carta is valid")

        /* ID INTERLOCUTOR
        Antes del store de la carta, si se quiere guardar el interlocutor y no estaba en la bdd antes, primero se hace el store del interlocutor y luego el de la 
        carta de llamada obteniendo el id del interlocutor según su teléfono.
        En caso que ya esté guardado en la base de datos, se obtiene su id mediante el teléfono
        */

        /* EXPEDIENTE
        Antes del store de la carta, para guardar el EXPEDIENTE: si ya existe el expediente, simplemente se le pasa el numero de expediente para obtener su ID.
        En caso que se cree un expediente nuevo, antes de guardar la carta se guarda el expediente y se obtiene su ID para guardarlo en la carta
        */

        /*ID USUARI
        Hay que obtener la id del usuario, haciendo un select del id segun el nombre de usuario?
        
        */


        

        // const data = {
        //   codi_trucada: this.codiTrucada,
        //   data_hora_trucada: this.dataHoraTrucada,
        //   durada: this.durada,

        //   //Falta sacar la id del interlocutor:
        //   interlocutors_id: this.interlocutors_id,
        //  
        //   telefon: this.interlocutor.telefon,
        //   nom: this.interlocutor.nom,
        //   cognoms: this.interlocutor.cognom,

        //   nota_comuna: this.notaCoumna,

        //   tipus_localitzacions_id: this.localitzacio.tipusLoc,
        //   descripcio_localitzacio: this.localitzacio.descripcioLoc,
        //   detall_localitzacio: this.localitzacio.detallsLoc,
        //   altres_ref_localitzacio: this.localitzacio.altresRefs,
        //   municipis_id: this.localitzacio.municipi,
        //   provincies_id: this.localitzacio.provincia,
        //   incidents_id: this.incidents_id,


        // Y si seleccionas un expediente, se guarda en la misma variable?
        //   expedients_id: this.codiNewExpedient,

        // // Falta sacar la id del usuari
        //   usuaris_id: this.usuaris_id
        // };

        // axios.post('CartaTrucadaController', data)
        //   .then(response => {
        //     console.log(response.data);
        //   })
        //   .catch(error => {
        //     console.log(error);
        //   });

      } else {
        console.log("Carta it's not valid")
      }
    },
    insertInterlocutor() {

    },
    insertAgenciaHasEstat() {
      //insert agencias has estat
    },
    insertExpedient() {
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
}</style>