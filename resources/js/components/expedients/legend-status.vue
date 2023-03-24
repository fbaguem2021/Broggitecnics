<template lang="">
    <div id="legend" class="container">
        <div class="block"></div>
        <div class="block"></div>
        <div class="block"></div>
        <div class="block"></div>
        <div class="block"></div>
    </div>
</template>
<script>
export default {
    name: 'expedients-status',
    
}
</script>
<style lang="css">

    #legend {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: grey;
        margin-top: 20px;
    }
    .block {
        width: 140px;
        height: 140px;
        margin: 0 20px;
        background-color: rgb(31, 84, 31);
    }
</style>