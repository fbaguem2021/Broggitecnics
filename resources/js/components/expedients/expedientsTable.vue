<template lang="">
    <table class="table table-hover text-center">
        <thead>
            <tr>
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
        <tbody v-if="isLoaded">
            <tr v-for="(exp, index) in expedients" :key="index">
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
                <td>
                    <select
                    :class="'estat-'+exp.estat_expedient_id"
                    @change="updateSelect(exp.id, $event.target.value)">
                        <option v-for="(estat, index) in estats" :key="index"
                            style="background-color: white;"
                            :value="estat.id"
                            :selected="estat.id == exp.estat_expedient_id">
                            {{estat.estat}}
                        </option>
                    </select>
                </td>
                <td>
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
    'refresh-legend'
  ],
  props: {
    estats: {
      type: Array,
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
      orderByColumn: 'updated_at',
      orderDir: 'desc',
      isLoaded: false
    }
  },
  methods: {
    changeTab (expID, expCodi) {
      console.log('CHANGE TAB CALLED');
      console.log(expID);
      console.log(expCodi);
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
      console.log(this.orderDir)
      this.orderByColumn = orderCol;
      const self = this;
      this.submit(true)
    },
    submit (keepOrder) {
      this.isLoaded = false
      const self = this;
      if (keepOrder) {
        axios
          .get(`expedients-gestio/orderBy/${self.orderByColumn}/${self.orderDir}`)
          .then(response => {
            console.log(response);
            self.expedients = response.data;
            self.isLoaded = true;
            self.expedientsByEstat();
          })
          .catch((error) => { 
            console.log(error)
          });
      } else {
        axios
          .get('expedients-gestio/all')
          .then(response => {
            console.log(response);
            self.expedients = response.data;
            self.expedientsByEstat();
            this.isLoaded = true;
          })
          .catch((error) => {
            console.log(error)
           });
      }
    },
    selectExpedientsBy(col, value) {
      this.isLoaded = false
      const self = this;
      axios
        .get(`expedients-gestio/${col}/${value}`)
        .then(response => {
          console.log(response);
          self.expedients = response.data
          this.isLoaded = true
        })
        .catch((error) => { 
          console.log(error)
        });
    },
    updateSelect (expID, estatID) {
      const self = this;
      axios
        .put(`expedients-gestio/${expID}`, { estat_expedient_id: estatID })
        .then(response => {
          console.log(response);
          this.$emit('refresh-legend')
          self.submit(true);
        })
        .catch((error) => { });
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
    }
  },

  mounted () {
    this.submit(true)
  },
}
</script>

<style scoped>
    .table {
      border-collapse: collapse;
    }
    .spinner-container {
      position: absolute;
      width: 100%; 
      height: calc(100% - 60px); 
      display: flex; 
      justify-content:center; 
      align-items:center; 
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
    .theader-item-container.active {
      background-color: #ffffff;
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
    .estat-5 option{
      color: black;
    }
    select {
      border-radius: 4px;
      padding: 2px 0 2px 2px;
    }
</style>