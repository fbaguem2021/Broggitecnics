<template>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="interlocutor-tab" data-bs-toggle="tab" data-bs-target="#interlocutor" type="button" role="tab" aria-controls="Interlocutor" aria-selected="true">Interlocutor</button>
        </li>
        <li class="nav-item" role="presentation" >
            <button class="nav-link" id="localitzacio-tab" data-bs-toggle="tab" data-bs-target="#localitzacio" type="button" role="tab" aria-controls="Localitzacio" aria-selected="false" ref="localitzacioTabButton">Localització</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident" type="button" role="tab" aria-controls="Incident" aria-selected="false">Incident</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="interlocutor" role="tabpanel" aria-labelledby="interlocutor-tab">
            <interlocutor-form/>
        </div>
        <div class="tab-pane" id="localitzacio" role="tabpanel" aria-labelledby="localitzacio-tab">
            <localitzacio-form :localitzacioData="localitzacioData" @get-location="updateLocation" @get-map-serach-string="updateMapString"/>
        </div>
        <div class="tab-pane" id="incident" role="tabpanel" aria-labelledby="incident-tab">
            <incident-form :incidentData="incidentData" />
        </div>

    </div>
</template>
<script>
import InterlocutorForm from './FormMainInterlocutor.vue';
import LocalitzacioForm from './FormMainLocalitzacio.vue';
import IncidentForm from './FormMainIncident.vue';
import axios from 'axios';

export default {
  components: {
    InterlocutorForm,
    LocalitzacioForm,
    IncidentForm
  },
  emits: [
    'get-carta-location',
    'get-map-search-string'
  ],
  data() {
    return {
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

        const button = document.getElementById('localitzacio-tab');
        const observer = new MutationObserver(mutation => {
            const tab = mutation[0].target
            if (!tab.classList.contains('active') && this.mapSearchString != '') {
                this.$emit('get-map-search-string', this.mapSearchString)
            }
        });
        observer.observe(button, { attributeFilter: ['class'] });
    
    
  },
  methods: {
    updateLocation(loc) {
        this.$emit('get-carta-location', loc)
    },
    updateMapString (string) {
        this.mapSearchString = string;
    },
    emitMapSearchString () {
        this.$emit('get-map-search-string', this.mapSearchString)
    },
    myfunction(){
        console.log("NOW!")
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
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .nav-item button {
        width: 100%;
    }
    .nav-link {
        border: none;
        color: var(--success);
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

    @media (max-width: 1145px) {
        .nav-item {
            width: 130px;
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