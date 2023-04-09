<template>
  <div id="card-wrapper">
    <div id="card-container">
      <div class="content">
          <div id="form">
            <div id="form-main" ref="formMain" class="expanded">
              <form-main 
                @get-carta-location="updateLoc"
                @get-carta-interlocutor="updateInterlocutor"
                @is-save-interlocutor="updateSaveInterlocutor"
                @get-map-search-string="updateSearchString">
              </form-main>
              <transition name="fade">
                <div v-show="notaIsExpaneded" class="blur-gradient"></div>
              </transition>
            </div>
            <div id="form-nota" @focusin="expandCompress" @focusout="expandCompress" ref="formNota">
              <form-nota></form-nota>
            </div>
          </div>
          <div id="side">
            <div id="data">DATA</div>
            <!-- MAPA -->
            <!-- <div id="map"><MapApp :arraySearch="mapSearchString"/></div> -->
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
// import MapApp from './mapa/MapApp.vue';
export default {
  components: {
    FormMain,
    FormNota,
    // MapApp
  },
  data () {
    return {
      interlocutor: {},
      localitzacio: {},
      incident: {},
      newInterlocutor: true,
      saveInterlocutor: false,
      mapSearchString: '',
      notaIsExpaneded: false
    }
  },
  methods: {
    expandCompress() {
      this.$refs.formNota.classList.toggle('expanded');
      this.$refs.formMain.classList.toggle('expanded');
      
      console.log("\n\nCARTA TRUCADA:")
      if (this.$refs.formNota.classList.contains('expanded')) {
        console.log('note is expaneded')
        this.notaIsExpaneded = true
      } else {
        console.log('note is compressed')
        this.notaIsExpaneded = false
      }
    },
    updateLoc (locString) {
      this.localitzacioString = locString
    },
    updateInterlocutor(interlocutor) {
      this.interlocutor = interlocutor
    },
    updateSaveInterlocutor (boolean) {
      this.saveInterlocutor = boolean
    },
    updateSearchString (mapString) {
      this.mapSearchString = mapString
    }
  }
}
</script>
<style scoped>
  .blur-gradient {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px; /* set the height of the div */
      border-radius: 0 0 5px 5px;
      /* background: linear-gradient(to bottom, rgba(250, 80, 80, 0) 0%, rgba(250, 80, 80, 1) 100%); */
      background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(230, 230, 230, 1) 100%);
  }
  .fade-enter-active,
    .fade-leave-active {
    transition: opacity 0.4s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
    opacity: 0;
    }

  /* DEV delete for elements inserts */
   #data, #expedients {
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
    gap: 1%
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
    gap: 2%;
    width: 58%;
  }

  #side {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 42%;
  }

  #form-main, #form-nota {
    border: 4px solid var(--primary);
    transition: height .4s ease-in-out;
    overflow: hidden;
  }

  #form-main {
    position: relative;
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

  #form-main, #form-nota, #data, #map, #expedients{
    border-radius: 10px;
    width: 100%;
  }

  #data {
    border: 4px solid black;
    height: 10%;
  }

  #map {
    border: 4px solid var(--secondary);
    height: 44%;
  }

  #expedients {
    border: 4px solid var(--tertiary);
    height: 42%;
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
    color: white;
    border: none;
  }
</style>