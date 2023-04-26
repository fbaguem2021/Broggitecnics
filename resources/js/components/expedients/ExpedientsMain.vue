<template>
    <message-app ref="messageApp"></message-app>
    <div class="container">
        <div class="header">
            <div id="legend">
                <legendStatus 
                    :estats="estats" 
                    @selectByEstat="selectByEstat"
                    @legend-error="showError"
                    ></legendStatus>
            </div>
            <div class="search-box">
                <div class="input-group p-0 g-0">
                    <div class="col-4 p-0">
                        <select v-model="filterBySelected.col" name="filtro"
                            class="form-select ps-3"
                            aria-label="Filtro">
                            <option value="all" >Tots</option>
                            <option v-for="filtre, index in filtres" :key="index"
                                v-show=" filtre.id <= 4 "
                                :value="filtre.col">
                                {{ filtre.label }}
                            </option>
                        </select>
                    </div>
             
                    <span v-if="filterBySelected.col === 'codi'" class="input-group-text">EXP-</span>
                    <input
                        v-model="filterBySelected.input"
                        @keydown.enter= "searchBarSubmit"
                        type="text"
                        class="form-control col-4">
                    
                    <button
                        type="submit"
                        class="col-1 btn btn-light text-dark"
                        @click="searchBarSubmit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="table-container">
            <ul class="nav nav-tabs" id="tableSelector" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-expedients-tab" ref="allExpedientsTab" data-bs-toggle="tab" data-bs-target="#all-expedients-container" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">EXPEDIENTS</button>
                </li>
                <li v-show="showExpedientCodi" class="nav-item" role="presentation">
                    <button class="nav-link" id="show-expedient-tab" ref="expedientTab" data-bs-toggle="tab" data-bs-target="#show-expedient-container" type="button" role="tab" aria-selected="false" tabindex="-1">
                        #{{showExpedientCodi}}
                        <span id="closeTab" @click="closeTab"><i class="bi bi-x"></i></span>
                    </button>

                </li>
            </ul>
            <div class="tab-content" id="tabContent">
                <div class="tab-pane show active" id="all-expedients-container" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <expedients-table 
                        ref="expedientsTable" 
                        :estats="estats" 
                        :estatsIsLoaded="estatsIsLoaded"
                        :filtres="filtres" 
                        @change-tab="switchTab" 
                        @refresh-legend="selectEstats"
                        @is-expedients-selected="isExpedientsSelected"
                        @table-error="showError"
                        @table-message="showMessage"
                        ></expedients-table>
                </div>
                <div class="tab-pane" id="show-expedient-container" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <show-expedient 
                        ref="showExpedientComponent"
                        @show-expedient-error="showError"
                    ></show-expedient>
                </div>
            </div>
            <div style="padding: 0 8px;">
            
            <table id="selection-controll" class="table">
                <tbody>
                    <tr>
                            <td width="85%">
                                <a @click="selectAll">Seleccionar tots</a>
                                <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a @click="deselectAll">Desseleccionar tots</a>
                            </td>
                            <td width="10%">
                                <select 
                                    v-model="selectedEstatUpdate" 
                                    :class="'estat-'+selectedEstatUpdate"
                                    :disabled="!showUpdateController">
                                    <option v-for="(estat, index) in estats" :key="index"
                                        style="background-color: white;"
                                        :value="estat.id"
                                        :selected="estat.id == 1">
                                        {{estat.estat}}
                                    </option>
                                </select>
                            </td>
                            <td width="5%">
                                <span @click="updateSelectedExp">
                                    <i class="bi bi-cloud-upload"
                                      :class="!showUpdateController ? 'disabled' : '' "></i>
                                </span>
                            </td>

                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>

</template>
<script>
import axios from 'axios';
import legendStatus from './LegendEstats.vue';
import ExpedientsTable from './expedientsTable.vue';
import showExpedient from './showExpedient.vue';
import messageApp from '../MessageApp.vue';
import * as bootstrap from 'bootstrap';

