<template>
    <form id="localitzacio-form"
      @input="updateCartaData"
      @focusin=" removeValidationClasses($event.target)"
      @focusout="validateInput($event.target)">
      <div id="invalid-legend">
        <i class="bi bi-exclamation-circle me-2"></i><span>Els camps amb aquest icona son obligatoris</span>
      </div>
      <div class="row align-items-center">
        <div class="col-1 d-flex mb-2" id="isCat-conatiner">
          <label class="form-check-label pe-2" for="isCat">Catalunya</label>
          <input v-model="isCat" class="form-check-input" type="checkbox" value="" id="isCat" @click="toggleIsCat($event)">
          <i class="bi bi-arrow-left" id="isCatFocus"></i>
          <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Are you currently located in Catalonia?"></i>
        </div>
      </div>
      <div class="row">        
        <div class="col-3" ref="provincia">
            <div class="form-floating mb-3">
              <input v-model="provincia.input" 
                @input="handleInput($event.target, provincies)" 
                :class="isCat ? 'is-invalid' : '' "
                type="text" class="form-control" id="provincia" placeholder="provincia" list="provinciesList" autocomplete="off" ref="provinciaInput">
              <label for="provincia">Provincia</label>
              <datalist v-if="provincies && isCat" id="provinciesList">
                <option v-for="(prov, index) in filteredList(provincies, provincia.input) " :key="index" :value="prov.nom"></option>
              </datalist>
            </div>
            <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="In which province are you currently located?"></i>
        </div>
        
        <transition name="fade" @before-enter="adjustColumnSizes" @after-leave="adjustColumnSizes">
          <div v-show="isCat" class="col-4">
            <div class="form-floating mb-3">
              <input v-model="comarca.input" 
                @input="handleInput($event.target, comarques)" type="text" class="form-control is-invalid" id="comarca" placeholder="comarca" list="comarquesList" autocomplete="off" ref="comarcaInput">
              <label for="comarca">Comarca</label>
              <datalist v-if="comarques" id="comarquesList">
                <option v-for="(com, index) in filteredList(comarques, comarca.input)" :key="index" :value="com.nom"></option>
              </datalist>
            </div>
            <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Which region are you currently in?"></i>
        </div>
        </transition>
        <div class="col-4" ref="municipi">
            <div class="form-floating mb-3">
              <input v-model="municipi.input" 
                @input="handleInput($event.target, municipis)" 
                :class="isCat ? 'is-invalid' : '' "
                type="text" class="form-control" id="municipi" placeholder="municipi" list="muncipisList" autocomplete="off" ref="municipiInput">
              <label for="municipi">Municipi<span v-show="!isCat" style="opacity: 0.5; font-size: 15px;"> - opcional</span></label>
              <datalist v-if="municipis && isCat" id="muncipisList">
                <option v-for="(mun, index) in filteredList(municipis, municipi.input)" :key="index" :value="mun.nom"></option>
              </datalist>
            </div>
            <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Could you provide the name of your municipality or city?"></i>
        </div>
        <div class="col-1 pb-3 reset-row">
            <i class="bi bi-clipboard-x" @click="clearProvinciaComarcaMunicipi($event)" @keydown="clearProvinciaComarcaMunicipi($event)" tabindex="0"></i>
        </div>
      </div>
      <transition name="fade">
        <div v-show="isCat">
          <div class="row">
            <div class="col-8">
              <div class="form-floating mb-3">
                <input v-model="tipusLoc.input" type="text" class="form-control is-invalid" id="tipusLoc" placeholder="Tipus localització" list="localitzacionsList" @input="handleInput($event.target, tipusLocalitzacions)" autocomplete="off" ref="tipusLocInput">
                <datalist v-if="tipusLocalitzacions" id="localitzacionsList">
                  <option v-for="tipus in filteredList(tipusLocalitzacions, tipusLoc.input)" :key="tipus.id" :value="tipus.nom"></option>
                </datalist>
                <label for="provincia">Tipus de localització</label>
              </div>
            <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" 
              data-bs-title="<p>Could you please tell me the type of location you are currently at?</p><p>Opcions:</p><ul><li>'Street' (carrer)</li> <li>'Highway' (carretera)</li> <li>'Town or city' (entidat població)</li> <li>'Landmark or point of interest' (punt singular)</li></ul>"></i>
            </div>
            <div class="col-4">
              <div class="form-floating mb-3">
                <input v-model="tipusVia.input" type="text" class="form-control" id="tipusVia" placeholder="Tipus via" list="viesList" autocomplete="off">
                <datalist v-if="tipusVies" id="viesList">
                  <option v-for="(via, index) in tipusVies" :key="index" :value="via.nom"></option>
                </datalist>
                <label for="tipusVia">Via<span style="opacity: 0.5; font-size: 15px;"> - opcional</span></label>
              </div>
            </div>
          </div>
          <transition name="fade">
            <component :is="currentLocComponent" :showHelp="showHelp" @update-description="updateLocDescription"></component>
          </transition>
        </div>
      </transition>
      <div id="referencies-textArea-container">
        <div class="form-floating">
            <textarea v-model="altresReferencies" class="form-control" placeholder="Referencies" id="referenciesTextarea"></textarea>
            <label for="referenciesTextarea">Altres referències</label>
        </div>
    </div>
    </form>
