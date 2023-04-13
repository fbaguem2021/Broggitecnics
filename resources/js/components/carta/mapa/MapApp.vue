<template>
    <div>
      <MapaOptions :arraySearch="arraySearch" @añadirAlert="añadirAlertSs">
        <template v-slot:child-component>
          <BaseMap />
        </template>
      </MapaOptions>
    </div>
    
  </template>

  <script>
 import BaseMap from './Mapa.vue'
  import MapaOptions from './AgenciasPrimarias.vue'
  import * as bootstrap from 'bootstrap'
  
  export default {
    components: {
        BaseMap,
        MapaOptions
    },
    props: ['arraySearch','alertCerrada'],
    data() {
        return {
          alertS:""
        };
    },
    watch:{
      alertS: {
            immediate: true,
            handler(newVal, oldVal) {
              this.$emit("changeAlert", this.alertS)
            }
        },
        alertCerrada: {
            immediate: true,
            handler(newVal, oldVal) {
              this.alertS=this.alertCerrada
            }
        }
    },
    methods: {
      añadirAlertSs(alert){
        this.alertS=alert
        // this.$emit('añadirAlertSuccess', this.alertS)
    }
    },
  }
  </script>
  <style scoped></style>
 
  
  