<template>
    <div class="container">
        <div class="header">
            <div id="legend">
                <legendStatus  :estats="estats"></legendStatus>
            </div>
            <div class="search-box">
                <div class="row p-0 g-0">
                    <div class="col-4 p-0">
                        <select name="filtro"
                            class="form-select"
                            aria-label="Filtro">
                            <option selected value="">Tots</option>
                            <option v-for="(filtre, index) in filtres" :key="index"
                                :value="filtre.col">
                                {{ filtre.label }}
                            </option>
                        </select>
                    </div>
                    <input
                        type="text"
                        class="col-7 form-input">
                    <button
                        type="submit"
                        class="col-1 btn btn-light text-dark"
                        @click="submit">
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
                    <expedients-table :estats="estats" :filtres="filtres" @change-tab="switchTab"></expedients-table>
                </div>
                <div class="tab-pane" id="show-expedient-container" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <show-expedient ref="showExpedientComponent"></show-expedient>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import axios from 'axios';
import legendStatus from './LegendEstats.vue';
import ExpedientsTable from './ExpedientsTable.vue';
import showExpedient from './ShowExpedient.vue';
import * as bootstrap from 'bootstrap';

export default {
  components: {
    legendStatus,
    ExpedientsTable,
    showExpedient
  },
  data () {
    return {
      expedients: [],
      estats: [],
      filtres: [
        {
          label: 'Codi',
          col: 'codi'
        },
        {
          label: 'Localització',
          col: ''
        },
        {
          label: 'Incidents',
          col: ''
        },
        {
          label: 'Cartes',
          col: 'cartes_count'
        },
        {
          label: 'Modificació',
          col: 'updated_at'
        },
        {
          label: 'Creació',
          col: 'created_at'
        },
        {
          label: 'Estat',
          col: 'estat_expedients_id'
        }

      ],
      selectedEstat: '',
      message: '',
      allExpedientsTab: null,
      expedientTab: null,
      showExpedientCodi: ''
    }
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
    selectEstats () {
      const self = this;
      axios
        .get('estatExpedient')
        .then(response => {
          self.estats = response.data;
          console.log(response);
        })
        .catch((error) => { });
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
.header {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    padding: 40px 0;
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

.table-container .nav-item {
    width: 50%
}
.table-container .nav-item button{
    height: 100%;
    width: 100%;
    border: none
}
#tableSelector {
    border-bottom: none;
}

.table-container #tabContent {
    height: 450px;
}

#all-expedients-container {
    padding-top: 16px;
    padding-right: 30px;
}

#all-expedients-tab, #all-expedients-container {
    background-color: #e6e4e4;
}

#show-expedient-tab, #show-expedient-container {
    background-color: #f3f3f3
}


#all-expedients-container, #show-expedient-container{
    height: 100%;
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
</style>