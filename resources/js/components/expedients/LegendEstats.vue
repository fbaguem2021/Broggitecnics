<template>
    <div v-for="(estat, index) in estatsUppercase()" :key="index" class="block">
        <div class="block-container" @click="sendData(estat.id)">
            <div class="title">
                {{estat.estat}}
            </div>
            <div class="data">
                <div class="color-container" :class="'estat-' + estat.id"></div>
                <span class="count">{{estat.count}}</span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: [
        'selectByEstat',
        'legend-error'
    ],
    props: {
        estats: {
            type: Array,
            required: true
        }
    },
    data () {
        return {

        }
    },
    mounted () {

    },
    methods: {
        estatsUppercase () {
            const upperCaseStatus = this.estats.map(estat => ({
            ...estat,
            estat: estat.estat.toUpperCase()
            }))
            return upperCaseStatus;
        },
        sendData (estatID) {
            this.$emit('selectByEstat', estatID);
        },
        showError(error) {
            this.$emit('leg end-error', error)
        }
    }
}

</script>
<style scoped lang="css">
.block {
    width: 100%;
    height: fit-content;
    margin: 0 10px;
    border-radius: 4px;
    box-shadow: 0 0 10px 4px rgb(0 0 0 / 7%);
    transform: scale(1);
    transition: transform .1s ease-in-out;
}
.block:hover {
    transform: scale(1.02);
}

.block-container {
    display: flex;
    align-items: center;
    position: relative;
    height: 100%;
    padding: 2px 10px 2px 6px;
}

.block-container .title {
    padding-left: 7px;
    font-size: 16px;
}

.block-container:hover {
    cursor: pointer;
}

.block-container .data {
    display: flex;
    align-items: center;
    margin-left: auto;
}

.block-container .color-container {
    height: 22px;
    width: 22px;
    border-radius: 4px;
}

.count {
    font-size: 18px;
    font-weight: bold;
    margin-left: 14px;
}

/* PROCES */
.estat-1 {
    background-color: #24DB4A;
}

/* SOLICITAT */
.estat-2 {
    background-color: #E9E241;
}

/* ACCEPTAT */
.estat-3 {
    background-color: #889D35;
}

/* TANCAT */
.estat-4 {
    background-color: #47B2FF;
}

/* IMMOBILITZAT */
.estat-5 {
    background-color: #8924D9;
    color: white;
}
</style>