</template>
<script >
import CarrerForm from './localitzacions/CarrerForm.vue';
import CarreteraForm from './localitzacions/CarreteraForm.vue';
import PuntSingularForm from './localitzacions/PuntSingularForm.vue';

export default {
  props: {
    localitzacioData: {
      required: true
    },
    showHelp: {
        type: Boolean
    },
  },
  components: {
    CarrerForm,
    CarreteraForm,
    PuntSingularForm
  },
  emits: [
    "get-localitzacio",
    'get-map-serach-string',
    'localitzacio-error'
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
      tipusVia: {
        input: ''
      },
      descripcioLocalitzacio: '',
      detallsLocalitzacio: '',
      altresReferencies: '',
      cartaLocation: {
        comarca: '',
        provincia: '',
        municipi: '',
        tipusLoc: '',
        descripcioLoc: '',
        detallsLoc: '',
        altresRefs: ''
      }
    }
  },
  computed: {
    // Returns provincies simple array rather than a proxy object
    provincies () {
      const provArray = this.localitzacioData.provincies ? Array.from(this.localitzacioData.provincies) : [];
      return provArray;
    },
    // If provincia is set returns comarques that belongs to the province otherwise all comarques
    comarques () {
      const comarquesData = this.localitzacioData.comarques;
      if (this.provincia.isValid) {
        return comarquesData.filter(comarca => comarca.provincies_id === this.provincia.id);
      } else {
        return comarquesData ? [...comarquesData] : []; // ... returns simple array with the same data as this.localitzacioData.comarques, no Proxy object
      }
    },
    /**
     * If comarca is valid returns municipis that belongs comarca
     * If only provincia is set, returns municipis that belongs to the comarques that belongs to the provincia
     * If only the 'provincia' input is set,returns the 'municipis' that belong to the 'comarques' that also belong to the specified 'provincia'
     */
    municipis () {
      const comarcaId = this.comarca.isValid ? this.comarca.id : null;
      const provinciaId = this.provincia.isValid ? this.provincia.id : null;
      const municipis = this.localitzacioData.municipis || [];

      return municipis.filter(municipi => {
        if (comarcaId) {
          return municipi.comarques_id == comarcaId;
        } else if (provinciaId) {
          return this.localitzacioData.comarques.some(comarca => {
            return comarca.provincies_id === provinciaId && comarca.id === municipi.comarques_id;
          });
        } else {
          return true;
        }
      });
    },
    tipusLocalitzacions() {
      const tipusLocArray = this.localitzacioData.tipusLoc ? [...this.localitzacioData.tipusLoc] : [];
      return tipusLocArray;
    },
    tipusVies() {
      const tipusViesArray = this.localitzacioData.tipusVies ? [...this.localitzacioData.tipusVies] : [];
      return tipusViesArray;
    },
    mapSearchString () {
      if (this.isCat) {
        var mapString = []
        if (this.tipusVia.input) {
          mapString = [this.provincia.input, this.municipi.input, this.tipusVia.input, this.descripcioLocalitzacio].filter(Boolean)
        } else {
          mapString = [this.provincia.input, this.municipi.input, this.tipusLoc.input, this.descripcioLocalitzacio].filter(Boolean)
        }
        return mapString.join(' ')
      } else {
        const mapString = ['Espanya, ', this.provincia.input, this.municipi.input].filter(Boolean)
        return mapString.join(' ')
      }
      
    },
    currentLocComponent() {
      const componentMap = {
      'carrers': 'CarrerForm',
      'carretera': 'CarreteraForm',
      'punt singular': 'PuntSingularForm',
      }
      const componentName = componentMap[this.tipusLoc.input.toLowerCase()]
      return componentName || null
    },
  },
  methods: {
        /**
     * Returns array of matching options between localitzacioData and input
     */
    filteredList(list, value) {
      return list.filter(tipus => tipus.nom.toLowerCase().startsWith(value.toLowerCase()))
    },
    removeValidationClasses(el) {
      el.classList.remove('is-valid', 'is-invalid');
    },
    /**
     */
    handleInput(target, list) {
      if (this.isCat) {
        this.handleInCatInput(target, list)
        this.validateInput(target)
      } else {
        this.handleOutOfCatInput()
      }
      this.updateCartaData()
    },
    handleInCatInput (target, list) {
      const inputValue = target.value
      const matchedInputValue = this.filteredList(list, inputValue).find(tipus => tipus.nom.toLowerCase() === inputValue.toLowerCase())
      const handlers = {
        'provincia': this.provinciaSelected,
        'comarca': this.comarcaSelected,
        'municipi': this.municipiSelected,
        'tipusLoc': this.tipusLocSelected,
      };
      if (matchedInputValue) {
        const handler = handlers[target.id];
        if (handler) {
          handler(matchedInputValue);
        }
      } else {
        this[target.id].isValid = false;
      }
    },
    handleOutOfCatInput () {
      this.descripcioLocalitzacio = `${this.provincia.input} ${this.municipi.input}`
    },
    validateInput (el) {
      const validIds = ['provincia', 'comarca', 'municipi', 'tipusLoc']
      if (validIds.includes(el.id)) {
        if (el.id === 'tipusLoc') {
          if (el.value === '') {
            this.descripcioLocalitzacio = ''
          }
        
        !this.tipusLoc.isValid ? this.tipusVia.input = '' : null;
        }
        el.classList.toggle('is-invalid', !this[el.id].isValid)
        el.classList.toggle('is-valid', this[el.id].isValid)
      }
    },
    updateCartaData () {
      this.cartaLocation = {
        provincia: this.provincia.id,
        comarca: this.comarca.id,
        municipi: this.municipi.id,
        tipusLoc: this.tipusLoc.id,
        descripcioLoc: this.descripcioLocalitzacio,
        detallsLoc: this.detallsLocalitzacio,
        altresRefs:this.altresReferencies,
        isValid: this.isCat ? this.tipusLoc.isValid && 
                              this.provincia.isValid && 
                              this.comarca.isValid && 
                              this.municipi.isValid 
                            : this.tipusLoc.isValid,
      };
      this.$emit('get-localitzacio', this.cartaLocation)
      this.emitMapSearchString()
    },
    provinciaSelected (validProv) {
      this.provincia.id = validProv.id
      this.provincia.input = validProv.nom
      this.provincia.isValid = true
      this.validateInput(this.$refs.provinciaInput)

      //Check if comarca is set and belongs to provincia
      if (this.provincia.id !== this.comarca.provincia_id) {
            this.resetComarca()
            this.municipi.isValid ? this.resetMunicipi() : null
      }
    },
    comarcaSelected(validCom) {
      this.comarca.id = validCom.id
      this.comarca.input = validCom.nom
      this.comarca.isValid = true
      this.comarca.provincia_id = validCom.provincies_id
      this.validateInput(this.$refs.comarcaInput)
      // If new comarca doesn't belong to the selected provincia OR provincia is not setted select valid provincia
      if ( this.provincia.input === '' || this.comarca.provincia_id !== this.provincia.id) {
        const provincia = this.provincies.find(provincia => provincia.id === this.comarca.provincia_id)
        provincia ? this.provinciaSelected(provincia) : null
      }
      // Checks if municipi has been set and if it belongs to this comarca
      // If not reset municipi
      if (this.comarca.id !== this.municipi.comarca_id) {
        this.resetMunicipi()
      }
    },
    municipiSelected (validMun) {
      this.municipi.id = validMun.id
      this.municipi.input = validMun.nom
      this.municipi.comarca_id = validMun.comarques_id
      this.municipi.isValid = true
      this.validateInput(this.$refs.municipiInput)

      if (this.comarca.input === '' || this.municipi.comarca_id != this.comarca.id) {
        const comarca = this.comarques.find(comarca => comarca.id === this.municipi.comarca_id)
        if (comarca) {
          this.comarcaSelected(comarca)
        }
      }
    },
    tipusLocSelected (validTipus) {
      this.tipusLoc.id = validTipus.id
      this.tipusLoc.input = validTipus.nom
      this.tipusLoc.isValid = true
    },
    // Handles click, space and enter on row reset button
    clearProvinciaComarcaMunicipi (event) {
      if (event.type === 'click' || event.keyCode === 13 || event.keyCode === 32) {
        this.provincia.input ? this.resetProvincia() : null
        this.comarca.input ? this.resetComarca() : null
        this.municipi.input ? this.resetMunicipi() : null
        this.updateCartaData()
      }
    },
    resetProvincia () {
      this.resetInput('provincia')
    },
    resetComarca() {
      this.resetInput('comarca')
    },
    resetMunicipi () {
      this.resetInput('municipi')
    },
    resetInput(property) {
      this[property].id = ''
      this[property].input = ''
      this[property].comarca_id = ''
      this[property].provincia_id = ''
      this[property].isValid = false
      this.cartaLocation[property] = ''
      this.validateInput(this.$refs[property + 'Input'])
    },
    toggleIsCat (e) {
      this.resetProvincia()
      this.resetComarca()
      this.resetMunicipi()
      this.descripcioLocalitzacio = ''
      this.isCat = this.isCat ? false : true
      if (this.isCat) {
        this.tipusLoc.id = ''
        this.tipusLoc.isValid = false
      } else {
        this.tipusLoc.id = 5 // if !isCat set tipus localitzacio id to 5 = Provincia
        this.tipusLoc.isValid = true
      }
      
    },
    //Sends the map search string
    emitMapSearchString () {
      this.$emit('get-map-serach-string', this.mapSearchString)
    },
    /**
     * Updates the location description given by this child components:
     * - CarrerForm
     * - CarreteraForm
     * - PuntSingularForm
     */
    updateLocDescription (desc) {
      this.descripcioLocalitzacio = desc;
    },
    // Adjusts Municipi and Provincia bootstrap cols when Comarca leaving transition finishes due to isCat unchecked
    adjustColumnSizes() {
        this.$refs.provincia.classList.toggle('col-4');
        this.$refs.provincia.classList.toggle('col-6');
        this.$refs.municipi.classList.toggle('col-4');
        this.$refs.municipi.classList.toggle('col-5'); // col-5 to give space(col-1) to reset button
    },
    showError (error) {
      this.$emit('localitzacio-error', error)
    }
  },
  mounted() {
    this.updateCartaData()
  },
}
</script>

<style scoped>

  #localitzacio-form {
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  #invalid-legend {
    position: absolute;
    right: 0;
    top: -10px;
  }
  #invalid-legend span {
    font-size: 14px;
    opacity: .8;
  }
  #invalid-legend i::before {
    font-size: 16px;
    color: #e21212;
  }
  .reset-row {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .reset-row i::before {
    font-size: 20px;
    transition: transform .12s ease-in-out;
  }
  .reset-row i:hover::before , .reset-row i:focus::before{
    cursor: pointer;
    content: '\F723';
    color: #e21212;
    transform: scale(1.15);
  }

  

  #referencies-textArea-container {
    flex-grow: 1;
  }
  #referencies-textArea-container .form-floating{
    height: 100%;
  }

  #referenciesTextarea {
    height: 100%;
    resize: none;
  }

  #isCat-conatiner {
    min-width: 125px;
  }

  #isCatFocus::before {
    content: '';
    transform: translateX(5px);
  }
  #isCat:focus ~ #isCatFocus::before {
    content: '\F12F';
  }

  /* Vue transitions animations */
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.2s ease-in-out;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }


</style>