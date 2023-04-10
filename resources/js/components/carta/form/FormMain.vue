<template>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="interlocutor-tab" data-bs-toggle="tab" data-bs-target="#interlocutor" type="button" role="tab" aria-controls="Interlocutor" aria-selected="true" ref="interlocutorTab">Interlocutor
                <div class="nav-link-icons">
                    <i v-if="interlocutorValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
                    <i v-if="saveInterlocutor && newInterlocutor" class="bi bi-person-plus ps-1 ps-1"></i>
                    <i v-else-if="saveInterlocutor && !newInterlocutor" class="bi bi-person-gear ps-1"></i>
                </div>
            </button>
        </li>
        <li class="nav-item" role="presentation" >
            <button class="nav-link" id="localitzacio-tab" data-bs-toggle="tab" data-bs-target="#localitzacio" type="button" role="tab" aria-controls="Localitzacio" aria-selected="false" ref="localitzacioTab">Localització
                <div class="nav-link-icons">
                    <i v-if="localitzacioValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
                </div>
            </button>
            
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident" type="button" role="tab" aria-controls="Incident" aria-selected="false" ref="incidentTab">Incident
                <div class="nav-link-icons">
                    <i v-if="incidentValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
                </div>
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="interlocutor" role="tabpanel" aria-labelledby="interlocutor-tab" ref="interlocutorPanel">
            <interlocutor-form 
                @get-interlocutor="emitInterlocutor"
                @is-save-interlocutor="updateSaveInterlocutor" 
                @is-form-valid="isInterlocutorValid"
            />
        </div>
        <div class="tab-pane" id="localitzacio" role="tabpanel" aria-labelledby="localitzacio-tab" ref="localitzacioPanel">
            <localitzacio-form 
                :localitzacioData="localitzacioData" 
                @get-location="emitLocation" 
                @get-map-serach-string="updateMapSearchString"
                @is-form-valid="isLocationValid"
            />
            <!-- @is-form-valid="updateLocationValid" -->
        </div>
        <div class="tab-pane" id="incident" role="tabpanel" aria-labelledby="incident-tab" ref="incidentPanel">
            <incident-form 
                :incidentData="incidentData"
                @get-incident="emitIncident"
                @is-form-valid="isIncidentValid"
                />
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import InterlocutorForm from './FormMainInterlocutor.vue';
import LocalitzacioForm from './FormMainLocalitzacio.vue';
import IncidentForm from './FormMainIncident.vue';
import * as bootstrap from 'bootstrap';

