<template>
    <div>

        <button v-if="idUsuario == -1"

            @click="abrirModal('crear')"
            class="btn btn-secondary btn-flotant"
            :class="{ hide: idUsuario > 0 }">
            <i class="bi bi-plus-circle"></i> Crear Usuario
        </button>
        <div v-else>
            <button :modalref="modalId" @click="abrirModal('editar')" class="btn btn-sm btn-tertiary ms-3 me-1">
                <i class="bi bi-pencil-square"></i>
            </button>
            <button @click="abrirModal('borrar')" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <!-- <form id="modalUsuario" -->
        <form :id="modalId"
            @submit.prevent="insertarUsuario()"
            class="modal"
            tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="idUsuario == -1"
                            class="modal-title">Crear usuario</h5>
                        <h5 v-else
                            class="modal-title">Editar usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" name="username" id="username"
                                class="form-control mb-2"
                                v-model="_userdata.username" required>
                            <label for="username">Nombre de usuario</label>
                        </div>
                        <div class="form-floating"
                            :class="{ hide: idUsuario > 0 }">
                            <input type="text" name="password" id="password"
                                class="form-control mb-2"
                                v-model="_userdata.contrasenya" required>
                            <label for="password">Contraseña</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="nombre" id="nombre"
                                class="form-control mb-2"
                                v-model="_userdata.nom" required>
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="apellidos" id="apellidos"
                                class="form-control mb-2"
                                v-model="_userdata.cognoms" required>
                            <label for="apellidos">Apellidos</label>
                        </div>
                        <div class="form-floating">
                            <select id="tipususuari" name="tipususuari"
                                class="form-select"
                                v-model="_userdata.tipus_usuaris_id" required>
                                <!-- <option value="1">Operador 112</option>
                                <option value="2">Supervisor 112</option>
                                <option value="3">Administrador Sistema</option> -->
                                <option v-for="tipus in tiposusuaris" :key="tipus.id"
                                    :value="tipus.id"
                                    :selected="checkTipo(tipus.id)"
                                    >{{ tipus.nom }}</option>
                            </select>
                            <label for="tipususuari">Works with selects</label>
                        </div>
                        <!-- <p>Modal body text goes here.</p> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Aceptar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>
<script>
import * as bootstrap from 'bootstrap'
import { markRaw } from 'vue'
export default {
    // props: ['idUsuario'],
    props: {
        idUsuario: {
            type: Number,
            default: -1,
        }
    },
    data() {
        return {
            _userdata: {
                id: '',
                username: '',
                contrasenya: '',
                nom: '',
                cognoms: '',
                tipus_usuaris_id: '',
            },
            modal: {},
            edituser: {},
            loggedUser: window.Usuario,
            tipoModal: 'crear',
        }
    },
    // mounted() {
    //     console.log(this.idUsuario);
    // },
    methods: {
        abrirModal(modo="crear") {
            this.tipoModal = modo

            // this.modal = new bootstrap.Modal('#modalUsuario')
            this.modal = new bootstrap.Modal(`#${this.modalId}`)
            this.modal.show()
            console.log(this.modalId);

            if (modo == 'editar') {
                this.getUsuario()
                console.log('editar');
            } else if ( modo == 'borrar' ) {
                console.log('borrar');
            }
        },
        insertarUsuario() {
            this.modal.hide()
            axios.post('/api/usuari',this._userdata)
                .then( response => {
                    console.log(response.data);
                })
                .catch( error => {
                    console.error(error);
                })
        },
        getUsuario() {
            axios.get(`/api/usuari/${this.idUsuario}`)
                .then( response => {
                    return response.data
                })
                .then( data => {
                    this.edituser = data
                    this._userdata = data
                })
                .catch( error => { console.error(error) })
        },
        checkTipo(id) {
            return this._userdata.tipus_usuaris_id == id
        }
    },
    computed: {
        modalId() {
            if (this.idUsuario == -1) {
                return 'modal-crear'
            } else {
                return `modal-${this.idUsuario}`
            }
        },
        tiposusuaris() {
            if ( this.loggedUser.tipus_usuaris_id == 2 )
                return [ { id: 1, nom: 'Operador 112' }, { id: 2, nom: 'Supervisor 112' } ]
            else
                return [ { id: 1, nom: 'Operador 112' }, { id: 2, nom: 'Supervisor 112' }, { id: 3, nom: 'Administrador Sistema' } ]
            // return [ { id: 1, nom: 'Operador 112' }, { id: 2, nom: 'Supervisor 112' }, { id: 3, nom: 'Administrador Sistema' } ]
        }
    }
}
</script>
<style >
.hide {
    display: none;
}
.btn-flotant {
    position: fixed;
    bottom: 50px;
    right: 50px;
}
.modal {
    background-color: rgb(128, 128, 128, 0.75);

    /* display: block; */
}
.modal > .modal-dialog {
    margin-top: 6rem !important;
    max-width: 50%;
}
</style>
