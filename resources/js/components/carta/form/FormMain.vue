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
        <div id="showHelpSwitch-container">
            <div class="form-check form-switch form-check-reverse m-0">
                <label class="form-check-label" for="showHelpSwitch"><i class="bi bi-badge-cc"></i></label>
                <input v-model="showHelp" id="showHelpSwitch" class="form-check-input" type="checkbox" role="switch">
            </div>
        </div>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" @keydown="handleTabKey($event)" id="interlocutor" role="tabpanel" aria-labelledby="interlocutor-tab" ref="interlocutorPanel">
            <interlocutor-form
                :showHelp=showHelp
                @get-interlocutor="emitInterlocutor"
                @interlocutor-error="showError"
                @interlocutor-is-loaded="formMainIsLoaded"
            />
        </div>
        <div class="tab-pane" @keydown="handleTabKey($event)" id="localitzacio" role="tabpanel" aria-labelledby="localitzacio-tab" ref="localitzacioPanel">
            <localitzacio-form
                :localitzacioData="localitzacioData"
                :showHelp=showHelp
                @get-localitzacio="emitLocation"
                @get-map-serach-string="updateMapSearchString"
                @localitzacio-error="showError"
                />
            <!-- @is-form-valid="updateLocationValid" -->
        </div>
        <div class="tab-pane" @keydown="handleTabKey($event)" id="incident" role="tabpanel" aria-labelledby="incident-tab" ref="incidentPanel">
            <incident-form
                :incidentData="incidentData"
                :showHelp=showHelp
                @get-incident="emitIncident"
                @get-tipus-incident="emitTipusIncident"
                @incident-error="showError"
                />
        </div>
    </div>
</template>
<script>
import InterlocutorForm from './FormMainInterlocutor.vue';
import LocalitzacioForm from './FormMainLocalitzacio.vue';
import IncidentForm from './FormMainIncident.vue';
import * as bootstrap from 'bootstrap';

export default {
  props: {
    localitzacioData: {
        type: Object,
        required: true
    },
    incidentData: {
        type: Object,
        required: true
    }
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
    'get-carta-tipus-incident',
    'get-map-search-string',
    'form-main-is-loaded',
    'form-main-error'
  ],
  data() {
    return {
        interlocutorIsLoaded: true,
        interlocutorValid: false,
        localitzacioValid: false,
        incidentValid: false,
        newInterlocutor: true,
        saveInterlocutor: false,
        interlocutor: {},
        localitzacio: {},
        incident: {},
        mapSearchString: '',
        showHelp: false,
        interlocutorTab: Object,
        localitzacioTab: Object,
        incidentTab: Object
    }
  },
  computed: {

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
     * BRIDGE EMITS TO -> CARTA TRUCADA big daddy
     * -----------------------------------------------------
     * Emits localitzacio, interlocutor and incident object
     * when is valid emits the valid object, when it's false empty object
     */
    emitInterlocutor (interlocutor) {
        this.interlocutor = interlocutor
        this.newInterlocutor = interlocutor.isNewInerlocutor
        this.saveInterlocutor = interlocutor.saveInterlocutor
        this.interlocutorValid = interlocutor.isValid
        this.$emit('get-carta-interlocutor', interlocutor)
    },
    emitLocation(loc) {
        this.localitzacio = loc
        this.localitzacioValid = loc.isValid
        this.$emit('get-carta-location', loc)
    },
    emitIncident (incident) {
        this.incident = incident
        this.incidentValid = incident.isValid
        this.$emit('get-carta-incident', incident)
    },
    emitTipusIncident(tipusIncident) {
        this.$emit('get-carta-tipus-incident', tipusIncident)
    },
    formMainIsLoaded (interlocutorIsLoaded) {
        this.interlocutorIsLoaded = interlocutorIsLoaded;
        this.$emit('form-main-is-loaded', this.interlocutorIsLoaded)
    },
    showError (error) {
        this.$emit('formMain-error', error)
    }
  },
  mounted() {
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

    // Init bootstrap Tab objects
    this.interlocutorTab = new bootstrap.Tab(this.$refs.interlocutorTab);
    this.localitzacioTab = new bootstrap.Tab(this.$refs.localitzacioTab);
    this.incidentTab = new bootstrap.Tab(this.$refs.incidentTab);

    /* Inits all tooltips */
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  }
}
</script>
<style scoped>
    .nav-tabs {
        align-items: end;
        background-color: #DFE2E6;
        border-bottom: 4px solid var(--primary);
        border-radius: 5px 5px 0 0;
    }
    #myTab {
        position: relative;
    }
    #showHelpSwitch-container{
        position: absolute;
        right: 3%;
        top: 60%;
        transform: translateY(-50%);
        padding-left: 20px;
    }
    #showHelpSwitch-container label {
        transform: scale(1.4);
        padding-right: 10px;
    }
    #showHelpSwitch {
        position: relative;
        background-image: none;
        width: 140%;
        transform: scale(1.4) translateX(10%);
    }

    #showHelpSwitch::before{
        position: absolute;
        font-size: 12px;
        top: 1px;
        left: 1px;
        color: rgb(81, 81, 81);
        display: inline-block;
        font-family: bootstrap-icons !important;
        content: '\F3EE';
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        transition: transform .2s ease-in-out;
    }
    #showHelpSwitch:checked::before{
        transform: translateX(160%);
        color: white
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
        border: 4px solid var(--primary);
        border-bottom: none;
        color: black;
        height: 109%;
    }
    .tab-content {
        position: relative;
        flex-grow: 1;
    }
    .tab-pane {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        padding: 20px 40px 20px 40px;
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
    @media (max-width: 1029px) {
        .nav-item {
            height: 50%;
        }
    }
    @media (max-width: 715px) {
        .nav-item {
            width: fit-content;
        }
    }
</style>