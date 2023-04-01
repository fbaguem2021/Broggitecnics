<template lang="">
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th scope="col" v-for="(filtre, index) in filtres" :key="index" :style="{ 'text-align': filtre.label == 'Codi' ? 'end' : '' }">
                    {{filtre.label}}
                    <span
                        :class="orderByColumn === filtre.col && orderDir === 'desc' ? 'triangle rotate' : 'triangle'"
                        @click="orderBy(filtre.col)">
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(exp, index) in expedients" :key="index">
                <td>{{exp.codi_expedient}}</td>
                <td>loc</td>
                <td>ins</td>
                <td>{{exp.cartes_count}}</td>
                <td>{{elapsedTime(exp.modificat)}}</td>
                <td>{{elapsedTime(exp.creat)}}</td>
                <td>
                    <select
                    :class="'estat-'+exp.estat_expedient_id"
                    @change="updateSelect(exp.id, $event.target.value)">
                        <option v-for="(estat, index) in estats" :key="index"
                            :value="estat.id"
                            :class="'state-'+estat.id"
                            :selected="estat.id === exp.estat_expedient_id">
                            {{estat.estat}}
                        </option>
                    </select>
                </td>
                <td style="width: 40px; padding: 0">
                    <!-- <i class="bi bi-folder"></i> -->
                    <i class="bi bi-folder2" @click="changeTab(exp.id, exp.codi_expedient)"></i>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import axios from 'axios'
import moment from 'moment';

export default {
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
      orderDir: 'asc'
    }
  },
  methods: {
    changeTab (expID, expCodi) {
      console.log('CHANGE TAB CALLED');
      console.log(expID);
      console.log(expCodi);
      this.$emit('change-tab', expID, expCodi)
    },
    selectExpedientsByEstat (estatID) {
      const self = this;
      axios
        .get(`expedients/estat_expedients_id/${estatID}`)
        .then(response => {
          self.expedients = response.data.data
        })
        .catch((error) => { });
    },
    orderBy (orderCol) {
      if (orderCol === this.orderByColumn && this.orderDir === 'desc') {
        this.orderDir = 'asc';
      } else {
        this.orderDir = 'desc';
      }
      this.orderByColumn = orderCol;
      console.log('ordering by: ' + this.orderByColumn);
      console.log('direction: ' + this.orderDir);
      const self = this;
      axios
        .get(`expedients/orderBy/${orderCol}/${self.orderDir}`)
        .then(response => {
          self.expedients = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    submit (keepOrder) {
      const self = this;
      if (keepOrder) {
        axios
          .get(`expedients/orderBy/${self.orderByColumn}/${self.orderDir}`)
          .then(response => {
            console.log(response);
            self.expedients = response.data.data;
            self.expedientsByEstat();
          })
          .catch((error) => { });
      } else {
        axios
          .get('expedients/all')
          .then(response => {
            console.log(response);
            self.expedients = response.data.data;
            self.expedientsByEstat();
          })
          .catch((error) => { });
      }
    },
    updateSelect (expID, estatID) {
      const self = this;
      axios
        .put(`expedient/${expID}`, { estat_expedient_id: estatID })
        .then(response => {
          console.log(response);
          self.submit(true);
        })
        .catch((error) => { });
    },
    elapsedTime (dateTime) {
      const now = moment();
      const DATETIME = moment(dateTime);
      console.log("now:");
      console.log(now);
      console.log("touched:");
      console.log(DATETIME);

      const timePassed = {
        days: now.diff(DATETIME, 'days'),
        hours: now.diff(DATETIME, 'hours') % 24,
        minutes: now.diff(DATETIME, 'minutes') % 60,
        seconds: now.diff(DATETIME, 'seconds') % 60
      };
      console.log("timepassed");
      console.log(timePassed);
      let timePassedString = '';
      if (timePassed.days > 0) {
        timePassedString = `${timePassed.days}d`;
      } else {
        if (timePassed.hours > 0) {
          timePassedString = `${timePassed.hours}h `;
        }
        if (timePassed.minutes > 0) {
          timePassedString += `${timePassed.minutes}m`;
        }
        if (timePassed.minutes < 1 && timePassed.seconds > 0) {
          timePassedString = '< 1m';
        }
      }
      return timePassedString;
    }
  },
  mounted () {
    const self = this;
    axios
      .get('expedients/all')
      .then(response => {
        console.log(response);
        self.expedients = response.data.data;
        self.expedientsByEstat();
      })
      .catch((error) => { });
  }
}
</script>

<style scoped>
        .table-container {
        padding: 0 80px;
        text-align: center;
    }

    .table-container {
        padding: 0 40px;
        margin-top: 20px;
        flex-grow: 1;
    }
    .table tbody tr td:first-child {
    text-align: end;
    padding-right: 10px;
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

    .triangle:hover {
        cursor: pointer;
    }

    .triangle.rotate {
        transform: rotate(180deg);
    }

    .bi-folder {
        font-size: 25px;
    }

    .bi-folder2::before {
        font-size: 25px;
        transform: scale(1);
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