<template>
  <form 
    @focusin=" removeValidationClasses($event.target)"
    @focusout="this.validateInput($event.target)">
    <div class="form-floating mb-3" id="tipusIncident-container">
      <input v-model="tipusIncident.input" @input="handleInput($event.target, tipusIncidents)" type="text" class="form-control" id="tipusIncident" placeholder="Tipus incident" list="tipusIncidentsList" autocomplete="off" ref="tipusIncidentInput">
      <label for="tipusIncident">Tipus d'incident</label>
      <datalist  id="tipusIncidentsList">
        <option v-for="(tipus, index) in tipusIncidents" :key="index" :value="tipus.nom"></option>
      </datalist>
    </div>
    <div class="form-floating mb-3" id="incident-container">
      <input v-model="incident.input" @input="handleInput($event.target, incidents)" type="text" class="form-control is-invalid" id="incident" placeholder="Incident" list="incidentsList" autocomplete="off" ref="incidentInput">
      <label for="incident">Incident</label>
      <datalist  id="incidentsList">
        <option v-for="(incident, index) in incidents" :key="index" :value="incident.nom"></option>
      </datalist>
    </div>
    <div v-show="incident.definicio">
      <div class="form-floating">
        <textarea :value="incidentDefinicio" :style="textAreaHeight(incidentDefinicio)" class="form-control-plaintext" id="incidentDefinicio" placeholder="Definició de l'incident"></textarea>
        <label for="incidentDefinicio">Descripció</label>
      </div>
    </div>
    <div v-show="incident.instruccions">
      <div class="form-floating mb-3">
        <textarea :value="incident.instruccions.toLowerCase()" :style="textAreaHeight(incidentInstruccions)" class="form-control-plaintext" id="incidentInstruccions" placeholder="Instruccions a seguir"></textarea>
        <label for="incidentInstruccions">Instruccions a seguir</label>
      </div>
    </div>
  </form>
</template>
<script>
export default {
  emits: [
    'get-incident',
  ],
  props: {
    incidentData: {
      type: Object
    }
  },
  data() {
    return {
      tipusIncident: {
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
        return this.incidentData.incidents.filter(incident => incident.tipus_incidents_id === this.cartaIncident.tipusIncident)
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
  },
  mounted() {
    this.updateCartaData()
  },
}
</script>
<style scoped>
  #tipusIncident-container {
    width: 40%;
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