<template lang="">
    <legendStatus></legendStatus>

    <div class="content">

        <div class="search-box">
            <div class="row p-0 g-0">
                <div class="col-4 p-0">
                    <select name="filtro"
                        class="form-select"
                        aria-label="Filtro">
                        <option disabled selected value="">Escoja un filtro</option>
                        <!-- <option v-for="filtro in filtros" :value="filtro.value">{{ filtro.label }}</option> -->
                    </select>
                </div>
                <input
                    type="text"
                    class="col-7 form-input">
                <button
                    type="submit"
                    class="col-1 btn btn-dark bg-dark text-white">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Codi</th>
                    <th scope="col">Localització</th>
                    <th scope="col">Tipus</th>
                    <th scope="col">Cartes</th>
                    <th scope="col">Ultima mod</th>
                    <th scope="col">Creació</th>
                    <th scope="col">Estat</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(exp, index) in expedients" :key="index">
                        <td>{{exp.id}}</td>
                        <td>{{exp.codi}}</td>
                        <td>{{exp.estat_expedients_id}}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        
</template>
<script>
import legendStatus from './legend-status.vue';
export default {
    components: {
        legendStatus
    },
    data() {
        return {
            expedients: [],
        }
    },
    methods: {

    },
    computed: {

    },
    mounted() {
        const self = this;
            axios
                .get("expedient")
                .then(response => {
                    self.expedients = response.data.data;
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
        padding: 0 20px;
        background-color: grey;
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


</style>