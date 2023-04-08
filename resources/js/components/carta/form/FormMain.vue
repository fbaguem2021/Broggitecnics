<template>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="interlocutor-tab" data-bs-toggle="tab" data-bs-target="#interlocutor" type="button" role="tab" aria-controls="Interlocutor" aria-selected="true">Interlocutor
                <div class="nav-link-icons">
                    <i v-if="saveInterlocutor && newInterlocutor" class="bi bi-person-plus ps-1 pe-1"></i>
                    <i v-else-if="saveInterlocutor && !newInterlocutor" class="bi bi-person-gear pe-1"></i>
                    <i v-if="interlocutorValid" class="bi bi-clipboard2-check"></i>
                    <i v-else class="bi bi-clipboard2-x"></i>
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
                @save-interlocutor="updateSaveInterlocutor" 
                @carta-interlocutor="updateInterlocutor"
                @is-form-valid="updateInterlocutorValid"
            />
        </div>
        <div class="tab-pane" id="localitzacio" role="tabpanel" aria-labelledby="localitzacio-tab">
            <localitzacio-form 
                :localitzacioData="localitzacioData" 
                @get-location="updateLocation" 
                @get-map-serach-string="updateMapString"
                
            />
            <!-- @is-form-valid="updateLocationValid" -->
        </div>
        <div class="tab-pane" id="incident" role="tabpanel" aria-labelledby="incident-tab">
            <incident-form 
                :incidentData="incidentData" 
                @is-form-valid="updateIncidentValid"
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
    'get-carta-location',
    'get-map-search-string',
    'get-carta-interlocutor',
    'is-new-interlocutor',
    'get-save-interlocutor'
  ],
  data() {
    return {
        interlocutorValid: false,
        localitzacioValid: false,
        incidentValid: false,
        saveInterlocutor: false,
        newInterlocutor: true,
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

    // LOCATION
    updateLocation(loc) {
        this.$emit('get-carta-location', loc)
    },
    updateMapString (string) {
        this.mapSearchString = string;
    },
    emitMapSearchString () {
        this.$emit('get-map-search-string', this.mapSearchString)
    },

    // INTERLOCUTOR
    updateInterlocutor (interlocutor) {
        this.$emit('get-carta-interlocutor', interlocutor)
    },
    updateSaveInterlocutor (saveIt) {
        console.log("\n\nFORM MAIN: save interlocutor emit signal recived \nValue recived:")
        console.log(saveIt)
        console.log("Component saveInterlocutor BEFORE:")
        console.log(this.saveInterlocutor)
        this.saveInterlocutor = saveIt
        console.log("Component saveInterlocutor AFTER:")
        console.log(this.saveInterlocutor)
        // this.$emit('get-save-interlocutor', this.saveInterlocutor)
    },
    updateInterlocutorValid (isValid) {
        this.interlocutorValid = isValid
    },

    // INCIDENT
    updateIncidentValid (isValid) {
        this.incidentValid = isValid;
    }

}
}
</script>
<style scoped>
    .nav-tabs {
        background-color: #DFE2E6;
        border-bottom: 2px solid var(--primary);
        border-radius: 5px 5px 0 0;
    }
    .nav-tabs:first-child {
        padding-left: 20px;
    }
    .nav-item {
        padding-top: 20px;
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
        position: relative;
        text-align: start;
        padding-left: 10%;
        border: none;
        color: var(--success);
    }
    .nav-link-icons {
        position: absolute;
        top: 50%;
        right: 5%;
        transform: translateY(-50%);
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
        }

    }
    @media (max-width: 775px) {
        .nav-link.active {
            border-radius: 10px;
            border-bottom: 2px solid var(--primary);;
            padding: 8px !important;
        }
    }
</style>