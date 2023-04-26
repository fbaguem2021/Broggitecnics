<template lang="">
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th></th>
                <th v-for="(filtre, index) in filtres" :key="index" 
                    :style = "{ 'text-align': filtre.label == 'Codi' ? 'end' : '' }"
                    :width = " filtre.id === 2 ? '20%' : filtre.id === 3 ? '25%' : '10%' ">
                  <div class="theader-item-container" 
                    :class = " orderByColumn === filtre.col ? 'active' : '' "
                    v-on =" filtre.label != 'Incidents' && filtre.label != 'Localització' ? { click: () => orderBy(filtre.col) } : {}">
                    {{filtre.label}}
                    <span
                      v-if = " filtre.label != 'Incidents' && filtre.label != 'Localització' "
                      :class=" orderByColumn === filtre.col && orderDir === 'desc' ? 'triangle' : 'triangle rotate' ">
                    </span>
                  </div>
                </th>
                <th width='5%'></th>
            </tr>
        </thead>
        <tbody v-if="isLoaded" ref="tableBody">
            <tr v-for="(exp, index) in expedients" :key="index" :data-expid="exp.id" @click="handleRowClick(exp.id, $event)">
                <td><input class="form-check-input" type="checkbox" v-model="exp.isChecked" @click.stop></td>
                <td>{{exp.codi_expedient}}</td>
                <td>
                  <div class="justify-center">
                    <div style="text-align: start; width: 60%">
                      {{exp.localitzacions}}
                    </div>
                  </div>
                </td>
                <td>
                  <div class="justify-center">
                    <div style="text-align: start;  ">
                      {{exp.incidents}}
                    </div>
                  </div>
                </td>
                <td>{{exp.cartes_count}}</td>
                <td>{{elapsedTime(exp.modificat)}}</td>
                <td>{{createdAt(exp.creat)}}</td>
                <td class="no-row-select">
                    <select
                    :class="'estat-'+exp.estat_expedient_id"
                    @change="updateSelect([exp.id], $event.target.value)">
                        <option v-for="(estat, index) in estats" :key="index"
                            style="background-color: white;"
                            :value="estat.id"
                            :selected="estat.id == exp.estat_expedient_id">
                            {{estat.estat}}
                        </option>
                    </select>
                </td>
                <td class="no-row-select">
                    <!-- <i class="bi bi-folder"></i> -->
                    <span>
                      <i class="bi bi-folder2" @click="changeTab(exp.id, exp.codi_expedient)"></i>
                    </span>
                </td>
            </tr>
        </tbody>
        <div v-else="isLoaded" class="spinner-container">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </table>
</template>
<script>
import axios from 'axios'
import moment from 'moment';

