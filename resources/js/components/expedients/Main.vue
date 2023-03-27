<template lang="">
    <legendStatus :estats="estats" @estat="selectExpedientsByEstat"></legendStatus>
    <p>{{ message }}</p>
    <div class="content">
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

        <div class="table-container">
            <table class="table table-hover">
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
                            <i class="bi bi-folder2"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        
</template>
<script>
    import axios from 'axios';
    import legendStatus from './legendStatus.vue';
    import moment from 'moment'
export default {
    components: {
        legendStatus
    },
    data() {
        return {
            expedients: [],
            estats: [],
            filtres: [
                {
                    label: "Codi",
                    col: "codi"
                },
                {
                    label: "Localització",
                    col: ""
                },
                {
                    label: "Incidents",
                    col: ""
                },
                {
                    label: "Cartes",
                    col: "cartes_count"
                },
                {
                    label: "Modificació",
                    col: "updated_at"
                },
                {
                    label: "Creació",
                    col: "created_at"
                },
                {
                    label: "Estat",
                    col: "estat_expedients_id"
                },
               
            ],
            selectedEstat: '',
            message: '',
            orderByColumn: 'updated_at',
            orderDir: 'asc',

        }
    },
    methods: {
        selectExpedientsByEstat(estat_id) {
            const self = this;
            axios
                .get(`expedients/estat_expedients_id/${estat_id}`)
                .then(response => {
                    self.expedients = response.data.data;
                })
                .catch((error) => { });
        },
        orderBy(order_col) {
            if (order_col == this.orderByColumn && this.orderDir == 'desc') {
                this.orderDir = 'asc';
            } else {
                this.orderDir = 'desc';
            }
            this.orderByColumn = order_col;
            console.log("ordering by: "+this.orderByColumn);
            console.log("direction: " + this.orderDir);
            const self = this;
            axios
                .get(`expedients/orderBy/${order_col}/${self.orderDir}`)
                .then(response => {
                    self.expedients = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        submit(keepOrder) {
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
                .get("expedients/all")
                .then(response => {
                    console.log(response);
                    self.expedients = response.data.data;
                    self.expedientsByEstat();
                })
                .catch((error) => { });
            }
        },
        expedientsByEstat() {
            const self = this;
            axios
                .get("estatExpedient")
                .then(response => {
                    self.estats = response.data;
                    console.log(response);
                })
                .catch((error) => { });
        },
        updateSelect(exp_id, estat_id) {
            const self = this;
            axios
                .put(`expedient/${exp_id}`, { estat_expedient_id: estat_id })
                .then(response => {
                    console.log(response);
                    this.submit(true);
                })
                .catch((error) => { });

        },
        elapsedTime(dateTime) {
            const now = moment();
            const DATETIME = moment(dateTime);

            const timePassed = {
            days: now.diff(DATETIME, 'days'),
            hours: now.diff(DATETIME, 'hours') % 24,
            minutes: now.diff(DATETIME, 'minutes') % 60,
            seconds: now.diff(DATETIME, 'seconds') % 60
            };

            let timePassedString = '-';
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
                    timePassedString += `${timePassed.seconds}s`;
                }
            }
            return timePassedString;
        }
    },
    computed: {
        
    },
    mounted() {
        this.orderBy('updated_at');
        const self = this;
        axios
            .get("estatExpedient")
            .then(response => {
                self.estats = response.data;
                console.log(response);
            })
            .catch((error) => { });

    },
}
</script>
<style scoped>
.content {
    display: flex;
    flex-direction: column;
    height: 70%;
    margin: 20px 100px;
    padding-top: 20px;
    background-color: #D9D9D9;
}

.search-box {
    width: 600px;
}

.search-box * {
    height: 100% !important;
    min-height: 36px;
}

.search-box .form-select {
    height: 100%;
    border-radius: 5px 0 0 5px;
    padding-left: 20px;
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

.search-box,
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

.bi-folder2:before {
    font-size: 25px;
    transform: scale(1);
}

.bi-folder2:hover:before {
    content: "\F3D8";
    cursor: pointer;
    transform: scale(1.08);
}

.bi-folder:hover:before {
    content: "\F3D5";
    cursor: pointer;
}

 select {
    border-radius: 4px;
    padding: 2px 0 2px 2px;
}</style>