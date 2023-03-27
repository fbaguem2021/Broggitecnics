<template lang="">
    <div id="legend" class="container">
        <div v-for="(estat, index) in estatsUppercase()" :key="index" class="block">
            <div class="block-container" @click="sendData(estat.id)">
                <div class="title">
                    <h5>{{ estat.estat }}</h5>
                </div>
                <div class="data">
                    <div class="color-container"
                    :class="'estat-'+estat.id"></div>
                    <span class="count">{{estat.count}}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        estats: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
      
        }
    },
    mounted() {
        
    },
    methods: {
       estatsUppercase() {
            return this.estats.map(estat => ({
                ...estat,
                estat: estat.estat.toUpperCase()
            })
        );
       },
       sendData(estat_id) {
            this.$emit('estat', estat_id);
        } 
    }
}

</script>
<style lang="css">

    #legend {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #D9D9D9;
        margin-top: 20px;
        padding: 10px;
        width: fit-content;
        border-radius: 4px;
    }
    .block {
        width: 160px;
        height: 100px;
        margin: 0 20px;
        background-color: #F8F8F8;
        border-radius: 4px;
    }
    .block-container {
        position: relative;
        height: 100%;
        padding: 10px
    }
    .block-container:hover {
        cursor: pointer;
    }
    .block-container .data {
        position: absolute;
        bottom: 10%;
        left: 50%;
        padding-right: 40px;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
    }

    .block-container .color-container{
        height: 35px;
        width: 35px;
        border-radius: 4px;
        margin-right: 20px;
    }

    .count {
        margin-left: 10px;
        font-size: 20px;
        font-weight: normal;
    }

    /* PROCES */
    .estat-1{
        background-color: #24DB4A;
    }
    /* SOLICITAT */
    .estat-2{
        background-color: #E9E241;
    }
    /* ACCEPTAT */
    .estat-3{
        background-color: #889D35;
    }
    /* TANCAT */
    .estat-4{
        background-color: #47B2FF;
    }
    /* IMMOBILITZAT */
    .estat-5{
        background-color: #8924D9;
        color: white;
    }

</style>