<template>
    <form>
        <div class="row align-items-center">
            <div class="col-2">
                <label for="telef" class="col-form-label">Telèfon</label>
            </div>
            <div class="col-auto">
                <input type="phone" id="telef" class="form-control" aria-describedby="telefInterlocutor">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-2">
                <label for="nom" class="col-form-label">Nom</label>
            </div>
            <div class="col-auto">
                <input type="text" id="nom" class="form-control" aria-describedby="nomInterlocutor">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-2">
                <label for="cognom" class="col-form-label">Cognom</label>
            </div>
            <div class="col-auto">
                <input type="text" id="cognom" class="form-control" aria-describedby="cognomInterlocutor">
            </div>
        </div>

        <div class="row align-items-center">
            <label for="floatingTextarea2" class="form-label">Antecedents</label>
            <textarea class="form-control" id="floatingTextarea2" style="height: 100px"></textarea>
        </div>

        <div class="form-check form-switch form-check-reverse">
            <label class="form-check-label" for="saveInterlocutor">Guardar interlocutor</label>
            <input class="form-check-input" type="checkbox" role="switch" id="saveInterlocutor">
        </div>



    
    </form>
</template>
<script>
export default {
}
</script>
<style scoped>
    form {
        max-width: 850px;
    }
</style>