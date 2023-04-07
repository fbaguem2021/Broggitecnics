<template>
    <form id="interlocutor-form">
        <!-- <div class="row align-items-center">
            <div class="col-2 input-label-container">
                <label for="telef" class="col-form-label">Telèfon</label>
            </div>
            <div class="col-5" id="telef-input-container">
                <input type="phone" id="telef" class="form-control" aria-describedby="telefInterlocutor" tabindex="1">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-2 input-label-container">
                <label for="nom" class="col-form-label">Nom</label>
            </div>
            <div class="col-8">
                <input type="text" id="nom" class="form-control" aria-describedby="nomInterlocutor" autocomplete="off" tabindex="2">
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-2 input-label-container">
                <label for="cognom" class="col-form-label">Cognoms</label>
            </div>
            <div class="col-8">
                <input type="text" id="cognom" class="form-control" aria-describedby="cognomInterlocutor" tabindex="3">
            </div>
        </div> -->
        
        <div class="form-floating mb-3" id="phone-input-container">
            <input v-model="phone" type="phone" class="form-control" id="phone" placeholder="Telèfon" autocomplete="off">
            <label for="phone">Telèfon</label>
        </div>
        <div class="form-floating mb-3">
            <input v-model="name" type="text" class="form-control" id="name" placeholder="Nom" autocomplete="off">
            <label for="name">Nom</label>
        </div>
        <div class="form-floating mb-3">
            <input v-model="surnames" type="text" class="form-control" id="surnames" placeholder="Cognoms" autocomplete="off">
            <label for="surnames">Cognoms</label>
        </div>
        

        <div class="form-floating mt-2" id="antecedents-textArea-container">
            <textarea class="form-control" placeholder="Anota antecedents" id="antecedentsTextarea"></textarea>
            <label for="antecedentsTextarea">Antecedents</label>
        </div>

        <div v-show="newInterlocutor" class="form-check form-switch form-check-reverse pb-4">
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
            newInterlocutor: true,
            phone: '',
            name: '',
            surnames: ''

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
    #interlocutor-form {
        display: flex;
        flex-direction: column;
        height: 100%;
        max-width: 850px;
    }
    label {
        font-size: 16px;
        color: black;
    }
    .input-label-container {
        min-width: 68px;
    }
    #phone-input-container {
        width: 30%;
        min-width: 110px;
    }
    #antecedents-textArea-container {
        flex-grow: 1;
    }
    #antecedentsTextarea {
        height: 100%;
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