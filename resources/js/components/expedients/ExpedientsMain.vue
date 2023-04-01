<template lang="">
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
                        class="col-1 btn btn-dark bg-dark text-white"
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
                        EXPEDIENT #{{showExpedientCodi}}
                        <span id="closeTab" @click="closeTab"><i class="bi bi-x"></i></span>
                    </button>

                </li>
            </ul>
            <div class="tab-content" id="tabContent">
                <div class="tab-pane fade show active" id="all-expedients-container" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <expedients-table :estats="estats" :filtres="filtres" @change-tab="switchTab"></expedients-table>
                </div>
                <div class="tab-pane fade" id="show-expedient-container" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <show-expedient ref="showExpedientComponent"></show-expedient>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import axios from 'axios';
import legendStatus from './LegendEstats.vue';
import expedientsTable from './ExpedientsTable.vue';
import showExpedient from './ShowExpedient.vue';
import * as bootstrap from 'bootstrap';

export default {
  components: {
    legendStatus,
    expedientsTable,
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
    background-color: #cbcbcb;
    margin: 20px auto;
    padding: 14px 2px;
    width: fit-content;
    border-radius: 10px;
}

#legend {
    display: flex;
    justify-content: center;
    align-items: center;
    width: fit-content;
}

.search-box {
    width: 600px;
    margin-top: 16px;
}

.search-box * {
    height: 100% !important;
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
.search-box .form-select {
    border: 1px solid black;
    background-color: #fff;
}

.search-box button {
    border-radius: 0 5px 5px 0;
}

.table-container {
    margin-top: 60px;
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
#tableSelector .active, #tabContent .active {
    background-color: #e6e4e4;
}

.table-container #tabContent {
    height: 450px;
}
#all-expedients-container, #show-expedient-container{
    height: 100%;
}

#all-expedients-tab, #show-expedient-tab {
    position: relative;
    display: flex;
    height: 40px;
    align-items: center;
    justify-content: center;
    padding: 0;
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