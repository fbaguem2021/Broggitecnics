<template>
    <form @input.prevent="emitLocation">
      <div class="row align-items-center">
        <div class="d-flex col-4 mb-2">
          <label class="form-check-label pe-2" for="isCat">Catalunya</label>
          <input v-model="isCat" class="form-check-input" type="checkbox" value="" id="isCat" >
        </div>
      </div>
      <div class="row">        
        <div class="col-4" ref="provincia">
            <div class="form-floating mb-3">
              <input v-model="provinciaInput.name" @input="handleInput('provinciaInput', $event, provincies)" type="text" class="form-control" id="provincia" placeholder="provincia" list="provinciesList" autocomplete="off" required>
              <label for="provincia">Provincia</label>
              <datalist v-if="provincies" id="provinciesList">
                <option v-for="(provincia, index) in filteredList(provincies, provinciaInput.name) " :key="index" :value="provincia.nom"></option>
              </datalist>
            </div>
        </div>
        <transition name="fade" @before-enter="adjustColumnSizes" @after-leave="adjustColumnSizes">
          <div v-show="isCat" class="col-4">
            <div class="form-floating mb-3">
              <input v-model="comarcaInput.name" @input="handleInput('comarcaInput', $event, comarques)" type="text" class="form-control" id="comarca" placeholder="comarca" list="comarquesList" autocomplete="off">
              <label for="comarca">Comarca</label>
              <datalist v-if="comarques" id="comarquesList">
                <option v-for="(comarca, index) in filteredList(comarques, comarcaInput.name)" :key="index" :value="comarca.nom"></option>
              </datalist>
            </div>
        </div>
        </transition>
        <div class="col-4" ref="municipi">
            <div class="form-floating mb-3">
              <input v-model="municipiInput.name" @input="handleInput('municipiInput', $event, municipis)" type="text" class="form-control" id="municipi" placeholder="municipi" list="muncipisList" autocomplete="off">
              <label for="municipi">Municipi<span v-show="!isCat" style="opacity: 0.5; font-size: 15px;"> - opcional</span></label>
              <datalist v-if="municipis" id="muncipisList">
                <option v-for="(municipi, index) in filteredList(municipis, municipiInput.name)" :key="index" :value="municipi.nom"></option>
              </datalist>
            </div>
        </div>
      </div>
      <transition name="fade">
        <div v-show="isCat">
          <div class="row">
            <div class="col-8">
              <div class="form-floating mb-3">
                <input v-model="tipusLocInput" type="text" class="form-control" id="tipusLoc" placeholder="Tipus localització" list="localitzacionsList" @input="handleInput('tipusLocInput', $event, tipusLocalitzacions)" autocomplete="off">
                <datalist v-if="showTipusLoc(tipusLocInput)" id="localitzacionsList">
                  <option v-for="tipus in filteredList(tipusLocalitzacions, tipusLocInput)" :key="tipus.id" :value="tipus.nom"></option>
                </datalist>
                <label for="provincia">Tipus de localització</label>
              </div>
            </div>
            <div v-show=" tipusLocInput.toLowerCase() === 'carrer' " class="col-4">
              <div class="form-floating mb-3">
                <input v-model="tipusVia" type="text" class="form-control" id="tipusVia" placeholder="Tipus via" autocomplete="off">
                <label for="tipusVia">Via</label>
              </div>
            </div>
          </div>
          <component :is="currentLocComponent" @update-description="updateLocDescription"></component>
        </div>
      </transition>
      
      <div class="form-floating mt-2">
          <textarea class="form-control" placeholder="Referencies" id="referenciesTextarea" style="height: 60px"></textarea>
          <label for="referenciesTextarea">Altres referències</label>
      </div>
    </form>
</template>
<script>
import CarrerForm from './localitzacions/CarrerForm.vue';
import CarreteraForm from './localitzacions/CarreteraForm.vue';
import PuntSingularForm from './localitzacions/PuntSingularForm.vue';

