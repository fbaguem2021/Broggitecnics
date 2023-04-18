<template>
    <div>
      <MapaOptions :arraySearch="arraySearch" @añadirAlert="añadirAlertSs" @enviarAgenciasSelec="recibirAgencias">
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
          alertS:"",
          agenciasSeleccionadas: []
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
        },
        agenciasSeleccionadas: {
            deep: true,
            handler: function (newVal, oldVal) {
                alert('agencia seleccionada');
                let arrayIds=[]
                newVal.forEach(element => {
                  arrayIds.push(element[0])
                });
                console.log('arrayIds')
                console.log(arrayIds)
                this.$emit("agenciasSeleccionadas", arrayIds);
            }
        }
    },
    methods: {
      añadirAlertSs(alert){
        this.alertS=alert
        // this.$emit('añadirAlertSuccess', this.alertS)
    },
    recibirAgencias(idAgencias){
      this.agenciasSeleccionadas=idAgencias
    }
    },
  }
  </script>
  <style scoped></style>
 
  
  