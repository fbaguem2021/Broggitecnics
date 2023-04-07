<template>
    <form @input.prevent="emitLocation">
      <div class="row align-items-center">
        <div class="d-flex col-4 mb-2">
          <label class="form-check-label pe-2" for="isCat">Catalunya</label>
          <input v-model="isCat" class="form-check-input" type="checkbox" value="" id="isCat" @click="setOutOfCat" >
        </div>
      </div>
      <div class="row">        
        <div class="col-4" ref="provincia">
            <div class="form-floating mb-3">
              <input v-model="provinciaInput" @input="handleInput('provinciaInput', $event, provincies)" type="text" class="form-control" id="provincia" placeholder="provincia" list="provinciesList" autocomplete="off" required>
              <label for="provincia">Provincia</label>
              <datalist v-if="provincies && isCat" id="provinciesList">
                <option v-for="(provincia, index) in filteredList(provincies, provinciaInput) " :key="index" :value="provincia.nom"></option>
              </datalist>
            </div>
        </div>
        <transition name="fade" @before-enter="adjustColumnSizes" @after-leave="adjustColumnSizes">
          <div v-show="isCat" class="col-4">
            <div class="form-floating mb-3">
              <input v-model="comarcaInput" @input="handleInput('comarcaInput', $event, comarques)" type="text" class="form-control" id="comarca" placeholder="comarca" list="comarquesList" autocomplete="off">
              <label for="comarca">Comarca</label>
              <datalist v-if="comarques" id="comarquesList">
                <option v-for="(comarca, index) in filteredList(comarques, comarcaInput)" :key="index" :value="comarca.nom"></option>
              </datalist>
            </div>
        </div>
        </transition>
        <div class="col-4" ref="municipi">
            <div class="form-floating mb-3">
              <input v-model="municipiInput" @input="handleInput('municipiInput', $event, municipis)" type="text" class="form-control" id="municipi" placeholder="municipi" list="muncipisList" autocomplete="off">
              <label for="municipi">Municipi<span v-show="!isCat" style="opacity: 0.5; font-size: 15px;"> - opcional</span></label>
              <datalist v-if="municipis && isCat" id="muncipisList">
                <option v-for="(municipi, index) in filteredList(municipis, municipiInput)" :key="index" :value="municipi.nom"></option>
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
                <datalist v-if="tipusLocalitzacions" id="localitzacionsList">
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
      provinciaInput: '',
      comarcaInput: '',
      municipiInput: '',
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
      } else if (this.cartaLocation.provincia) {
        const comarques = this.localitzacioData.comarques.filter(comarca => comarca.provincies_id === this.cartaLocation.provincia);
        return this.localitzacioData.municipis.filter(municipi => comarques.some(comarca => comarca.id === municipi.comarques_id))
      } else{
        return this.localitzacioData.municipis ? this.localitzacioData.municipis : []
      }
    },
    tipusLocalitzacions() {
      return this.localitzacioData.tipusLoc ? this.localitzacioData.tipusLoc : []
    },
    mapSearchString () {
      if (this.isCat) {
        const mapString = [`${this.municipiInput},`, this.tipusLocInput, this.cartaLocation.descripcioLoc].filter(Boolean)
        return mapString.join(' ')
      } else {
        const mapString = ['Espanya, ', this.provinciaInput, this.municipiInput].filter(Boolean)
        return mapString.join(' ')
      }
      
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
    },
  },
  methods: {
    setOutOfCat () {
      if (isCat) {
        this.cartaLocation.tipusLoc = ''
      } else {
        this.cartaLocation.tipusLoc = 60; // if isCat = false set tipus localitzacio id to 60 = Provincia
      }
      this.comarcaInput = ''
      this.cartaLocation.comarca = ''
  
      this.provinciaInput = ''
      this.cartaLocation.provincia = ''

      this.municipiInput = ''
      this.cartaLocation.municipi = ''
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
      if (this.isCat) {
        const inputValue = event.target.value
        const matchedInputLetter = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase().startsWith(inputValue.toLowerCase()))
        const matchedInputValue = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())

        if (!matchedInputLetter){
          const previousValue = this[inputName].slice(0, -1)
          this[inputName] = previousValue
        } else if (inputValue.trim() === '') {
          console.log("empppty!!")
          this.cartaLocation = {
            ...this.cartaLocation,
            [event.target.id]: ''
          }
        }
        if (matchedInputValue && this.isCat) {
            switch (event.target.id) {
              case 'provincia':
                this.provinciaSelected(matchedInputValue)
                break;
              case 'comarca':
                this.comarcaSelected(matchedInputValue)
                break;
              case 'municipi':
                this.municipiSelected(matchedInputValue)
              break;
            }
          this.cartaLocation = {
            ...this.cartaLocation,
            [event.target.id]: matchedInputValue.id
          }
        } 
      } else {
        this.cartaLocation.descripcioLoc = `${this.provinciaInput} ${this.municipiInput}`
      }
    },
    comarcaSelected(comarca) {
      const provincia = this.provincies.find(provincia => provincia.id === comarca.provincies_id)
          this.provinciaInput = provincia.nom
          this.cartaLocation.provincia = provincia.id
          /* When valid Comarca is set checks if Municipi has been set and if belongs to this Comarca
          If not clear the input and the carta location municipi */
          if (this.cartaLocation.municipi) {
            console.log(this.municipis.find(mun => mun.id === this.cartaLocation.municipi)?.comarques_id)
            console.log(comarca.id)
            if ( (comarca.id !== this.municipis.find(municipi => municipi.id === this.cartaLocation.municipi)?.comarques_id)){
              console.log("The municipi selected doesnt belong to this Comarca!")
              this.cartaLocation.municipi = ''
              this.municipiInput = ''
            }else {
              console.log("The municipi selected is CHILLING with this Comarca!")
            }
          }
    },
    provinciaSelected (provincia) {
      if ( this.cartaLocation.comarca && provincia.id !== this.comarques.find(comarca => comarca.id === this.cartaLocation.comarca)?.provincies_id ) {
            console.log('Comarca provincies_id doesnt match with provincia selected')
            this.comarcaInput = ''
            this.cartaLocation.comarca = ''
            if (this.cartaLocation.municipi) {
              this.municipiInput = ''
              this.cartaLocation.municipi = ''
            }
          } else {
            console.log('provincia chilling with the set comarca')
          }   
    },
    municipiSelected (municipi) {
      const comarca = this.comarques.find(comarca => comarca.id === municipi.comarques_id)
          this.comarcaInput = comarca.nom
          this.cartaLocation.comarca = comarca.id
    
          const provincia = this.provincies.find(provincia => provincia.id === comarca.provincies_id)
          this.provinciaInput = provincia.nom
          this.cartaLocation.provincia = provincia.id
    },

    /**
     * Adjusts Municipi and Provincia bootstrap cols when Comarca leaving transition finishes due to isCat unchecked
     */
    adjustColumnSizes() {
        this.$refs.provincia.classList.toggle('col-4');
        this.$refs.provincia.classList.toggle('col-6');
        this.$refs.municipi.classList.toggle('col-4');
        this.$refs.municipi.classList.toggle('col-6');
    }
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