export default {
  components: {
    legendStatus,
    ExpedientsTable,
    showExpedient,
    messageApp
  },
  watch: {
    'filterBySelected.col'(newVal, oldVal) {
      if (newVal === 'all') {
        this.searchBarSubmit();
        this.filterBySelected.input = ''
      }
    }
  },
  data () {
    return {
      expedients: [],
      estats: [],
      selectedEstatUpdate: 1,
      showUpdateController: false,
      estatsIsLoaded: false,
      filtres: [
        {
            id: 1,
            label: 'Codi',
            col: 'codi'
        },
        {
          id: 2,
          label: 'Localització',
          col: 'loc'
        },
        {
            id: 3,
          label: 'Incidents',
          col: 'inc'
        },
        {
            id: 4,
          label: 'Cartes',
          col: 'cartes_count'
        },
        {
            id: 5,
          label: 'Modificació',
          col: 'updated_at'
        },
        {
            id: 6,
          label: 'Creació',
          col: 'created_at'
        },
        {
            id: 7,
          label: 'Estat',
          col: 'estat_expedients_id'
        }
      ],
      selectedEstat: '',
      message: '',
      filterBySelected: {
        col: 'all',
        input: ''
      },
      allExpedientsTab: null,
      expedientTab: null,
      showExpedientCodi: ''
    }
  },
  computed: {

  }, 
  methods: {
    switchTab (expID, expCodi) {
      this.showExpedientID = expID;
      this.showExpedientCodi = expCodi;
      this.$refs.showExpedientComponent.getExpedient(expID);
      this.expedientTab.show();
    },
    closeTab () {
      this.showExpedientCodi = '';
      this.allExpedientsTab.show();
    },
    selectAll() {
        this.$refs.expedientsTable.selectAll();
    },
    deselectAll() {
        this.$refs.expedientsTable.deselectAll();
    },
    isExpedientsSelected(isSelected) {
        this.showUpdateController = isSelected
        console.log("at last one selected", isSelected)
    },
    updateSelectedExp(){
        this.filterBySelected.col = "all"
        this.$refs.expedientsTable.updateSelect(this.$refs.expedientsTable.selectedIds, this.selectedEstatUpdate);
    },
    selectEstats () {
      const self = this;
      axios
        .get('estatExpedient')
        .then(response => {
          self.estats = response.data;
          self.estatsIsLoaded = true;
          console.log(response)
        })
        .catch((error) => { 
            this.showError(error)
        });
    },
    selectByEstat(estatID){
        this.allExpedientsTab.show()
        this.filterBySelected.col = "estat_expedients_id"
        this.$refs.expedientsTable.selectExpedientsBy('estat_expedients_id', estatID)
    },
    searchBarSubmit() {
        this.allExpedientsTab.show()
        const col = this.filterBySelected.col
        let value = this.filterBySelected.input
        if(col === 'codi') { value = 'EXP-'+value}
        console.log("Searching in", `'${col}'`, "value:", value)
        this.$refs.expedientsTable.selectExpedientsBy(col, value)
    },
    showError(error) {
        this.$refs.messageApp.createErrorAlert(error)
    },
    showMessage(message, type, data = null) {
        this.$refs.messageApp.createMessageAlert(message, type, data)
    }
  },
  mounted () {
    this.selectEstats();
    this.allExpedientsTab = new bootstrap.Tab(this.$refs.allExpedientsTab);
    this.expedientTab = new bootstrap.Tab(this.$refs.expedientTab);
  }
}
</script>
<style scoped>
.container {
    height: 100%;
}
.header {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    padding: 6vh 0;
    border-radius: 10px;
}

#legend {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.search-box {
    width: 600px;
    margin-top: 40px;
}

.search-box * {
    min-height: 36px;
}

.search-box .form-select {
    height: 100%;
    border-radius: 5px 0 0 5px;
}

.search-box input {
    padding-left: 18px;
}

.search-box .form-input,
.search-box .form-select, .search-box button {
    border: 1px solid rgb(0, 0, 0, 0.08);
    background-color: #fff;
}

.search-box button {

    border-radius: 0 5px 5px 0;
}
.table-container {
    position: relative;
    height: 50%;
}
.table-container .nav-item {
    width: 50%
}
.table-container .nav-item button{
    height: 100%;
    width: 100%;
    border: none
}
#tableSelector {
    height: 50px;
    border-bottom: none;
}

#tabContent {
    height: calc(100% - 50px);
}

#all-expedients-tab, #all-expedients-container {
    background-color: #e6e4e4;
}

#show-expedient-tab, #show-expedient-container {
    background-color: #f3f3f3
}


#all-expedients-container, #show-expedient-container{
    position: relative;
    padding: 0 8px;
    height: 100%;
    overflow-y: scroll;
    overflow-x: hidden;
}

#all-expedients-tab, #show-expedient-tab {
    position: relative;
    display: flex;
    height: 50px;
    align-items: start;
    padding-left: 42px;
    font-size: 1.3rem;
    font-weight: bold;
    color: black;
    justify-content: start;
    border-bottom: 2px solid rgba(0, 0, 0, .05);
}
#closeTab i::before {
    font-size: 30px;
    font-weight: bold;
}

#closeTab {
    position: absolute;
    right: 4%;
    transform: scale(1);
}
#closeTab:hover {
    transform: scale(1.1);
}

#selection-controll {
    width: 100%;
    height: 60px;
    margin-top: 20px;

}

#selection-controll tbody tr td {
    vertical-align: middle;
    border: none;
}
#selection-controll tbody tr > td:first-child {
    text-align: end;
}
#selection-controll tbody tr > td:first-child a{
    text-decoration: underline; 
    color: var(--success); 
    cursor: pointer;
}
#selection-controll tbody tr > td:first-child a:hover{
    color: hsl(240, 90%, 40%);; 
}
#selection-controll tbody tr > td:not(:first-child) {
    text-align: center;
}
#selection-controll tbody tr > td:last-child {
    font-size: 25px;
    transform: scale(1);
    transform-origin: center;
    transition: transform .1s linear;
}
#selection-controll tbody tr > td:last-child:hover {
    cursor: pointer;
    transform: scale(1.02);
}
#selection-controll .buttons-container {
    margin-right: 40px;
}
select {
    border-radius: 4px;
    padding: 2px 0 2px 2px;
}
select:disabled{
    background-color: #e9ecef;
}
.bi-cloud-upload {
    color: black;

}
.bi-cloud-upload::before{
    transform: translateY(3px);
}
.bi-cloud-upload.disabled {
    color: rgba(0, 0, 0, .5)
}
</style>
<style>
    .estat-1 {
        background-color: #24DB4A;
    }

    /* SOLICITAT */
    .estat-2 {
        background-color: #E9E241;
    }

    /* ACCEPTAT */
    .estat-3 {
        background-color: #889D35;
    }

    /* TANCAT */
    .estat-4 {
        background-color: #47B2FF;
    }

    /* IMMOBILITZAT */
    .estat-5 {
        background-color: #8924D9;
        color: white;
    }
    .estat-5 option{
        color: black;
    }
</style>