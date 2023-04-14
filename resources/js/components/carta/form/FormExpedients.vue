<template>
    <div class="table-container table-scrollable"
        style="position: absolute;">
            <table class="table" id="tabla-expedients">
                <thead>
                    <tr class="row bg-white">
                        <th scope="col" class="col-2 text-center"
                            :style="test">Trucada Previa</th>
                        <th scope="col" class="col-4 th-loc">Localització</th>
                        <th scope="col" class="col-4 th-typ"></th>
                        <th scope="col" class="col-2 th-exp">
                            <button class="col-12 btn btn-sm btn-tertiary text-white text-center">
                                Nou Expedient
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row" v-for="e in expedients" :key="e.id">
                        <td class="col-2 center-child">
                            <input type="checkbox"
                                class="form-check-input"
                                :checked="checkInterlocutor()"
                                disabled>
                        </td>
                        <td class="col-4 align-middle">{{ getLoc(e.localitzacions) }}</td>
                        <td class="col-4">{{ getTipus(e.tipus) }}</td>
                        <td class="col-2 center-child">
                            <button @click="seleccionarExpedient(e.id)"
                                class="btn btn-exp col-9"
                                :class="{ 'btn-outline-tertiary': isSelected(e.id), 'btn-outline-secondary withText': !isSelected(e.id) }">
                                <i v-if="isSelected(e.id)" class="bi bi-check-all"></i>
                                <span v-else>Vincular</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>
</template>
<script>
export default {
    // emits: ['expedient_vinculat'],
    props: {

    },
    data() {
        return {
            expedient_selected: 0,
            expedients: [],
        }
    },
    mounted() {
        this.getExpedients()
    },
    methods: {
        getStyles() {
            const tab = document.querySelector('#tabla-expedients');
            let styles;
            if ( tab != null ) {
                // console.log('top',tab.offsetTop)
                styles = `--top: ${tab.offsetTop}; --left: ${tab.offsetLeft}; --width: ${tab.offsetWidth};`
            }
            return styles;
        },
        getExpedients() {
            const self = this
            axios.get('expedients/cartaTrucada')
            .then(response => {
                return response.data
            })
            .then(data => {
                self.expedients = data
                // self.expedient_selected = data[0].codi
                // console.log(data);
            })
        },
        seleccionarExpedient(selected_id) {
            console.log('expedient_selected: ',this.expedient_selected, ', selected_id: ',selected_id);
            if ( this.expedient_selected != selected_id ) {
                this.expedient_selected = selected_id
            } else {
                this.expedient_selected = 0
            }
            // console.log(selected_id);
        },
        checkInterlocutor() {
            return false
        },
        isSelected(value) {
            const isSel = this.expedient_selected == value
            return isSel

        },
        getLoc(loc=false) {
            if (loc) {
                return loc
            } else {
                return 'Sense Localització'
            }
        },
        getTipus(tipus=false) {
            if (tipus) {
                return tipus
            } else {
                return 'Sense Tipus'
            }
        }
    },
    computed: {
        hasTrucades(e) {
            return e.cartes_count > 0
        },
        // logged_user() {
        //     return window.Usuario
        // }
        test() {
            return this.getStyles()
        }
    },
}
</script>
<style scoped>
    .table-container {
        width: 100%;
        height: 100%;
        padding: 0.5rem 2rem;
        /* position: relative; */
    }
    .table-container > table {
        height: 100%;
    }
    table {
        margin-bottom: 0;
    }
    /* thead {
        height: 65px;
        max-height: 65px;
    }
    thead tr {
        position: absolute;
        left: 0;
        right: 0;
    }
    tr {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    tbody {
        --percentage: 42;
        --padding: 0.5rem;
    } */
    .table-scrollable {
        position: relative;
        height: 100% !important;
        overflow: scroll;
        overflow-x: hidden;
    }
    .th-loc {

    }
    .th-typ {

    }
    .th-exp {
        padding-left: 0;
        padding-right: 0;
    }
    .btn-exp {
        height: 100%;
    }
    .hidden {
        display: none;
    }
    .btn-expedient {
        padding: 0;
        /* font-size: 12px; */
    }
    .center-child {
        position: relative;
        padding: 0px !important;
    }
    .center-child > * {
        margin: 0px !important;
        position: absolute;
        /* width: 100%;
        height: 100%; */
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
    }
</style>