export default {
  props: {
    localitzacioData: {
      required: true
    }
  },
  components: {
    CarrerForm,
    CarreteraForm,
    PuntSingularForm
  },
  emits: [
    "get-location",
    'map-serach-string'
  ],
  data() {
    return {
      isCat: true,
      provinciaInput: {
        'name': '',
      },
      comarcaInput: {
        'name': '',
        'provincia_id': ''
      },
      municipiInput: {
        'name': '',
        'comarca_id': ''
      },
      tipusLocInput: '',
      tipusVia: "c/",
      cartaLocation: {
        comarca: '',
        provincia: '',
        municipi: '',
        tipusLoc: '',
        descripcioLoc: '',
        detallLoc: '',
        altresRefs: ''
      }
    }
  },
  computed: {
    provincies () {

      return this.localitzacioData.provincies ? this.localitzacioData.provincies : []
    },
    comarques () {
      if (this.cartaLocation.provincia) {
        return this.localitzacioData.comarques.filter(comarca => comarca.provincies_id === this.cartaLocation.provincia)

      } else {
        return this.localitzacioData.comarques ? this.localitzacioData.comarques : []
      }
    },
    municipis () {
      if (this.cartaLocation.comarca) {
        return this.localitzacioData.municipis.filter(municipi => municipi.comarques_id == this.cartaLocation.comarca)
      } else {
        return this.localitzacioData.municipis ? this.localitzacioData.municipis : []
      }
    },
    tipusLocalitzacions() {
      return this.localitzacioData.tipusLoc ? this.localitzacioData.tipusLoc : []
    },
    mapSearchString () {
      const mapString = [this.cartaLocation.municipi, this.tipusLocInput, this.cartaLocation.descripcioLoc].filter(Boolean)
      return mapString.join(' ')
    },
    currentLocComponent() {
      switch (this.tipusLocInput.toLowerCase()) {
        case 'carrer':
          return 'CarrerForm'
        case 'carretera':
          return 'CarreteraForm'
        case 'punt singular':
          return 'PuntSingularForm'
        default:
          return null
      }
    }
  },
  methods: {
    showTipusLoc(input) {
      return input.length >= 0 && this.filteredList(this.tipusLocalitzacions, input).length > 0
    },
    /**
     * Returns array of matching options between localitzacioData and input
     */
    filteredList(list, value) {
      return list.filter(tipus => tipus.nom.toLowerCase().startsWith(value.toLowerCase()))
    },

    /**
     * Sends to parent the cartaLocation object
     * If the location is not in cat tipus location is set to Provincia
     */
    emitLocation () {
      if (!this.isCat) {
        this.cartaLocation.tipusLoc = 60; // if isCat = false set tipus localitzacio id to 60 = Provincia
      }
      this.$emit('get-location', this.cartaLocation)
      this.$emit('map-serach-string', this.mapSearchString)
    },

    /**
     * Updates the location description given by this child components:
     * - CarrerForm
     * - CarreteraForm
     * - PuntSingularForm
     */
    updateLocDescription (desc) {
      this.cartaLocation.descripcioLoc = desc;
    },

    /**
     * Allows ONLY to input tipus localització matching one of the options
     * If whole input matches an option sets cartaLocation.tipusLocId to the list option id
     */
    handleInput(inputName, event, list) {
      const inputValue = event.target.value
      const matchedInputLetter = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase().startsWith(inputValue.toLowerCase()))
      const matchedInputValue = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())

      if (inputValue.trim() === '') {
        console.log("empppty!!")
        this.cartaLocation = {
          ...this.cartaLocation,
          [event.target.id]: ''
        }

      }else if (!matchedInputLetter) {
        const previousValue = this[inputName].name.slice(0, -1)
        this[inputName].name = previousValue
      } else if (matchedInputValue) {
        if (event.target.id === 'comarca') {
          const provincia = this.localitzacioData.provincies.find(provincia => provincia.id === matchedInputValue.provincies_id)
          this.provinciaInput.name = provincia.nom
          this.cartaLocation.provincia = provincia.id
        }
        if (event.target.id === 'municipi') {
          const comarca = this.localitzacioData.comarques.find(comarca => comarca.id === matchedInputValue.comarques_id)
          this.comarcaInput.name = comarca.nom
          this.cartaLocation.comarca = comarca.id
    
          const provincia = this.localitzacioData.provincies.find(provincia => provincia.id === comarca.provincies_id)
          this.provinciaInput.name = provincia.nom
          this.cartaLocation.provincia = provincia.id
        }
        this.cartaLocation = {
          ...this.cartaLocation,
          [event.target.id]: matchedInputValue.id
        }
      }
    },

    /**
     * Adjusts Municipi and Provincia bootstrap cols when Comarca leaving transition finishes due to isCat unchecked
     */
    adjustColumnSizes() {
        this.$refs.provincia.classList.toggle('col-4');
        this.$refs.provincia.classList.toggle('col-6');
        this.$refs.municipi.classList.toggle('col-4');
        this.$refs.municipi.classList.toggle('col-6');
    },
  },
  mounted() {
  },
}
</script>
<style scoped>


.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}




  #referenciesTextarea {
    min-height: 60px;
  }
</style>