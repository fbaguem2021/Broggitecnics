<template>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="interlocutor-tab" data-bs-toggle="tab" data-bs-target="#interlocutor" type="button" role="tab" aria-controls="Interlocutor" aria-selected="true">Interlocutor
                <div class="nav-link-icons">
                    <i v-if="interlocutorValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
                    <i v-if="saveInterlocutor && newInterlocutor" class="bi bi-person-plus ps-1 ps-1"></i>
                    <i v-else-if="saveInterlocutor && !newInterlocutor" class="bi bi-person-gear ps-1"></i>
                </div>
            </button>
        </li>
        <li class="nav-item" role="presentation" >
            <button class="nav-link" id="localitzacio-tab" data-bs-toggle="tab" data-bs-target="#localitzacio" type="button" role="tab" aria-controls="Localitzacio" aria-selected="false" ref="localitzacioTabButton">Localització
                <div class="nav-link-icons">
                    <i v-if="localitzacioValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
                </div>
            </button>
            
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident" type="button" role="tab" aria-controls="Incident" aria-selected="false">Incident
                <div class="nav-link-icons">
                    <i v-if="incidentValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
                </div>
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="interlocutor" role="tabpanel" aria-labelledby="interlocutor-tab">
            <interlocutor-form 
                @get-interlocutor="emitInterlocutor"
                @is-save-interlocutor="updateSaveInterlocutor" 
                @is-form-valid="isInterlocutorValid"
            />
        </div>
        <div class="tab-pane" id="localitzacio" role="tabpanel" aria-labelledby="localitzacio-tab">
            <localitzacio-form 
                :localitzacioData="localitzacioData" 
                @get-location="emitLocation" 
                @get-map-serach-string="updateMapSearchString"
                @is-form-valid="isLocationValid"
            />
            <!-- @is-form-valid="updateLocationValid" -->
        </div>
        <div class="tab-pane" id="incident" role="tabpanel" aria-labelledby="incident-tab">
            <incident-form 
                :incidentData="incidentData"
                @get-incident="emitIncident"
                @is-form-valid="isIncidentValid"
                />
        </div>
    </div>
</template>
<script>
import InterlocutorForm from './FormMainInterlocutor.vue';
import LocalitzacioForm from './FormMainLocalitzacio.vue';
import IncidentForm from './FormMainIncident.vue';
import axios from 'axios';

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
        incidentData: {}
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
    const button = this.$refs.localitzacioTabButton;
    const observer = new MutationObserver(mutation => {
        const tab = mutation[0].target
        if (!tab.classList.contains('active') && this.mapSearchString != '') {
            this.$emit('get-map-search-string', this.mapSearchString)
            console.log("\n\nFORM MAIN: emiting map search string")
        }
    });
    observer.observe(button, { attributeFilter: ['class'] });
  },
  methods: {
    /**
     * UPDATES
     */
    updateMapSearchString (mapString) {
        this.mapSearchString = mapString
    },

    /**
     * VALIDATIONS
     * 
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
     * 
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