<template>
  <form @input="isFormValid($event)">
    <div class="form-floating mb-3" id="tipusIncident-container">
      <input v-model="tipusIncident.name" @input="handleInput('tipusIncident', $event, tipusIncidents)" @change="validateInput($event.target)" type="text" class="form-control" id="tipusIncident" placeholder="Tipus incident" list="tipusIncidentsList" autocomplete="off" ref="tipusIncidentInput">
      <label for="tipusIncident">Tipus d'incident</label>
      <datalist  id="tipusIncidentsList">
        <option v-for="(tipus, index) in tipusIncidents" :key="index" :value="tipus.nom"></option>
      </datalist>
    </div>
    <div class="form-floating mb-3" id="incident-container">
      <input v-model="incident.name" @input="handleInput('incident', $event, incidents)" @change="validateInput($event.target)"  type="text" class="form-control is-invalid" id="incident" placeholder="Incident" list="incidentsList" autocomplete="off">
      <label for="incident">Incident</label>
      <datalist  id="incidentsList">
        <option v-for="(incident, index) in incidents" :key="index" :value="incident.nom"></option>
      </datalist>
    </div>
    <div v-show="incident.definicio">
      <div class="form-floating">
        <textarea :value="incidentDefinicio" :style="textareaHeight(incidentDefinicio)" class="form-control-plaintext" id="incidentDefinicio" placeholder="Definició de l'incident" ></textarea>
        <label for="incidentDefinicio">Descripció</label>
      </div>
    </div>
    <div v-show="incident.instruccions">
      <div class="form-floating mb-3">
        <textarea :value="incident.instruccions.toLowerCase()" :style="textareaHeight(incidentInstruccions)" class="form-control-plaintext" id="incidentInstruccions" placeholder="Instruccions a seguir" ></textarea>
        <label for="incidentInstruccions">Instruccions a seguir</label>
      </div>
    </div>
  </form>
</template>
<script>
export default {
  emits: [
    'is-form-valid'
  ],
  props: {
    incidentData: {
      type: Object
    }
  },
  data() {
    return {
      tipusIncident: {
        name: '',
        isValid: false
      },
      incident: {
        name: '',
        codi: '',
        definicio: '',
        instruccions: '',
        tipus_incidents_id: '',
        isValid: false
      },
      cartaIncident: {
        'tipusIncident': '',
        'incident': ''
      }
    }
  },
  computed: {
    tipusIncidents () {
      return this.incidentData ? this.incidentData.tipusIncident : []
    },
    incidents () {
      if (this.cartaIncident.tipusIncident) {
        return this.incidentData.incidents.filter(incident => incident.tipus_incidents_id === this.cartaIncident.tipusIncident)
      } else {
        return this.incidentData ? this.incidentData.incidents : []
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
    validateInput (el) {
      var isValid = false;
      if (this[el.id].isValid) {
        isValid = true
      } 
      if (el.id === 'incident') {
        el.classList.toggle('is-invalid', !isValid)
      }
      el.classList.toggle('is-valid', isValid)
    },
    isFormValid() {
      const isValid = this.tipusIncident.isValid && this.incident.isValid ? true : false
      this.$emit('is-form-valid', isValid)
      console.log("Form is valid?: " + isValid)
    },
    handleInput(inputName, event, list) {
      const inputValue = event.target.value
      const matchedInputLetter = list.find(tipus => tipus.nom.toLowerCase().startsWith(inputValue.toLowerCase()))
      const matchedInputValue = list.find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())

      if (matchedInputValue) {
        this[inputName].isValid = true
        switch (event.target.id) {
          case 'tipusIncident':
            this.tipusIncidentSelected(matchedInputValue)
            console.log("Tipus incident match!!")
            break;
          case 'incident':
            this.incidentSelected(matchedInputValue)
            console.log("Incident match!!")
            break;
        }
        this.cartaIncident = {
          ...this.cartaIncident,
          [event.target.id]: matchedInputValue.id
        }
      } else {
        this[inputName].isValid = this[inputName].isValid ? false : this[inputName].isValid
        
        if (inputValue.trim() === '') {
          console.log("empppty!! like nada, zero, super empty")
          this.cartaIncident = {
            ...this.cartaIncident,
            [event.target.id]: ''
          }
        } else if (!matchedInputLetter){
            const previousValue = this[inputName].name.slice(0, -1)
            this[inputName].name = previousValue
        } 
      }

    },
    tipusIncidentSelected (tipusIncident) {
      if (this.cartaIncident.incident && tipusIncident.id != this.incident.tipus_incidents_id) {
        console.log("The incident selected doesnt belong to this tipus!")
        for(const prop in this.incident) {
          this.incident[prop] = ''
        }
        this.cartaIncident.incident = ''
      }
    },
    incidentSelected (incident) {
      this.incident.codi = incident.codi
      this.incident.definicio = incident.definicio
      this.incident.instruccions = incident.instruccions
      this.incident.tipus_incidents_id = incident.tipus_incidents_id
      const tipusIncident = this.tipusIncidents.find(tipus => tipus.id === incident.tipus_incidents_id)
      if (tipusIncident) {
        this.tipusIncident.name = tipusIncident.nom
        this.cartaIncident.tipusIncident = tipusIncident.id
        this.tipusIncident.isValid = true
        this.validateInput(this.$refs.tipusIncidentInput)
      }
    },
    toLowerCase (string) {
      return [string].map(item =>
        item.charAt(0).toUpperCase() + item.slice(1).toLowerCase()
      ).join('');
    },
    textareaHeight(text) {
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
  }
}
</script>
<style scoped>
  #tipusIncident-container {
    width: 40%;
    min-width: 230px;
  }
</style>