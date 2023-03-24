<template>
    <div>
        <div class="card mx-auto mt-3">
            <div class="card-body">
                <!-- <BuscadorOperadors/> -->
                <form name="formbuscar" @submit.prevent="getData(current_page)">
                    <div class="row  mx-1 p-0 rounded border border-dark bg-dark"
                    name="filtros">
                        <div class="col-4 p-0">
                            <select id="filtro" name="filtro"
                                class="form-select custom-border"
                                aria-label="Filtro"
                                v-model="filtrado">
                                <option disabled selected :value="null">Escoja un filtro</option>
                                <option v-for="filtro in filtros" :value="filtro.value">{{ filtro.label }}</option>
                            </select>
                        </div>
                        <input id="valor" name="valor"
                            type="text"
                            class="col-7 form-input custom-border"
                            v-model="buscado"
                            :required="selectHasValue"
                            :disabled="!selectHasValue">
                        <button
                            type="submit"
                            class="col-1 btn btn-transparent text-white">
                            <i class="bi bi-search"></i>
                        </button>

                    </div>
                </form>
                <div class="mt-4 mx-3">
                    <table class="table">
                        <thead>
                            <tr class="active">
                                <!-- <th scope="col">ID</th> -->
                                <th scope="col"
                                    class="col-4"
                                    >UserName</th>
                                <th scope="col"
                                    class="col-4"
                                    >Nombre</th>
                                <!-- <th scope="col"
                                    class="col-3"
                                    >Apellidos</th> -->
                                <th scope="col"
                                    class="col-4"
                                    >Tipo</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuari in usuaris.data">
                                <!-- <td scope="row">{{ usuari.id }}</td> -->
                                <td>{{ usuari.username }}</td>
                                <td>{{ `${usuari.nom} ${usuari.cognoms}` }}</td>
                                <!-- <td>{{ usuari.cognoms }}</td> -->
                                <td>{{ usuari.tipus }}</td>
                                <td>{{  }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a @click="prevPage" class="page-link page-link-btn">
                                    <span>
                                        <span aria-hidden="true">«</span>
                                    </span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link">
                                    <span><span aria-hidden="true">
                                        {{ current_page }}
                                    </span></span>

                                </a>
                            </li>
                            <li class="page-item">
                                <a @click="nextPage" class="page-link page-link-btn">
                                    <span>
                                        <span aria-hidden="true">»</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// import BuscadorOperadors from './BuscadorOperadors.vue'
export default {
    name: 'operadors',
    data() {
        return {
            usuario: {},
            filtrado: '',
            buscado: '',
            usuaris: [],
            current_page: 1,
            test: 0
        }
    },
    mounted() {
        // this.usuario = window.Usuario;
        this.usuario = window.Usuario
        delete window.Usuario;
    },
    created() {
        // console.log(window.User)
        this.getData();
    },
    methods: {
        getUrl() {
            const self = this;
            let url;
            if (self.filtrado == '') {
                url = `/api/usuari?page=${this.current_page}`
                // console.log(`/api/usuari?page=${this.current_page}`);
            } else {
                url = `/api/usuari-buscar${this.query}`;
                // console.log(`normal: /api/usuari-buscar?${self.filtrado}=${self.buscado}?page=${page}`);
                // console.log(`query:  /api/usuari-buscar${this.query}`);
            }
            return url;
        },
        getData(page = 1) {
            const self = this;
            // axios.get(`/api/usuari?page=${page}`)
            axios.get(this.getUrl())
                .then(response => {
                    return response.data
                })
                .then(data => {
                    // console.log(data);
                    self.usuaris = data
                    self.current_page = data.meta.current_page
                })
        },
        nextPage() {
            const meta = this.usuaris.meta;

            if (meta['current_page'] < meta['last_page']) {
                // let next_page = meta['current_page'] + 1;
                // this.getData(next_page)
                this.current_page++;
                this.getData();
                console.log('+')
            }
        },
        prevPage() {
            const meta = this.usuaris.meta;

            if (meta['current_page'] > 1) {
                // let prev_page = meta['current_page'] - 1;
                // this.getData(prev_page)
                this.current_page--;
                this.getData();
                console.log('-');
            }
        }
    },
    computed: {
        filtros() {
            return [
                { 'value': '',          'label': 'Ninguno' },
                { 'value': 'username',  'label': 'Nombre de usuario', },
                { 'value': 'nom',       'label': 'Nombre' },
                { 'value': 'cognoms',   'label': 'Apellidos' }
            ]
        },
        selectHasValue() {
            return this.filtrado != '';
        },
        query() {
            return `?${this.filtrado}=${this.buscado}&page=${this.current_page}`;
        }
    },
    // components: {
    //     BuscadorOperadors
    // },
}
</script>
<style>
.card {
    width: 75%;
}
.page-link {
    -webkit-user-select: none; /* Safari */
    -ms-user-select: none; /* IE 10 and IE 11 */
    user-select: none; /* Standard syntax */
}
.page-link-btn {
    cursor: pointer;
}
#filtro.form-select {
    border-radius: 0.375rem 0 0 0.375rem;
}
.custom-border {
    border: 1px solid black !important;
}
input:focus {
    outline: none;
}
input[disabled] {
    background-color: #e7e7e7;
}
</style>
