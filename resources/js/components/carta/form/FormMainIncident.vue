<template>
  <form id="incident-form"
    @focusin=" removeValidationClasses($event.target)"
    @focusout="this.validateInput($event.target)">
    <div id="invalid-legend">
      <i class="bi bi-exclamation-circle me-2"></i><span>Els camps amb aquest icona son obligatoris</span>
    </div>
    <div class="row">
      <div class="col-5">
        <div class="form-floating mb-3" id="tipusIncident-container">
          <input v-model="tipusIncident.input" @input="handleInput($event.target, tipusIncidents)" type="text" class="form-control" id="tipusIncident" placeholder="Tipus incident" list="tipusIncidentsList" autocomplete="off" ref="tipusIncidentInput">
          <label for="tipusIncident">Tipus d'incident</label>
          <datalist  id="tipusIncidentsList">
            <option v-for="(tipus, index) in tipusIncidents" :key="index" :value="tipus.nom"></option>
          </datalist>
        </div>
        <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"  data-bs-title="<p>What type of incident are you reporting?</p> <p>Opcions:</p> <ul><li>'Accident' (accident)</li> <li>'Other incidents' (altres incidències)</li> <li>'Medical assistance' (asistencia sanitaria)</li> <li>'Civic issues' (civisme)</li> <li>'Leakage' (fuita)</li> <li>'Fire' (incendi)</li> <li>'Environmental issue' (medi ambient)</li> <li>'Safety' (seguretat)</li> <li>'Traffic' (trànsit)</li></ul>"></i>
      </div>
    </div>
    
    <div class="row">
      <div class="col">
        <div class="form-floating mb-3" id="incident-container">
          <input v-model="incident.input" @input="handleInput($event.target, incidents)" type="text" class="form-control is-invalid" id="incident" placeholder="Incident" list="incidentsList" autocomplete="off" ref="incidentInput">
          <label for="incident">Incident</label>
          <datalist  id="incidentsList">
            <option v-for="(incident, index) in incidents" :key="index" :value="incident.nom"></option>
          </datalist>
        </div>
        <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Can you please provide any additional details about the incident that may be helpful for us to know?"></i>
      </div>
    </div>
    <div v-show='incident.definicio'>
      <div class='form-floating'>
        <textarea :value="incidentDefinicio" :style="textAreaHeight(incidentDefinicio)" class="form-control-plaintext" id="incidentDefinicio" placeholder="Definició de l'incident" readonly></textarea>
        <label for="incidentDefinicio">Descripció</label>
      </div>
    </div>
    <div v-show="incident.instruccions">
      <div class="form-floating mb-3">
        <textarea :value="incident.instruccions.toLowerCase()" :style="textAreaHeight(incidentInstruccions)" class="form-control-plaintext" id="incidentInstruccions" placeholder="Instruccions a seguir" readonly></textarea>
        <label for="incidentInstruccions">Instruccions a seguir</label>
      </div>
    </div>
  </form>
