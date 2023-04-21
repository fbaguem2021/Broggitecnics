<template lang="">
    <div>
        <div class='container' style="margin-top: 40px">
          <h4>Cartes de trucada</h4>
          <table class="table table-hover text-center">
            <thead>
              <tr>
                <th v-for="(label, index) in th" :key="index" scope="col">{{label}}</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="(carta, index) in expedient.cartes_trucada" :key="index">
                    <td>{{carta.codi_trucada}}</td>
                    <td>{{carta.localitzacio.municipi}}</td>
                    <td>{{toLowerCase(carta.incident.tipus_incident)}}</td>
                    <td>{{carta.data_hora.data}}</td>
                    <td>{{carta.durada}}</td>
                    <td>{{carta.operador.nom}} {{carta.operador.cognom}}</td>
                    <td @click="showModal(carta)"><i class="bi bi-eye inspect-carta"></i></td>
                </tr>
            </tbody>
          </table>
        </div>
    </div>

    <div ref="cartaModal" class="modal fade" id="cartaModal" tabindex="-1" aria-labelledby="logCartaTrucada" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-3 fw-bold" id="exampleModalLabel">CARTA #{{cartaSelected.codi_trucada}}</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div v-if="cartaSelected" class="modal-body">
              <div id="interlocutor">
                <h5>INTERLOCUTOR</h5>
                <div class="row">
                  <div class="col-3">
                    <div class="form-floating mb-3">
                      <input type="phone" class="form-control" id="phone" :value="cartaSelected.interlocutor.telefon" placeholder="Telèfon" disabled>
                      <label for="phone">Telèfon</label>
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="name" :value="cartaSelected.interlocutor.nom + ' ' +cartaSelected.interlocutor.cognom" placeholder="Nom" disabled>
                      <label for="name">Nom</label>
                    </div>
                  </div>
                </div>
                <div class="form-floating mb-3">
                  <textarea  type="text" class="form-control" id="record" :value="cartaSelected.interlocutor.antecedents" placeholder="Antecedents" disabled></textarea>
                  <label for="record">Antecedents</label>
                </div>
              </div>
              <div id="localitzacio">
                <h5>LOCALITZACIÓ</h5>
                <div class="row">
                  <div class="col-3">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="provincia" :value="cartaSelected.localitzacio.provincia" placeholder="Input" disabled>
                      <label for="provincia">Provincia</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="comarca" :value="cartaSelected.localitzacio.comarca" placeholder="Input" disabled>
                      <label for="comarca">Comarca</label>
                    </div>
                  </div>
                  <div class="col-5">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="municipi" :value="cartaSelected.localitzacio.municipi" placeholder="Input" disabled>
                      <label for="municipi">Municipi</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="tipusLoc" :value="cartaSelected.localitzacio.tipus" placeholder="Input" disabled>
                      <label for="tipusLoc">Tipus de localització</label>
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="descripcioLoc" :value="cartaSelected.localitzacio.descripcio" placeholder="Input" disabled>
                      <label for="descripcioLoc">Descripció</label>
                    </div>
                  </div>
                </div>
                <div class="form-floating mb-3">
                  <textarea  type="text" class="form-control" id="referenciesLoc" :value="cartaSelected.localitzacio.referencies" placeholder="Antecedents" disabled></textarea>
                  <label for="referenciesLoc">Altres referències</label>
                </div>
              </div>
              <div id="incident">
                <h5>INCIDENT</h5>
                <div class="row">
                  <div class="col-3">
                    <div class="form-floating mb-3">
                      <textarea  type="text" class="form-control" id="tipusIncident" :value="cartaSelected.incident.codi_incident" placeholder="Codi incident" disabled></textarea>
                      <label for="tipusIncident">Codi incident</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-floating mb-3">
                      <textarea  type="text" class="form-control" id="tipusIncident" :value="cartaSelected.incident.tipus_incident" placeholder="Tipus incident" disabled></textarea>
                      <label for="tipusIncident">Tipus d'incident</label>
                    </div>
                  </div>
                </div>
                <div class="form-floating mb-3">
                  <textarea  type="text" class="form-control" id="incidentLabel" :value="cartaSelected.incident.incident" placeholder="Incident" disabled></textarea>
                  <label for="incidentLabel">Incident</label>
                </div>
              </div>
              <div id="agencies">
                <h5>AGENCIES</h5>
                <div class="agencia mb-2" v-for="(agencia, index) in cartaSelected.agencies" :key="index">
                  <div class="row">
                    <div class="col-8" style="display: flex; align-items: center">
                      {{agencia.nom}}
                    </div>
                    <div class="col-4">
                      <div class="form-floating">
                        <select class="form-select" 
                                :id="'agenciaID' + agencia.id" 
                                :class=" 'state'+agencia.estat.id"
                                aria-label="Floating label select example"
                                @change="updateAgenciaState(cartaSelected.id, agencia.id, $event.target.value)">
                          <option v-for="(estat, index) in estatsAgencies" :key="index" 
                              :value="estat.id" 
                              :selected="agencia.estat.id === estat.id"
                              >{{estat.estat}}</option>
                        </select>
                        <label for="floatingSelect">Estat de l'agència</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="modal-body spinner-container">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            </div>
            <button ref="collapseBtn" id="collapse-btn" class="btn btn-primary" type="button" @click="toggleCollapse">
              <i class="bi bi-arrows-expand"></i>
            </button>
            <div id="collapse-container" class="d-flex flex-row-reverse">
              <div style="min-height: 200px;">
                <div class="collapse collapse-horizontal" ref="collapse" id="collapseWidthExample" style="height:100%">
                  <div class="card card-body" style="width: 300px; height:100%">
                    <div class="form-floating mb-3" style="max-height:100%; height: 100%">
                      <textarea  type="text" class="form-control" style="max-height:100%; height: 50%" id="notaComuna" :value="cartaSelected.nota_comuna" placeholder="Antecedents" disabled></textarea>
                      <label for="notaComuna">Nota Comuna</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>

        
      </div>
    </div>

