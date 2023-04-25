<template>
    <div class="table-container table-scrollable"
        style="position: absolute;">
        <div>
            <table class="table" id="tabla-expedients">
                <thead>
                    <tr class="row bg-white">
                        <th scope="col" class="col-2 text-center">Trucada Previa</th>
                        <th scope="col" class="col-4 th-loc">Provincies</th>
                        <th scope="col" class="col-4 th-typ">Incidents</th>
                        <th scope="col" class="col-2 th-exp">
                            <button class="col-12 btn btn-sm btn-tertiary text-white text-center">
                                Nou Expedient
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr class="row">
                        <td class="col-2 centered">
                            <input type="checkbox"
                                class="form-check-input"
                                :checked="true"
                                disabled>
                        </td>
                        <td id="tooltip_loc" class="col-4 align-middle" ref="test" :numbero="20">default</td>
                        <td class="col-4">default</td>
                        <td class="col-2 centered">
                            <button
                                class="btn btn-exp btn-outline-secondary col-9 text-center">
                                <i v-if="true" class="bi bi-check-all"></i>
                                <span v-else>Vincular</span>
                            </button>
                        </td>
                    </tr> -->
                    <tr class="row" v-for="(e, index) in expedients" :key="e.id">
                        <td class="col-2 centered">
                            <input type="checkbox"
                                class="form-check-input"
                                :checked="checkInterlocutor()"
                                disabled>
                        </td>
                        <td class="col-4 align-middle"
                            :ref="'locs'">{{ getLoc(e.localitzacions) }}</td>
                        <td class="col-4">{{ getTipus(e.tipus) }}</td>
                        <td class="col-2 centered">
                            <button @click="seleccionarExpedient(e.id)"
                                class="btn btn-exp col-9 text-center"
                                :class="{ 'btn-outline-tertiary': isSelected(e.id), 'btn-outline-secondary': !isSelected(e.id) }">
                                <i v-if="isSelected(e.id)" class="bi bi-check-all"></i>
                                <span v-else>Vincular</span>
                                <!-- <i v-if="isSelected(e.id)" class="bi bi-check-circle"></i>
                                <i v-else class="bi bi-x-circle"></i> -->
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import * as bootstrap from 'bootstrap';
import axios from 'axios';

export default {
    props: {
        localitzacio: {
            type: Object,
            required: true,
        },
        newExpedientCode: {
            type: String
        }
    },
    data() {
        return {
            tooltip: {},
            expedient_selected: 0,
            expedients: [],
            tooltips: [],
        }
    },
    mounted() {
        this.getExpedients()
    },
    watch: {
        localitzacio(newData, oldData) {
            if (newData.provincia != undefined && newData.provincia != '') {

            }
        }
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
        getExpedients(params='') {
            const self = this
            // const url = 'expedients/cartaTrucada'
            this.num++
            console.log(params);
            // const url = 'expedients-carta-trucada'
            const url = `expedients-carta-trucada/${params}`
            // axios.get('expedients/cartaTrucada')
            axios.get(url)
            .then(response => {
                return response.data
            })
            .then(data => {
                // self.expedients = data.sort((a,b) => {
                //     if ( a.localitzacions.toLowerCase().includes(tipus.toLowerCase()) ) {
                //         return -1
                //     }
                //     if ( b.localitzacions.toLowerCase().includes(tipus.toLowerCase()) ) {
                //         return 1
                //     }
                //     return 0
                // })
                self.expedients = data
                // console.log('data',data);
                setTimeout(() => {
                    this.initTooltips(data,self)
                }, 1000);
            })
        },
        initTooltips(data, self) {
            self.$refs.locs.forEach( (element, index) => {
                // const full_loc = JSON.parse(data[index].full_loc).join('\n')
                const full_loc = JSON.parse(data[index].full_loc).join('<br>')
                self.tooltips.push(
                    // new bootstrap.Tooltip(element, {"title": `Localizaciones: ${data[index].localitzacions}`})
                    new bootstrap.Tooltip(element, {
                        "title": full_loc,
                        "html":true,
                        "template": /*html*/ `
                        <div class="tooltip" name="holamundo" role="tooltip">
                            <div class="tooltip-arrow"></div>
                            <div class="tooltip-inner custom-tooltip"></div>
                        </div>
                        `
                    })
                )
            })
            // self.tooltips[0].show()
            // for ( const [index, element] of self.$refs.locs) {
                // self.tooltips.push(
                //     new bootstrap.Tooltip(element, {"title": `Localizaciones: ${data[index].localitzacions}`})
                // )
            // }
        },
        createTooltip(id, localitzacions) {
            const element = document.querySelector(`#expediente-${id}`)
            this.tooltip = new bootstrap.Tooltip(element, {
                "title":`Localizaciones: ${localitzacions}`,
            })
        },
        seleccionarExpedient(selected_id) {
            // console.log('expedient_selected: ',this.expedient_selected, ', selected_id: ',selected_id);
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
    },
}
</script>
<style scoped>
    .table-container {
        width: 100%;
        height: 100%;
    }
    table {
        margin-bottom: 0;
    }
    .table-scrollable {
        position: absolute;
        height: 100% !important;
        overflow-x: hidden;
    }
    .table-scrollable > div {
        position: relative;
        overflow: scroll;
        overflow-x: hidden;
        height: 100% !important;
        padding: 0;
    }
    .table-scrollable > div > table > thead {
        position: sticky;
        top: 0;
        background-color: white;
        z-index: 10;
    }
    .centered {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .centered > button {
        text-align: center;
    }
    thead > tr.row {
        display: block;
    }
    tr.row {
        margin-left: 0;
        margin-right: 0;
    }
    .th-exp {
        padding-left: 0;
        padding-right: 0;
    }
    .btn-exp {
        height: 100%;
    }
    /* .center-child {
        position: relative;
        padding: 0px !important;
    } */
    /* .center-child > * {
        margin: 0px !important;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
    } */
</style>