export default {
  emits: [
    'change-tab',
    'refresh-legend',
    'is-expedients-selected',
    'table-error',
    'table-message'
  ],
  props: {
    estats: {
      type: Array,
      required: true
    },
    estatsIsLoaded: {
      type: Boolean,
      required: true
    },
    filtres: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      expedients: [],
      expedientsIsLoaded: false,
      orderByColumn: 'updated_at',
      orderDir: 'desc',
      isAllSelected: false,
      lastSelect: {
        col: '',
        value: ''
      }
    }
  },
  computed: {
    isLoaded () {
      const isLoaded = this.expedientsIsLoaded && this.estatsIsLoaded;
      return isLoaded
    },
    selectedIds() {
      const selectedIds = this.expedients.filter((exp) => exp.isChecked).map((exp) => exp.id)
      this.$emit('is-expedients-selected', selectedIds.length)
      return selectedIds;
    },
  },
  methods: {
    toggleSelection() {
      if (this.isAllSelected) {
        this.isAllSelected = false;
        this.deselectAll();
      } else {
        this.isAllSelected = true;
        this.selectAll();
      }
    },
    handleRowClick(expId, event) {
   /*    const selectEl = this.$refs.tableBody.querySelector(`[data-expid="${expId}"] select`);
      const checkboxEl = this.$refs.tableBody.querySelector(`[data-expid="${expId}"] input[type="checkbox"]`);
      console.log(selectEl)
      if (!selectEl.contains(event.target)) {
        checkboxEl.click();
      } */
      const avoidElements = Array.from(this.$refs.tableBody.querySelectorAll(`[data-expid="${expId}"] .no-row-select`));
      const checkboxEl = this.$refs.tableBody.querySelector(`[data-expid="${expId}"] input[type="checkbox"]`);

      if (!avoidElements.some(el => el.contains(event.target))) {
        checkboxEl.click();
        console.log("Selected expedients ids", this.selectedIds)
      }
    },
    selectAll() {
      const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
      checkboxes.forEach((checkbox) => {
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change'));
      });
      console.log("Selected expedients ids", this.selectedIds)
    },
    deselectAll() {
      const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
      checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
        checkbox.dispatchEvent(new Event('change'));
      });
      console.log("Selected expedients ids", this.selectedIds)
    },
    changeTab (expID, expCodi) {
      console.log("\nShowing expedient with:")
      console.log("Expedient ID:", expID);
      console.log("Expedient CODI:", expCodi);
      this.$emit('change-tab', expID, expCodi)
    },
    orderBy (orderCol) {
      if (orderCol === 'estat_expedients_id') {
        // Reversed for expedient estat
        this.orderDir = this.orderDir === 'asc' ? 'desc' : 'asc';
      } else if (orderCol === this.orderByColumn && this.orderDir === 'desc') {
        this.orderDir = 'asc';
      } else {
        this.orderDir = 'desc';
      }
      console.log("Orderning by:", orderCol, this.orderDir)
      this.orderByColumn = orderCol;
      this.submit(true, true)
    },
    submit (keepOrder, showReload) {
      if(showReload) {
        this.expedientsIsLoaded = false
      }
      const self = this;
      if (keepOrder) {
        axios
          .get(`expedients-gestio/orderBy/${self.orderByColumn}/${self.orderDir}`)
          .then(response => {
            self.expedients = response.data;
            if (showReload) {
              self.expedientsIsLoaded = true;
            }
          })
          .catch((error) => { 
            self.showError(error)
          });
      } else {
        axios
          .get('expedients-gestio/all')
          .then(response => {
            self.expedients = response.data;
            this.expedientsIsLoaded = true;
          })
          .catch((error) => {
            self.showError(error)
           });
      }
    },
    selectExpedientsBy(col, value) {
      this.lastSelect.col = col
      this.lastSelect.value =
      this.expedientsIsLoaded = false
      const self = this;
      axios
        .get(`expedients-gestio/${col}/${value}`)
        .then(response => {
          self.expedients = response.data
          this.expedientsIsLoaded = true
        })
        .catch((error) => { 
          self.showError(error)
        });
    },
    updateSelect (expIDs, estatID) {
      console.log("Expedients to update:", expIDs, "To state id:", estatID)
      const self = this;
      if (expIDs.length > 0) {
        const promises = expIDs.map(expID => {
        return axios.put(`estatExpedient/${expID}`, { "estat_expedient_id": estatID });
        });
        Promise.all(promises)
        .then(responses => {
          console.log("LAST SELECT", this.lastSelect)
          this.$emit('refresh-legend');
          this.showMessage("Estat de l'expedient modificat correctament", "success")
          if (this.lastSelect.col == 'all' || this.lastSelect.col == '') {
            self.submit(true, false);
          } else {
            self.selectExpedientsBy(this.lastSelect.col, this.lastSelect.value)
          }
        
          console.log(responses);
        })
        .catch(error => {
          self.showError(error);
        });
      } else {
        this.showMessage("Selecciona un o més expedients per modifcar els seus estats", "warning")
      }
      
    },
    elapsedTime (dateTime) {
      const now = moment();
      const DATETIME = moment(dateTime);

      const timePassed = {
        days: now.diff(DATETIME, 'days'),
        hours: now.diff(DATETIME, 'hours') % 24,
        minutes: now.diff(DATETIME, 'minutes') % 60,
        seconds: now.diff(DATETIME, 'seconds') % 60
      };

      let timePassedString = '';
      if (timePassed.days > 0) {
        timePassedString = `${timePassed.days}d`;
      } else {
        if (timePassed.hours > 0 && timePassed.hours <= 6) {
          timePassedString = `${timePassed.hours}h `;
        } else {
          if (timePassed.minutes > 0) {
          timePassedString += `${timePassed.minutes}m`;
          }
          if (timePassed.minutes < 1) {
            timePassedString = '<1m';
          }
        }
        
      }
      return timePassedString;
    },
    createdAt(dateTime) {
      const formattedDateTime = moment(dateTime).format('DD-MM-YY');
      return formattedDateTime;
    },
    showError(error) {
      this.$emit('table-error', error)
    },
    showMessage(message, type) {
      this.$emit('table-message', message, type)
    }
  },

  mounted () {
    this.submit(true, true)
  },
}
</script>

<style scoped>
    .table {
      border-collapse: collapse;
    }
    .spinner-container {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .spinner-border {
      height: 60px;
      width: 60px;
      border-width: 6px;
    }
    .table tbody tr td:first-child {
      text-align: end;
      padding-right: 10px;
    }
    thead {
      height: 58px;
      position: sticky;
      top: 0;
      background-color: #e6e4e4;
    }
    th {
      vertical-align: middle;
    }
/*     .table-hover > tbody > tr:hover > * {
      background-color: #e2ab12;
      background-color: #e2127a;
      background-color: #12c7e2;
    } */
    /* 
        --primary: hsl(44, 85%, 48%, 0.3);
    --secondary: hsl(330, 85%, 48%, 0.3);
    --tertiary: hsl(188, 85%, 48%, 0.3);
    */
    .theader-item-container.active {
      background-color: rgba(255, 255, 255, .6);
      border-radius: 4px;
      color: black;
      margin: -2px;
      border: 2px solid black;
    }
    .theader-item-container:hover {
      cursor: pointer;
    }
    .justify-end {
      display: flex;
      justify-content: end;
    }
    .justify-center {
      display: flex;
      justify-content: center;
    }
    .triangle {
      display: inline-flex;
      align-items: center;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 6px 6px 0 6px;
      border-color: #000 transparent transparent transparent;
      margin-left: 6px;
      transform: rotate(0deg);
    }
    .triangle.rotate {
      transform: rotate(180deg);
    }
    .bi-folder2::before {
      font-size: 25px;
      transform: scale(1);
      display: inline;
    }
    .bi-folder2:hover::before {
      content: "\F3D8";
      cursor: pointer;
      transform: scale(1.08);
    }
    .bi-folder:hover::before {
      content: "\F3D5";
      cursor: pointer;
    }
    select {
      border-radius: 4px;
      padding: 2px 0 2px 2px;
    }
</style>