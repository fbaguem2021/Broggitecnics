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
                        <option disabled selected value="">Tots</option>
                        <option v-for="(filtre, index) in filtres" :key="index"
                            :value="filtre">
                            {{ filtre }}
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
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Codi<span class="triangle" @click="orderBy('codi')"></span></th>
                    <th scope="col">Localització <span class="triangle"></span></th>
                    <th scope="col">Incidents <span class="triangle"></span></th>
                    <th scope="col">Cartes <span class="triangle"></span></th>
                    <th scope="col">Ultima mod <span class="triangle"></span></th>
                    <th scope="col">Creació <span class="triangle"></span></th>
                    <th scope="col">Estat <span class="triangle"></span></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(exp, index) in expedients" :key="index">
                        <td>{{exp.codi_expedient}}</td>
                        <td>localitzacio</td>
                        <td></td>
                        <td>{{exp.numero_cartes}}</td>
                        <td>ultima mod</td>
                        <td>creacio</td>
                        <td>
                            <select name="" id="" :class="'estat-'+exp.estat_expedient_id">
                                <option v-for="(estat, index) in estats" :key="index" 
                                    value="{{estat.estat}}"
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
import legendStatus from './legendStatus.vue';
export default {
    components: {
        legendStatus
    },
    data() {
        return {
            expedients: [],
            estats: [],
            filtres: [
                "Codi",
                "Localització",
                "Incidents",
                "Modificació",
                "Creació",
                "Estat"
            ],
            message: '',
            lastOrder: '',
            orderDir: 'asc'
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
                .catch((error) => {});
        },
        orderBy(order_col) {
            if (order_col == this.lastOrder && this.direction == 'asc') {
                this.direction = 'desc';
            } else {
                this.direction = 'asc';
            }
            this.lastOrder = order_col;
            console.log("ordering");
            console.log("direction: "+this.direction);
            const self = this;
            axios
                .get(`expedients/orderBy/${order_col}/${self.direction}`)
                .then(response => {
                    self.expedients = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        submit(filter) {
            const self = this;
            axios
                .get("expedients/all")
                .then(response => {
                    console.log(response);
                    self.expedients = response.data.data;
                })
                .catch((error) => {});
        }
    },
    computed: {

    },
    mounted() {
        this.submit('all');
        const self = this;
        axios
            .get("estatExpedient")
            .then(response => {
                self.estats = response.data;
                console.log(response);
            })
            .catch((error) => {});
    },
}
</script>
<style lang="css">
    expedients {
        display: block;
        height: 100%;
        padding: 0 100px;
    }
    .content {
        display: flex;
        flex-direction: column;
        height: 70%;
        margin-top: 20px;
        padding: 20px 20px 0 20px;
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
    .search-box .form-input, .search-box .form-select{
        border: 1px solid black;
        background-color: #fff;
    }

    .search-box button {
        border-radius: 0 5px 5px 0;
    }

    .search-box, .table-container {
        padding: 0 80px;
    }    

    .table-container {
        margin-top: 20px;
        flex-grow: 1;
    }

    .triangle {
        display: inline-flex;
        align-items: center;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 6px 6px 0 6px;
        border-color: #000 transparent transparent transparent;
    }

    .triangle:hover {
        cursor: pointer;
    }

    .bi-folder{
        font-size: 25px;
    }
    .bi-folder2 {
        font-size: 25px;
    }

    .bi-folder2:hover:before {
        content: "\F3D8";
        cursor: pointer
    }
    .bi-folder:hover:before {
        content: "\F3D5";
        cursor: pointer;
    }
    expedients select {
        border-radius: 4px;
        padding: 2px 0 2px 2px;
    }


</style>