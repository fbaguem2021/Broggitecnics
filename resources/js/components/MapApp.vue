<template>
    <div>
      <MapaOptions :arraySearch="arraySearch">
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
    props: ['arraySearch'],
    data() {
        return {
        };
    },
  }
  </script>
  <style scoped></style>
 
  
  