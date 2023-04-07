<template>
  <form>
    <div class="form-floating mb-3">
      <input v-model="tipusIncidentInput.name" @input="handleInput('tipusIncidentInput', $event, tipusIncidents)" type="text" class="form-control" id="tipusIncident" placeholder="Tipus incident" list="tipusIncidentsList" autocomplete="off">
      <label for="tipusIncident">Tipus d'incident</label>
      <datalist  id="tipusIncidentsList">
        <option v-for="(tipus, index) in tipusIncidents" :key="index" :value="tipus.nom"></option>
      </datalist>
    </div>
    <div class="form-floating mb-3">
      <input v-model="incidentInput.name" @input="handleInput('incidentInput', $event, incidents)" type="text" class="form-control" id="incident" placeholder="Incident" list="incidentsList" autocomplete="off">
      <label for="incident">Incident</label>
      <datalist  id="incidentsList">
        <option v-for="(incident, index) in incidents" :key="index" :value="incident.nom"></option>
      </datalist>
    </div>
  </form>
</template>
<script>
export default {
  props: {
    incidentData: {
      type: Object
    }
  },
  data() {
    return {
      tipusIncidentInput: {
        'name': ''
      },
      incidentInput: {
          'name': '',
          'tipus_incidents_id': ''
      },
      cartaIncident: {
        'tipusIncident': '',
        'incident': ''
      }
    }
  },
  computed: {
    tipusIncidents() {
      return this.incidentData ? this.incidentData.tipusIncident : []
    },
    incidents() {
      if (this.cartaIncident.tipusIncident) {
        return this.incidentData.incidents.filter(incident => incident.tipus_incidents_id === this.cartaIncident.tipusIncident)
      } else {
        return this.incidentData ? this.incidentData.incidents : []
      }
     
    }
  },
  methods: {
    handleInput(inputName, event, list) {
      const inputValue = event.target.value
      const matchedInputLetter = list.find(tipus => tipus.nom.toLowerCase().startsWith(inputValue.toLowerCase()))
      const matchedInputValue = list.find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())

      if (!matchedInputLetter){
          const previousValue = this[inputName].name.slice(0, -1)
          this[inputName].name = previousValue
      } else if (inputValue.trim() === '') {
          console.log("empppty!! like nada, zero, super empty")
          this.cartaIncident = {
            ...this.cartaIncident,
            [event.target.id]: ''
          }
      } else if (matchedInputValue) {
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
      }
    },
    tipusIncidentSelected (tipusIncident) {
      if (this.cartaIncident.incident && tipusIncident.id != this.incidentInput.tipus_incidents_id) {
        console.log("The incident selected doesnt belong to this tipus!")
        this.incidentInput.name = ''
        this.incidentInput.tipus_incidents_id = ''
        this.cartaIncident.incident = ''
      }
    },
    incidentSelected (incident) {
      this.incidentInput.tipus_incidents_id = incident.tipus_incidents_id
      const tipusIncident = this.tipusIncidents.find(tipus => tipus.id === incident.tipus_incidents_id)
      this.tipusIncidentInput.name = tipusIncident.nom
      this.cartaIncident.tipusIncident = tipusIncident.id
    }

  }
}
</script>
<style scoped>
  
</style>