<template>
    <form>
        <div class="row align-items-center">
            <div class="col-2">
                <label for="telef" class="col-form-label">Telèfon</label>
            </div>
            <div class="col-auto">
                <input type="phone" id="telef" class="form-control" aria-describedby="telefInterlocutor" tabindex="1">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-2">
                <label for="nom" class="col-form-label">Nom</label>
            </div>
            <div class="col-auto">
                <input type="text" id="nom" class="form-control" aria-describedby="nomInterlocutor" tabindex="2">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-2">
                <label for="cognom" class="col-form-label">Cognom</label>
            </div>
            <div class="col-auto">
                <input type="text" id="cognom" class="form-control" aria-describedby="cognomInterlocutor" tabindex="3">
            </div>
        </div>

        <div class="row align-items-center">
            <label for="antecedentsTextarea" class="form-label">Antecedents</label>
            <textarea class="form-control" id="antecedentsTextarea" style="height: 80px" tabindex="4"></textarea>
        </div>

        <div class="form-check form-switch form-check-reverse mt-4">
            <label class="form-check-label" for="saveInterlocutor">Guardar interlocutor</label>
            <input class="form-check-input" type="checkbox" role="switch" id="saveInterlocutor" tabindex="5" @focusout="nextForm">
        </div>
    </form>
</template>
<script>
export default {
    data() {
        return {
            tabIndex: 1
        }
    },
    methods: {
        nextForm () {
            console.log("To the next form i say!")
        }
    },
}
</script>
<style scoped>
    form {
        display: flex;
        flex-direction: column;
        gap: 5%;
        height: 100%;
        max-width: 850px;
    }
    label {
        font-size: 18px;
        color: black;
    }
    #antecedentsTextarea {
        max-height: 150px;
    }
    .form-check-label {
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>