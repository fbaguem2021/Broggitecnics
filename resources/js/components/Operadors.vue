<template>
    <div>
        <div class="card mx-auto mt-3">
            <div class="card-body">
                <!-- <BuscadorOperadors/> -->
                <form name="formbuscar" @submit.prevent="getData(pageinfo.current_page)">
                    <div class="row  mx-1 p-0 rounded border border-dark bg-dark"
                    name="filtros">
                        <div class="col-4 p-0">
                            <select id="filtro" name="filtro"
                                class="form-select custom-border"
                                aria-label="Filtro"
                                v-model="buscador.filtrado">
                                <option disabled selected :value="null">Escoja un filtro</option>
                                <option v-for="filtro in filtros" :value="filtro.value">{{ filtro.label }}</option>
                            </select>
                        </div>
                        <input id="valor" name="valor"
                            type="text"
                            class="col-7 form-input custom-border"
                            v-model="buscador.buscado"
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
                                    class="col-3"
                                    >Tipo</th>
                                <th scope="col-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuari in usuaris.data" :key="usuari.id">
                                <!-- <td scope="row">{{ usuari.id }}</td> -->
                                <td>{{ usuari.username }}</td>
                                <td>{{ `${usuari.nom} ${usuari.cognoms}` }}</td>
                                <!-- <td>{{ usuari.cognoms }}</td> -->
                                <td>{{ usuari.tipus }}</td>
                                <td>
                                    <!-- <FormUsuario :id-usuario="usuari.id"></FormUsuario> -->
                                    <FormUsuario :id-usuario="usuari.id"
                                        @api-called="myhandler"
                                         @api-error="myhandler"></FormUsuario>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li v-if="pageinfo.hasPrev"
                                class="page-item">
                                <a @click="firstPage" class="page-link page-link-btn">
                                    <span><span aria-hidden="true">
                                        Principio
                                    </span></span>
                                </a>
                            </li>
                            <li v-if="pageinfo.hasPrev"
                                class="page-item">
                                <a @click="prevPage" class="page-link page-link-btn">
                                    <span>
                                        <span aria-hidden="true">«</span>
                                    </span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link">
                                    <span><span aria-hidden="true">
                                        {{ pageinfo.current_page }}
                                    </span></span>
                                </a>
                            </li>
                            <li v-if="pageinfo.hasNext"
                                class="page-item">
                                <a @click="nextPage" class="page-link page-link-btn">
                                    <span>
                                        <span aria-hidden="true">»</span>
                                    </span>
                                </a>
                            </li>
                            <li v-if="pageinfo.hasNext"
                                class="page-item">
                                <a @click="lastPage" class="page-link page-link-btn">
                                    <span><span aria-hidden="true">
                                        Final
                                    </span></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- <form action="" method=""></form> -->
            </div>
        </div>
        <div role="alert"
            class="alert alert-primary alert-dismissible fade col-4 mx-auto floating border"
            :class="{ show: alert.show, 'alert-tertiary': !alert.error, 'alert-danger': alert.error }">
            {{ alert.message }}
            <button @click="toggleAlert(alert.show)"
                type="button" class="btn-close"
                data-dismiss="alert" aria-label="Close">
            </button>
        </div>
        <!-- <FormUsuario/> -->
        <FormUsuario
            @api-called="myhandler"
            @api-error="myhandler"/>
        <!-- <FormUsuario :id-usuario="loggedUser.id"/> -->
    </div>
</template>
<script>
// import BuscadorOperadors from './BuscadorOperadors.vue'
import FormUsuario from './operadors/form.vue'

import { markRaw } from 'vue'
export default {
    name: 'operadors',
    data() {
        return {
            usuaris: [],
            // loggedUser: {},
            loggedUser: window.Usuario,
            buscador: {
                filtrado: '',
                buscado: ''
            },
            pageinfo: {
                current_page: 1,
                hasNext: false,
                hasPrev: false
            },
            alert: {
                show: false,
                code: 0,
                error: false,
                message: '',
            }
        }
    },
    mounted() {
        // this.loggedUser = window.Usuario;
        // this.loggedUser = window.Usuario
        // delete window.Usuario;
    },
    created() {
        this.getData();
    },
    methods: {
        getUrl() {
            const self = this;
            let url;
            if (self.buscador.filtrado == '') {
                let id = window.Usuario.id;
                url = `/api/usuari?id=${id}&page=${this.pageinfo.current_page}`
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
                    // console.log(response);
                    return response.data
                })
                .then(data => {
                    // console.log(data);
                    self.usuaris = data
                    self.pageinfo.current_page = data.meta.current_page
                    self.pageinfo.hasNext = data.links.next != null
                    self.pageinfo.hasPrev = data.links.prev != null
                })
        },
        nextPage() {
            const meta = this.usuaris.meta;

            if (meta['current_page'] < meta['last_page']) {
                // let next_page = meta['current_page'] + 1;
                // this.getData(next_page)
                this.pageinfo.current_page++;
                this.getData();
                // console.log('+')
            }
        },
        prevPage() {
            const meta = this.usuaris.meta;

            if (meta['current_page'] > 1) {
                // let prev_page = meta['current_page'] - 1;
                // this.getData(prev_page)
                this.pageinfo.current_page--;
                this.getData();
                // console.log('-');
            }
        },
        firstPage() {
            this.pageinfo.current_page = 1
            this.getData()
        },
        lastPage() {
            const meta = this.usuaris.meta;
            this.pageinfo.current_page = meta['last_page']
            this.getData()
        },
        myhandler(n, isDelete=false) {
            switch (n.data.code) {
                case 400:
                case 404:
                    console.log('error');
                    break;

                default:
                    console.log('not error');
                    if ( isDelete && (this.usuaris.data.length == 1 && this.pageinfo.current_page > 1)) {
                        this.pageinfo.current_page--
                    }
                    this.getData();
                    break;
            }
            this.setAlert(n.data)
            this.toggleAlert(false)
        },
        setAlert(data) {
            this.alert.code = data.code
            this.alert.message = data.missatge
            if (data.code >= 400) {
                this.alert.error = true;
            } else {
                this.alert.error = false;
            }
        },
        toggleAlert(alertIsShown=true) {
            // if (show) {
            //     this.alert.show = false
            // } else {
            //     this.alert.show = true
            // }
            // if (this.alert.show == true) {
            if (alertIsShown) {
                this.alert.show = false
            } else {
                this.alert.show = true
                setTimeout(()=>{
                    this.alert.show = false
                }, 2000)
            }
        },
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
            return this.buscador.filtrado != '';
        },
        query() {
            return `?${this.buscador.filtrado}=${this.buscador.buscado}&page=${this.pageinfo.current_page}`;
        },
    },
    components: {
        FormUsuario
    //     BuscadorOperadors
    },
}
</script>
<style scoped>
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
.my-alert-dismissible {
    padding-right: 3rem;
}
.my-alert-dismissible .btn-close {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 2;
    padding: 1.25rem 1rem;

}
.floating {
    z-index: 1000;
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
}
</style>
