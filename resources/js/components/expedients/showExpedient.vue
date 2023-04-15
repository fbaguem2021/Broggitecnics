<template lang="">
    <div>
        <p v-if="s">Selecciona un expedient</p>
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
                    <td><i class="bi bi-eye inspect-carta"></i></td>
                </tr>
            </tbody>
          </table>
        </div>

    </div>
</template>
<script>
import axios from 'axios';
export default {
  data () {
    return {
      expedient: [],
      th: [
        'Codi',
        'Localització',
        'Incident',
        'Data',
        'Durada',
        'Operador'
      ]
    }
  },
  methods: {
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
    toLowerCase (string) {
      return [string].map(item =>
        item.charAt(0).toUpperCase() + item.slice(1).toLowerCase()
      ).join('');
      /* return string; */
    }
  }
}
</script>
<style scoped>
  .container {
    margin-top: 40px;
    padding: 0 100px;
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