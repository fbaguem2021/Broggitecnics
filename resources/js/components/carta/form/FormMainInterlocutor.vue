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
                <input type="text" id="nom" class="form-control" aria-describedby="nomInterlocutor" autocomplete="off" tabindex="2">
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

        <div class="form-floating mt-2">
            <textarea class="form-control" placeholder="Anota antecedents" id="antecedentsTextarea" 
            :style="{ 'height': newInterlocutor? '60px' : '14vh',
            'max-height': newInterlocutor? '10vh' : '14vh'}"></textarea>
            <label for="antecedentsTextarea">Antecedents</label>
        </div>

        <div v-show="newInterlocutor" class="form-check form-switch form-check-reverse mt-2">
            <label class="form-check-label" for="saveInterlocutor">Guardar interlocutor</label>
            <input class="form-check-input" type="checkbox" role="switch" id="saveInterlocutor" tabindex="5" @focusout="nextForm">
        </div>
    </form>
</template>
<script>
export default {
    data() {
        return {
            tabIndex: 1,
            newInterlocutor: true

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
        font-size: 16px;
        color: black;
    }
    #antecedentsTextarea {
        max-height: 14vh;
        min-height: 60px;
    }
    .form-check-label {
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    #saveInterlocutor {
        margin-left: 18px;
        width: 40px;
        height: 20px;
    }
</style>