export default {
  props: {
  },
  components: {
    InterlocutorForm,
    LocalitzacioForm,
    IncidentForm
  },
  emits: [
    'get-carta-interlocutor',
    'get-carta-location',
    'get-carta-incident',
    'get-map-search-string',
    'is-new-interlocutor',
    'is-save-interlocutor'
  ],
  data() {
    return {
        interlocutorValid: false,
        localitzacioValid: false,
        incidentValid: false,
        newInterlocutor: true,
        saveInterlocutor: false,
        interlocutor: {},
        localitzacio: {},
        incident: {},
        mapSearchString: '',
        localitzacioData: {},
        incidentData: {},
        interlocutorTab: Object,
        localitzacioTab: Object,
        incidentTab: Object
    }
  },
  computed: {

  },
  mounted() {
    const self = this;
    axios
        .get('cartaData')
        .then(response => {
            self.localitzacioData = response.data.localitzacio
            self.incidentData = response.data.incident
        })
        .catch((error) => {})

    /**
     * Observer sends the location string to the map component to search it
     * This is occurs when location tab is no longer active, aka doesn't have class 'active' no more. Hit the road jack
     */ 
    const observer = new MutationObserver(mutation => {
        if (!mutation[0].target.classList.contains('active') && this.mapSearchString != '') {
            this.$emit('get-map-search-string', this.mapSearchString)
            console.log("\n\nFORM MAIN: emiting map search string")
        }
    });
    observer.observe(this.$refs.localitzacioTab, { attributeFilter: ['class'] });

    this.$refs.interlocutorPanel.addEventListener('keydown', this.handleTabKey);
    this.$refs.localitzacioPanel.addEventListener('keydown', this.handleTabKey);
    this.$refs.incidentPanel.addEventListener('keydown', this.handleTabKey);

    this.interlocutorTab = new bootstrap.Tab(this.$refs.interlocutorTab);
    this.localitzacioTab = new bootstrap.Tab(this.$refs.localitzacioTab);
    this.incidentTab = new bootstrap.Tab(this.$refs.incidentTab);
  },
  
  methods: {
    /**
     * Checks for tab key pressed
     * inputElements: gets all inputs and textareas of the tabPanel that are not disabled (not focusable)
     * lastInputElement: gets last input or textarea of the tabPanel
     * If the event target is last child of tab panel then redirects to next Tab
     */
    handleTabKey(event) {
    const isTabKey = event.keyCode === 9; //keycode of tab
    const inputElements = event.currentTarget.querySelectorAll('input:not([disabled]), textarea:not([disabled])'); //node list array
    const lastInputElement = inputElements[inputElements.length - 1];
    const isLastInput = event.target === lastInputElement;

    if (isTabKey && isLastInput) {
      const tabIndex = this.getTabIndex(event.currentTarget);
      if (tabIndex < 2) {
        const nextTab = this.getTabObject(tabIndex + 1);
        console.log(tabIndex)
        nextTab.show();
      }
    }
  },
  
  getTabIndex(tabPanel) {
    const tabPanes = [this.$refs.interlocutorPanel, this.$refs.localitzacioPanel, this.$refs.incidentPanel];
    return tabPanes.indexOf(tabPanel);
  },
  getTabObject(index) {
    // Return the corresponding tab object based on the index
    switch (index) {
      case 0:
        return this.interlocutorTab;
      case 1:
        return this.localitzacioTab;
      case 2:
        return this.incidentTab;
    }
  },

    /**
     * UPDATES
     * ----------------------------
     */
    updateMapSearchString (mapString) {
        this.mapSearchString = mapString
    },

    /**
     * VALIDATIONS
     * ---------------------------------------------------
     * Sets forms validation, this affects to the nav-item tabs icons
     */
     isInterlocutorValid (isValid) {
        this.interlocutorValid = isValid
    },
    isLocationValid (isValid) {
        this.localitzacioValid = isValid
    },
    isIncidentValid (isValid) {
        this.incidentValid = isValid;
    },
    updateSaveInterlocutor (saveIt) {
        this.saveInterlocutor = saveIt
        this.$emit('is-save-interlocutor', this.saveInterlocutor)
    },

    /** 
     * BRIDGE EMITS TO -> CARTA TRUCADA big daddy
     * -----------------------------------------------------
     * Emits localitzacio, interlocutor and incident object
     * when is valid emits the valid object, when it's false empty object
     */
    emitLocation(loc) {
        this.$emit('get-carta-location', loc)
    },
    emitInterlocutor (interlocutor) {
        this.$emit('get-carta-interlocutor', interlocutor)
    },
    emitIncident (incident) {
        this.$emit('get-carta-incident', incident)
    },
}
}
</script>
<style scoped>
    .nav-tabs {
        align-items: end;
        background-color: #DFE2E6;
        border-bottom: 2px solid var(--primary);
        border-radius: 5px 5px 0 0;
    }
    .nav-tabs:first-child {
        padding-left: 20px;
    }
    .nav-item {
        padding-top: 20px;
        height: 100%;
        width: 200px;
        min-width: 180px;
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .nav-item button {
        width: 100%;
        
    }
    .nav-link {
        display: inline-flex;
        position: relative;
        text-align: start;
        padding-left: 10%;
        border: none;
        color: var(--success);
    }
    .nav-link-icons {
        display: inline-flex;
        padding-left: 10px;
    }
   .nav-link.active {
        border: 2px solid var(--primary);
        border-bottom: none;
        color: black;
        height: 105%;
    }
    .tab-content {
        flex-grow: 1;
    }
    .tab-pane {
        height: 100%;
        padding: 20px 40px 0 40px;
    }
    .bi-clipboard2-x {
        color: #e21212;
    }
    .bi-clipboard2-check {
        color: #198754;
    }

    @media (max-width: 1145px) {
        .nav-item {
            width: 180px;
            padding: 10px 0;
        }
        .nav-link.active {
            border-radius: 10px;
            border-bottom: 2px solid var(--primary);;
            padding: 8px !important;
        }

    }
    @media (max-width: 1030px) {
        .nav-item {
            height: 50%;
        }
    }
    @media (max-width: 715px) {         
        .nav-item {
            width: fit-content;
            height: 34%;
        }
    }
</style>