</template>
<script>
import axios from 'axios';
import * as bootstrap from 'bootstrap';
export default {
  emits: [
    'showExpedient-error'
  ],
  data () {
    return {
      expedient: [],
      cartaSelected: '',
      th: [
        'Codi',
        'Localització',
        'Incident',
        'Data',
        'Durada',
        'Operador'
      ],
      estatsAgencies: [],
      cartaModal: null,
      collapse: null
    }
  },
  methods: {
    toggleCollapse() {
      this.collapse.toggle()
      this.$refs.collapseBtn.classList.toggle('expanded')
    },
    getExpedient (expID) {
      const self = this;
      axios
        .get(`expedient/${expID}`)
        .then(response => {
          self.expedient = response.data;
          console.log(self.expedient);
        })
        .catch((error) => { });
    },
    getEstatAgencies() {
      const self = this;
      axios
        .get('estatAgencies/')
        .then(response => {
          console.log(response)
          self.estatsAgencies = response.data;
        })
        .catch((error) => { });
    },
    updateAgenciaState(cartaTrucadaId, agenciaId, newEstatValue) {
      this.cartaSelected = false
      const self = this
      axios.put(`updateEstatAgencia/${cartaTrucadaId}/${agenciaId}`, {new_estat_agencies_id: newEstatValue})
          .then(response => {
            if (!response.data.error) {
              self.cartaSelected = response.data.updatedCarta
            } 
          })
          .catch((error)=>{
            console.log(error)
          })
    },
    toLowerCase (string) {
      return [string].map(item =>
        item.charAt(0).toUpperCase() + item.slice(1).toLowerCase()
      ).join('');
      /* return string; */
    },
    showModal (carta) {
      this.cartaSelected = carta
      this.collapse.hide();
      this.cartaModal.show();
    },
    showError (error) {
      this.$emit('showExpedient-error',)
    }
  },
  mounted() {
    this.getEstatAgencies();
    this.cartaModal = new bootstrap.Modal(this.$refs.cartaModal);
    this.collapse = new bootstrap.Collapse(this.$refs.collapse);
  }
}
</script>
<style scoped>

#cartaModal .modal-dialog {
  max-width: 720px;
}
#cartaModal .modal-content {
  position: relative;
  overflow: visible !important;
}
#cartaModal .modal-body {
  min-height: 600px;
}

#collapse-container {
  height: 100%;
  position: absolute;
  left: 100%;
  top: 0;
}
#collapse-btn{
  position: absolute;
  height: 20px;
  right: -20px;
  top: 50%;
  transform: translateY(-50%) rotate(90deg);
  display: flex;
  align-items: center;
  z-index: 10;
}

.expanded i::before {
  content: '\F14B'
}

  .container {
    margin-top: 40px;
    padding: 0 100px;
  }
  #localitzacio, #incident, #agencies {
    margin-top: 40px;
  }

  .state1 {
    background-color: #24DB4A;
  }
  .state2 {
    background-color: #E9E241;
  }
  .state3 {
    background-color: #47B2FF;
  }

  option {
    background-color: white;
  }

  .inspect-carta::before {
    font-size: 24px;
    content: '\F341';
    height: 10px;
    transform: scale(1);
    transform-origin: bottom;
    transition: transform .1s linear;
  }
  .inspect-carta:hover::before {
    content: '\F33E';
    transform: scale(1.2);
    cursor: pointer;
  }
</style>