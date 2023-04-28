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
                            <button class="col-12 btn btn-sm text-center"
                                :class="{ 'btn-tertiary text-white': isSelected(), 'btn-outline-secondary': !isSelected() }"
                                @click="seleccionarExpedient()">
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
                        <td class="col-4"
                            :ref="'types'">{{ getTipus(e.tipus) }}</td>
                        <td class="col-2 centered">
                            <button @click="seleccionarExpedient(e.id, index)"
                                class="btn btn-exp col-9 text-center"
                                :class="{ 'btn-tertiary': isSelected(e.id), 'btn-outline-secondary': !isSelected(e.id) }">
                                <!-- <i v-if="isSelected(e.id)" class="bi bi-check-all"></i>
                                <span v-else>Vincular</span> -->
                                <i v-if="isSelected(e.id)" class="bi bi-check-circle"></i>
                                <i v-else class="bi bi-x-circle"></i>
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
    emits: ['get-expedient-info'],
    props: {
        localitzacio: {
            type: Object,
            required: true,
        },
        tipusIncident: {
            type: Object,
            required: true
        },
        infoIncidents: {
            type: Object,
            required: true
        },
        newExpedientCode: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            tooltip: { locations: [], incidents: [] },
            expedient_selected: Number,
            expedient_info: {
                is_new: Boolean,
                data: { id: Number, codi: String, estat_id: Number }
            },
            expedient_is_new: false,
            expedients: [],
            expedientsReady: false,
            tooltips: [],
        }
    },
    mounted() {
        this.getExpedients()
    },
    watch: {
        localitzacio(newData, oldData) {
            const checkProvincia = newData.provincia != undefined && newData.provincia != ''
            const checkComarca   = newData.comarca   != undefined && newData.comarca   != ''
            const checkMunicipi  = newData.municipi  != undefined && newData.municipi  != ''
            const checkValues    = newData.provincia != oldData.provincia && newData.comarca != oldData.comarca && newData.municipi != oldData.municipi

            if (checkProvincia && checkComarca && checkMunicipi) {
                const params = `${newData.provincia}/${newData.comarca}/${newData.municipi}`
                // console.log('dentro',params);
                this.getExpedients(params)
            } else {
                this.getExpedients()
                // console.log('fuera');
            }
        },
        tipusIncident:{
            handler(NEW, OLD) {
                // if ( ! newUndefined && !oldUndefined && NEW.input != '' && NEW.input != OLD.input ) {
                const expedientsExist=this.expedients!=null && this.expedients!=undefined && this.expedients.length!=0
                if ( this.tipusIncidentChecker(NEW,OLD) && this.expedientsReady == true) {
                    this.expedients = this.sortData(this.expedients, this.tipusIncident.input)
                }
            },
            deep: true
        }
    },
    methods: {
        tipusIncidentChecker(a=undefined, b=undefined) {
            // const newUndefined = (a == undefined && a == null)
            // const oldUndefined = (b == undefined && b == null)
            const newExists   = a != undefined && a != null
            const newHasValue = a.input != '' && a.input != undefined && a.input != null
            // const oldExists   = b != undefined && b != null
            // const oldHasValue = b.input != '' && b.input != undefined && b.input != null

            return newExists && newHasValue
        },
        sortData(toSort=this.expedients, filter='') {
            toSort.sort((a,b) => {
                if ( a.tipus.toLowerCase().includes(filter.toLowerCase()) ) {
                    return -1
                }
                if ( b.tipus.toLowerCase().includes(filter.toLowerCase()) ) {
                    return 1
                }
                return 0
            })
            return toSort
        },
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
            // this.num++
            console.log(params);
            // const url = 'expedients-carta-trucada'
            const url = `expedients-carta-trucada/${params}`
            // axios.get('expedients/cartaTrucada')
            axios.get(url)
            .then(response => {
                return response.data
            })
            .then(data => {
                // self.expedients = data
                self.expedients = self.sortData(data, this.tipusIncident.input)
                this.expedientsReady = true
                // console.log('data',data);
                if (data.length > 0) {
                    setTimeout(() => {
                        this.initTooltips(data,self)
                    }, 1000);
                }
            })
            .catch(error => console.error('getExpedients\n',error))
        },
        initTooltips(data, self) {
            self.tooltip['incidents'] = []
            self.tooltip['locations'] = []
            self.$refs.locs.forEach( (element, index) => {
                // const full_loc = JSON.parse(data[index].full_loc).join('\n')
                const full_loc = JSON.parse(data[index].full_loc).join('<br>')
                // self.tooltips.push(
                self.tooltip['locations'].push(
                    // new bootstrap.Tooltip(element, {"title": `Localizaciones: ${data[index].localitzacions}`})
                    new bootstrap.Tooltip(element, {
                        "title": full_loc,
                        "html":true,
                        "placement": 'left',
                        "template": /*html*/ `
                        <div class="tooltip" name="holamundo" role="tooltip">
                            <div class="tooltip-arrow"></div>
                            <div class="tooltip-inner custom-tooltip"></div>
                        </div>
                        `
                    })
                )
            })
            self.$refs.types.forEach( (element, index) => {
                const full_inc = JSON.parse(data[index].full_inc).join('<br>')
                self.tooltip['incidents'].push(
                    new bootstrap.Tooltip(element, {
                        title: full_inc,
                        html: true,
                        placement: 'left',
                        template: /*html*/ `
                        <div class="tooltip" name="holamundo" role="tooltip">
                            <div class="tooltip-arrow"></div>
                            <div class="tooltip-inner custom-tooltip"></div>
                        </div>
                        `
                    })
                )
            })
        },
        createTooltip(id, localitzacions) {
            const element = document.querySelector(`#expediente-${id}`)
            this.tooltip = new bootstrap.Tooltip(element, {
                "title":`Localizaciones: ${localitzacions}`,
            })
        },
        seleccionarExpedient(selected_id= 'default', index=undefined) {
            this.expedient_selected = selected_id
            let mode = String
            if ( selected_id == 'default' ) {
                mode = 'created'
            } else {
                mode = 'selected'
            }
            this.emitExpedient(mode,index)
        },
        emitExpedient(expedient_mode, index=undefined) {
            if (expedient_mode == 'selected') {
                const exp = this.expedients[index]
                this.expedient_info.is_new          = false
                this.expedient_info.data.id         = exp.id
                this.expedient_info.data.codi       = exp.codi
                this.expedient_info.data.estat_id   = exp.estat_id
            } else if (expedient_mode == 'created') {
                this.expedient_info.is_new          = true
                this.expedient_info.data.id         = null
                this.expedient_info.data.codi       = this.$props.newExpedientCode
                this.expedient_info.data.estat_id   = 1
            }
            this.$emit('get-expedient-info',this.$data.expedient_info)
        },
        checkInterlocutor() {
            return false
        },
        isSelected(value='default') {
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