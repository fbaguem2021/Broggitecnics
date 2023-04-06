<template>
    <form @input.prevent="emitLocation">
      <div class="row align-items-center">
        <div class="d-flex col-4 mb-2">
          <label class="form-check-label pe-2" for="isCat">Catalunya</label>
          <input v-model="isCat" class="form-check-input" type="checkbox" value="" id="isCat" >
        </div>
      </div>
      <div class="row">
        <transition name="fade" @before-enter="adjustColumnSizes" @after-leave="adjustColumnSizes">
          <div v-show="isCat" class="col-4">
            <div class="form-floating mb-3">
              <input v-model="cartaLocation.comarca" type="text" class="form-control" id="comarca" placeholder="comarca" list="comarquesList" autocomplete="off">
              <label for="comarca">Comarca</label>
              <datalist v-if="comarques" id="comarquesList">
                <option v-for="comarca in comarques" :key="comarca.id" :value="comarca.nom"></option>
              </datalist>
            </div>
        </div>
        </transition>
        
        <div class="col-4" ref="provincia">
            <div class="form-floating mb-3">
              <input v-model="cartaLocation.provincia" type="text" class="form-control" id="provincia" placeholder="provincia" required>
              <label for="provincia">Provincia</label>
            </div>
        </div>
        <div class="col-4" ref="municipi">
            <div class="form-floating mb-3">
              <input v-model="cartaLocation.municipi" type="text" class="form-control" id="municipi" placeholder="municipi">
              <label for="municipi">Municipi<span v-show="!isCat" style="opacity: 0.5; font-size: 15px;"> - opcional</span></label>
            </div>
        </div>
      </div>
      <transition name="fade">
        <div>
          <div v-show="isCat" class="row">
            <div class="col-8">
              <div class="form-floating mb-3">
                <input v-model="tipusLocInput" type="text" class="form-control" id="tipusLoc" placeholder="Tipus localització" list="localitzacionsList" @input="handleInput" autocomplete="off">
                <datalist v-if="showTipusLoc" id="localitzacionsList">
                  <option v-for="tipus in filteredList(tipusLocalitzacions)" :key="tipus.id" :value="tipus.nom"></option>
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
import axios from 'axios';
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
      carrerString: '',
      tipusVia: "c/",
      tipusLocInput: '',
      cartaLocation: {
        comarca: '',
        provincia: '',
        municipi: '',
        tipusLocId: null,
        descripcioLoc: null,
        detallLoc: null,
        altresRefs: ''
      }
    }
  },
  computed: {
    comarques () {
      return this.localitzacioData.comarques
    },
    provincies () {
      return this.localitzacioData.provincies
    },
    municipis () {
      return this.localitzacioData.municipis
    },
    tipusLocalitzacions() {
      return this.localitzacioData.tipusLoc
    },
    mapSearchString () {
      const mapString = [this.cartaLocation.municipi, this.tipusLocInput, this.cartaLocation.descripcioLoc].filter(Boolean)
      return mapString.join(' ')
    },
    showTipusLoc() {
      return this.tipusLocInput.length > 0 && this.filteredList(this.tipusLocalitzacions).length > 0
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
    filteredList(list) {
      return list.filter(tipus => tipus.nom.toLowerCase().startsWith(this.tipusLocInput.toLowerCase()))
    },
    emitLocation () {
      if (!this.isCat) {
        this.cartaLocation.tipusLocId = 60; // if is not cat set tipus loc id to 60 = Provincia
      }
      this.$emit('get-location', this.cartaLocation)
      this.$emit('map-serach-string', this.mapSearchString)
    },

    updateLocDescription (desc) {
      this.cartaLocation.descripcioLoc = desc;
    },

    /**
     * Allows ONLY to input tipus localització matching one of the options
     * If whole input matches an option sets cartaLocation.tipusLocId to the list option id
     */
    handleInput() {
      const matchedLetter = this.filteredList(this.tipusLocalitzacions).find(tipus => tipus.nom.toLowerCase().startsWith(this.tipusLocInput.toLowerCase()))
      const matchedTipusLocation = this.filteredList(this.tipusLocalitzacions).find(tipus => tipus.nom.toLowerCase() === this.tipusLocInput.toLowerCase())
      
      if (!matchedLetter) {
        const previousValue = this.tipusLocInput.slice(0, -1)
        this.tipusLocInput = previousValue
      } else if (matchedTipusLocation){
        this.cartaLocation.tipusLocId = matchedTipusLocation.id
      }
    },

    /**
     * Adjusts Municipi and Provincia bootstrap cols after removing Comarca transition finished
     * due to uncheck isCat 
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