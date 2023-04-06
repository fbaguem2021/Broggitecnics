<template>
  <div id="card-wrapper">
    <div id="card-container">
      <div class="content">
          <div id="form">
            <div id="form-main" ref="formMain">
              <form-main @get-carta-location="updateLoc" @get-map-search-string="updateSearchString"></form-main>
            </div>
            <div id="form-nota" class="expanded" @click="expandCompress()" ref="formNota">
              <form-nota></form-nota>
            </div>
          </div>
          <div id="side">
            <div id="data">DATA</div>
            <div id="map">MAP</div>
            <div id="expedients">EXPEDIENTS</div>
          </div>
          <div id="bg"></div>
      </div>
      <div class="buttons">
          <button id="cancel">Cancelar</button>
          <button id="submit">Finalitzar</button>
      </div>
    </div>
  </div>
</template>
<script>
import FormMain from './form/FormMain.vue';
import FormNota from './form/FormNota.vue';
export default {
  components: {
    FormMain,
    FormNota
  },
  data () {
    return {
      
      mapSearchString: ''
    }
  },
  methods: {
    expandCompress(target) {
      this.$refs.formNota.classList.toggle('expanded');
      this.$refs.formMain.classList.toggle('expanded');
    },
    updateLoc (locString) {
      this.localitzacioString = locString
    },
    updateSearchString (mapString) {
      this.mapSearchString = mapString
    }
  }
}
</script>
<style scoped>

  /* DEV delete for elements inserts */
   #data, #map, #expedients {
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 30px;
  }
  /* END DEV */

  #card-wrapper {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    /* border: 4px solid red */
  }

  #card-container {
    flex-grow: 1;
    margin: 20px 20px 40px 20px;
    /* border: 1px solid blue */
  }

  .content {
    position: relative;
    display: flex;
    height: 94%;
    gap: 2%
  }

  #bg {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-color: white;
    border-radius: 10px;
    z-index: -1;
  }

  #form, #side {
    height: 100%;
  }

  #form {
    display: flex;
    flex-direction: column;
    gap: 4%;
    width: 58%;
  }

  #form-main, #form-nota {
    border: 4px solid var(--primary);
    transition: height .4s ease-in-out;
    overflow: hidden;
  }

  #form-main {
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

  #form-nota.expanded{
    height: 40%;
  }

  #side {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 42%;
  }

  #form-main, #form-nota, #data, #map, #expedients{
    border-radius: 10px;
    width: 100%;
  }

  #data {
    border: 4px solid black;
    height: 12%;
  }

  #map {
    border: 4px solid var(--secondary);
    height: 40%;
  }

  #expedients {
    border: 4px solid var(--tertiary);
    height: 40%;
  }

  .buttons {
    display: flex;
    justify-content: center;
    column-gap: 30px;
    margin-left: auto;
    height: 10%;
    width: 42%;
  }

  .buttons #cancel {
    background-color: var(--danger);
  }

  .buttons #submit {
    background-color: var(--success);
  }

  .buttons #cancel, .buttons #submit {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 2.5% 0;
    width: 180px;
    border-radius: 5px;
    color: var(--light);
    border: none;
  }
</style>