</template>
<script>
import * as bootstrap from 'bootstrap';
export default {
  emits: [
    'get-incident',
    'incident-error'
  ],
  props: {
    incidentData: {
      type: Object
    },
    showHelp: {
      type: Boolean
    },
  },
  data() {
    return {
      tipusIncident: {
        id: '',
        input: '',
        isValid: false
      },
      incident: {
        id: '',
        input: '',
        codi: '',
        definicio: '',
        instruccions: '',
        tipus_incidents_id: '',
        isValid: false
      },
      cartaIncident: {
        incident: '',
        isValid: false
      }
    }
  },
  computed: {
    tipusIncidents () {
      return this.incidentData.tipusIncident ? this.incidentData.tipusIncident : []
    },
    incidents () {
      if (this.tipusIncident.isValid) {
        return this.incidentData.incidents.filter(incident => incident.tipus_incidents_id === this.tipusIncident.id)
      } else {
        return this.incidentData.incidents ? this.incidentData.incidents : []
      }
    },
    incidentDefinicio (){
      return this.incident.definicio ? this.toLowerCase(this.incident.definicio) : ''
    },
    incidentInstruccions () {
      return this.incident.instruccions ? this.toLowerCase(this.incident.instruccions) : ''
    }
  },
  methods: {
    removeValidationClasses(el) {
      el.classList.remove('is-valid', 'is-invalid');
    },
    handleInput(target, list) {
      const inputValue = target.value
      const matchedInputValue = list.find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())
      const handlers = {
        'tipusIncident': this.tipusIncidentSelected,
        'incident': this.incidentSelected
      }
      if (matchedInputValue) {
        const handler = handlers[target.id]
        if (handler) {
          handler(matchedInputValue);
        }
      } else {
        this[target.id].isValid = false;
      }
      this.validateInput(target)
      this.updateCartaData()
    },
    tipusIncidentSelected (tipusIncident) {
      this.tipusIncident.isValid = true
      this.tipusIncident.id = tipusIncident.id
      this.tipusIncident.input = tipusIncident.nom
      if (this.cartaIncident.incident && tipusIncident.id != this.incident.tipus_incidents_id) {
        for(const prop in this.incident) {
          if (prop != 'isValid') {
            this.incident[prop] = ''
          }
        }
        this.cartaIncident.incident = ''
        this.validateInput(this.$refs.incidentInput)
      }
    },
    incidentSelected (incident) {
      this.incident.isValid = true
      this.incident.id = incident.id
      this.incident.codi = incident.codi
      this.incident.definicio = incident.definicio
      this.incident.instruccions = incident.instruccions
      this.incident.tipus_incidents_id = incident.tipus_incidents_id
      const tipusIncident = this.tipusIncidents.find(tipus => tipus.id === incident.tipus_incidents_id)
      if (tipusIncident) {
        this.tipusIncident.input = tipusIncident.nom
        this.cartaIncident.tipusIncident = tipusIncident.id
        this.tipusIncident.isValid = true
        this.validateInput(this.$refs.tipusIncidentInput)
      }
    },
    validateInput (el) {
      if (el.id === 'incident') {
        el.classList.toggle('is-invalid', !this[el.id].isValid)
      }
      el.classList.toggle('is-valid', this[el.id].isValid)
    },
    updateCartaData () {
      this.cartaIncident = {
        incident: this.incident.id,
        isValid: this.incident.isValid
      }
      this.$emit('get-incident', this.cartaIncident)
    },
    toLowerCase (string) {
      return [string].map(item =>
        item.charAt(0).toUpperCase() + item.slice(1).toLowerCase()
      ).join('');
    },
    textAreaHeight(text) {
      const lineHeight = 50; // adjust as needed
      const minHeight = lineHeight * 2; // adjust as needed
      const contentLength = text.length;
      const rows = Math.max(Math.ceil(contentLength / 80), 2); // adjust as needed
      const height = rows * lineHeight;
      return {
        'min-height': `${minHeight}px`,
        'height': `${height}px`,
        'max-height':  `${Math.max(height, minHeight)+40}px`
      };
    },
    showError (error) {
      this.$emit('incident-error', error)
    }
  },
  mounted() {
    this.updateCartaData()
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

  },
}
</script>
<style scoped>
  #incident-form {
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  #invalid-legend {
    position: absolute;
    right: 0;
    top: -10px;
  }
  #invalid-legend span {
    font-size: 14px;
    opacity: .8;
  }
  #invalid-legend i::before {
    font-size: 16px;
    color: #e21212;
  }
  #tipusIncident-container {
    width: 100%;
    min-width: 230px;
  }
  .form-control-plaintext:focus {
    color: #212529;
    background-color: #fff;
    border-color: #1266e2;
    border-width: 2px;
    outline: 0;
    box-shadow: 0 0 4px 2px rgba(18, 102, 226, 0.2);
  }
</style>