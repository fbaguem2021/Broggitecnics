<template>
    <form id="interlocutor-form">        
        <div class="form-floating mb-3" id="phone-input-container">
            <input v-model="phone" type="phone" class="form-control" id="phone" placeholder="Telèfon" autocomplete="off">
            <label for="phone">Telèfon</label>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-floating mb-3">
                    <input v-model="name" type="text" class="form-control" id="name" placeholder="Nom" autocomplete="off">
                    <label for="name">Nom</label>
                </div>
            </div>
            <div class="col-8">
                <div class="form-floating mb-3">
                    <input v-model="surnames" type="text" class="form-control" id="surnames" placeholder="Cognoms" autocomplete="off">
                    <label for="surnames">Cognoms</label>
                </div>
            </div>
        </div>
       

        <div class="form-floating" id="antecedents-textArea-container">
            <textarea class="form-control" placeholder="Anota antecedents" id="antecedentsTextarea"></textarea>
            <label for="antecedentsTextarea">Antecedents</label>
        </div>

        <div v-show="newInterlocutor" class="form-check form-switch form-check-reverse my-4" id="save-interlocutor-container">
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
    .name-label-container {
        width: 60%;
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

    #save-interlocutor-container {
        height: 40px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    #saveInterlocutor {
        margin-left: 18px;
        width: 40px;
        height: 20px;
    }
</style>