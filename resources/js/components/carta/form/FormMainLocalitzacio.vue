<template>
    <form id="location-form" 
        @input.prevent="validateForm($event.target)"
        @focusin=" removeValidationClasses($event.target)"
        @focusout="this.validateInput($event.target)">
      <div class="row align-items-center">
        <div class="d-flex col-4 mb-2" id="isCat-conatiner">
          <label class="form-check-label pe-2" for="isCat">Catalunya</label>
          <input v-model="isCat" class="form-check-input" type="checkbox" value="" id="isCat" @click="setOutOfCat" >
        </div>
      </div>
      <div class="row">        
        <div class="col-3" ref="provincia">
            <div class="form-floating mb-3">
              <input v-model="provincia.input" @input="handleInput($event, provincies)" type="text" class="form-control is-invalid" id="provincia" placeholder="provincia" list="provinciesList" autocomplete="off" ref="provinciaInput">
              <label for="provincia">Provincia</label>
              <datalist v-if="provincies && isCat" id="provinciesList">
                <option v-for="(prov, index) in filteredList(provincies, provincia.input) " :key="index" :value="prov.nom"></option>
              </datalist>
            </div>
        </div>
        <transition name="fade" @before-enter="adjustColumnSizes" @after-leave="adjustColumnSizes">
          <div v-show="isCat" class="col-4">
            <div class="form-floating mb-3">
              <input v-model="comarca.input" @input="handleInput($event, comarques)" type="text" class="form-control is-invalid" id="comarca" placeholder="comarca" list="comarquesList" autocomplete="off" ref="comarcaInput">
              <label for="comarca">Comarca</label>
              <datalist v-if="comarques" id="comarquesList">
                <option v-for="(com, index) in filteredList(comarques, comarca.input)" :key="index" :value="com.nom"></option>
              </datalist>
            </div>
        </div>
        </transition>
        <div class="col-5" ref="municipi">
            <div class="form-floating mb-3">
              <input v-model="municipi.input" @input="handleInput($event, municipis)" type="text" class="form-control is-invalid" id="municipi" placeholder="municipi" list="muncipisList" autocomplete="off" ref="municipiInput">
              <label for="municipi">Municipi<span v-show="!isCat" style="opacity: 0.5; font-size: 15px;"> - opcional</span></label>
              <datalist v-if="municipis && isCat" id="muncipisList">
                <option v-for="(mun, index) in filteredList(municipis, municipi.input)" :key="index" :value="mun.nom"></option>
              </datalist>
            </div>
        </div>
      </div>
      <transition name="fade">
        <div v-show="isCat">
          <div class="row">
            <div class="col-8">
              <div class="form-floating mb-3">
                <input v-model="tipusLoc.input" type="text" class="form-control is-invalid" id="tipusLoc" placeholder="Tipus localització" list="localitzacionsList" @input="handleInput($event, tipusLocalitzacions)" autocomplete="off" ref="tipusLocInput">
                <datalist v-if="tipusLocalitzacions" id="localitzacionsList">
                  <option v-for="tipus in filteredList(tipusLocalitzacions, tipusLoc.input)" :key="tipus.id" :value="tipus.nom"></option>
                </datalist>
                <label for="provincia">Tipus de localització</label>
              </div>
            </div>
            <div v-show=" tipusLoc.input.toLowerCase() === 'carrer' " class="col-4">
              <div class="form-floating mb-3">
                <input v-model="tipusVia" type="text" class="form-control" id="tipusVia" placeholder="Tipus via" autocomplete="off">
                <label for="tipusVia">Via</label>
              </div>
            </div>
          </div>
          <transition name="fade">
            <component :is="currentLocComponent" @update-description="updateLocDescription"></component>
          </transition>
        </div>
      </transition>
      
      <div class="form-floating" id="referencies-textArea-container">
          <textarea class="form-control" placeholder="Referencies" id="referenciesTextarea"></textarea>
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
    'get-map-serach-string',
    'is-form-valid'
  ],
  data() {
    return {
      isCat: true,
      provincia: {
        id: '',
        input: '',
        isValid: false
      },
      comarca: {
        id: '',
        input: '',
        provincia_id: '',
        isValid: false
      },
      municipi: {
        id: '',
        input: '',
        comarca_id: '',
        isValid: false
      },
      tipusLoc: {
        id: '',
        input: '',
        isValid: false
      },
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
      if (this.provincia.isValid) {
        return this.localitzacioData.comarques.filter(comarca => comarca.provincies_id === this.provincia.id)
      } else {
        return this.localitzacioData.comarques ? this.localitzacioData.comarques : []
      }
    },
    municipis () {
      if (this.comarca.isValid) {
        return this.localitzacioData.municipis.filter(municipi => municipi.comarques_id == this.comarca.id)
      } else if (this.provincia.isValid) {
        const comarquesIds = new Set();
        for (const comarca of this.localitzacioData.comarques) {
          if (comarca.provincies_id === this.provincia.id) {
            comarquesIds.add(comarca.id);
          }
        }
        return this.localitzacioData.municipis.filter(municipi => comarquesIds.has(municipi.comarques_id))
      } else{
        return this.localitzacioData.municipis ? this.localitzacioData.municipis : []
      }
    },
    tipusLocalitzacions() {
      return this.localitzacioData.tipusLoc ? this.localitzacioData.tipusLoc : []
    },
    mapSearchString () {
      if (this.isCat) {
        const mapString = [this.provincia.input, this.municipi.input, this.tipusLoc.input, this.cartaLocation.descripcioLoc].filter(Boolean)
        return mapString.join(' ')
      } else {
        const mapString = ['Espanya, ', this.provincia.input, this.municipi.input].filter(Boolean)
        return mapString.join(' ')
      }
      
    },
    currentLocComponent() {
      switch (this.tipusLoc.input.toLowerCase()) {
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
    validateForm (el) {
      this.validateInput(el)
      const isValid =  (this.provincia.isValid &&
                         this.comarca.isValid &&
                         this.municipi.isValid &&
                         this.tipusLoc.isValid) 
      this.$emit('is-form-valid', isValid)
    },
    validateInput (el) {
      if (el.id != 'isCat') {
        var isValid = false;
        isValid = this[el.id].isValid ? true : false
        /* if (el.id != 'tipusLoc') {
          el.classList.toggle('is-valid', isValid)
        } else {
          el.classList.toggle('is-invalid', !isValid)
          el.classList.toggle('is-valid', isValid)
        } */
        el.classList.toggle('is-invalid', !isValid)
        el.classList.toggle('is-valid', isValid)
      }
    },
    removeValidationClasses(el) {
      el.classList.remove('is-valid', 'is-invalid');
    },
    resetProvincia () {
      this.provincia.id = ''
      this.provincia.input = ''
      this.provincia.isValid = false
      this.cartaLocation.provincia = ''
      this.validateInput(this.$refs.provinciaInput)

    },
    resetComarca() {
      this.comarca.id = ''
      this.comarca.input = ''
      this.comarca.provincia_id = ''
      this.comarca.isValid = false
      this.cartaLocation.comarca = ''
      this.validateInput(this.$refs.comarcaInput)
    },
    resetMunicipi () {
      this.municipi.id = ''
      this.municipi.input = ''
      this.municipi.comarca_id = ''
      this.municipi.isValid = false
      this.cartaLocation.municipi = ''
      this.validateInput(this.$refs.municipiInput)
    },
    setOutOfCat () {
      this.resetProvincia()
      this.resetComarca()
      this.resetMunicipi()
      this.cartaLocation.tipusLoc = isCat ? '' : 60 // if !isCat set tipus localitzacio id to 60 = Provincia
      this.cartaLocation.descripcioLoc = ''
    },
    /**
     * Returns array of matching options between localitzacioData and input
     */
    filteredList(list, value) {
      return list.filter(tipus => tipus.nom.toLowerCase().startsWith(value.toLowerCase()))
    },

    /**
     * Sends to parent the cartaLocation object and the map search string
     */
    emitLocation () {
      this.$emit('get-location', this.cartaLocation)
      this.$emit('get-map-serach-string', this.mapSearchString)
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
     * Allows ONLY to input some value of the list options
     * If whole input matches an option sets the cartaLocation property =  matchedValue.id
     */
    handleInput(event, list) {
      if (this.isCat) {
        const inputValue = event.target.value
        const matchedInputLetter = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase().startsWith(inputValue.toLowerCase()))
        const matchedInputValue = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())

        if (matchedInputValue) {
          this[event.target.id].isValid = true
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
          console.log(event.target.id + " isValid: " + this[event.target.id].isValid)
        } else {
          this[event.target.id].isValid = false
          console.log(event.target.id + " isValid: " +this[event.target.id].isValid)
          //This code only allows only to input letter by letter valid inputs
          //
          /* if (!matchedInputLetter){
            const previousValue = this[event.target.id].input.slice(0, -1)
            this[event.target.id].input = previousValue
          } */
        }

      } else { // no Cat
        this.cartaLocation.descripcioLoc = `${this.provincia.input} ${this.municipi.input}`
      }
    },

    provinciaSelected (validProv) {
      this.provincia.id = validProv.id
      this.provincia.input = validProv.nom
      this.provincia.isValid = true
      this.validateInput(this.$refs.provinciaInput)
      //Check if comarca is set and belongs to provincia
      console.log("\nFORM MAIN LOC - provinciaSelected")
      if (this.provincia.id !== this.comarca.provincia_id) {
            console.log("Reset Comarca: comarca.provincia_id that was selected doesnt match the new provincia.id ")
            this.resetComarca()
            if (this.municipi.isValid) {
              console.log("Reset Municipi: municpi was set to matched comarca")
              this.resetMunicipi()
            }
      } else {
        if (this.comarca.provincia_id) {
          console.log("No reset: comarca.provincia_id matches new provincia.id")
        }else {
          console.log("No reset: comarca is not set")
        }
      }
    },

    comarcaSelected(validCom) {
      this.comarca.id = validCom.id
      this.comarca.input = validCom.nom
      this.comarca.isValid = true
      this.comarca.provincia_id = validCom.provincies_id
      this.validateInput(this.$refs.comarcaInput)

      // If new comarca doesn't belong to the selected provincia OR provincia is not setted
      // Selectprovincia
      console.log("\nFORM MAIN LOC - comarcaSelected")
      if (this.comarca.provincia_id !== this.provincia.id) {
        console.log("Provincia.id different than comarca.provincies_id selected")
        const provincia = this.provincies.find(provincia => provincia.id === this.comarca.provincia_id)
        if (provincia) {
          this.provinciaSelected(provincia)
        }
      }
      /* 
        * Checks if municipi has been set and if it belongs to this new comarca
        * If not clear the input and the carta location municipi 
        */
      if (this.comarca.id !== this.municipi.comarca_id) {
        console.log("The municipi selected doesnt belong to this Comarca!\n")
        this.resetMunicipi()
      }else {
        if (this.municipi.isValid) {
          console.log("Municipi selected is CHILLING with this new Comarca!")
        } else {
          console.log("No municipi is selected")
        }
      }
    },

    municipiSelected (validMun) {
      this.municipi.id = validMun.id
      this.municipi.input = validMun.nom
      this.municipi.comarca_id = validMun.comarques_id
      this.municipi.isValid = true
      this.validateInput(this.$refs.municipiInput)

      console.log("\nFORM MAIN LOC - municipiSelected")
      if (this.municipi.comarca_id != this.comarca.id) {
        console.log("Comarca is not set or municipi doesn't belong to comarca")
        const comarca = this.comarques.find(comarca => comarca.id === this.municipi.comarca_id)
        if (comarca) {
          this.comarcaSelected(comarca)
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
    }
  },
  mounted() {
  },
}
</script>

<style scoped>

  #location-form {
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  #referencies-textArea-container {
    flex-grow: 1;
    margin-bottom: 20px;
  }

  #referenciesTextarea {
    height: 100%;
  }

  #isCat-conatiner {
    min-width: 125px;
  }
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.2s ease-in-out;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }


</style>