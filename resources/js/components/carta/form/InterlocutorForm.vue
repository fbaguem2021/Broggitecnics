<template>
    <form>
        <div class="row g-3 align-items-center">
            <div class="col-2">
                <label for="telef" class="col-form-label">Telèfon</label>
            </div>
            <div class="col-auto">
                <input type="phone" id="telef" class="form-control" aria-describedby="telefInterlocutor">
            </div>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-2">
                <label for="nom" class="col-form-label">Nom</label>
            </div>
            <div class="col-auto">
                <input type="text" id="nom" class="form-control" aria-describedby="nomInterlocutor">
            </div>
        </div>
    </form>
</template>
<script>
export default {
}
</script>
<style scoped>
    form {
        max-width: 1000px;
    }
</style>