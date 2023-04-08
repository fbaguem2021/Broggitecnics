<template>
    <form id="interlocutor-form" @input.prevent="emitInterlocutor">    
        <div class="row">
            <div class="col-4">
                <div class="form-floating mb-3" id="phone-input-container">
                    <input v-model="phone" type="phone" class="form-control" id="phone" placeholder="Telèfon" autocomplete="off">
                    <label for="phone">Telèfon</label>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-4">
                <div class="form-floating mb-3">
                    <input v-model="name" @focus="removeValidationClasses($event.target.classList)" @focusout="validateInput($event.target)" type="text" class="form-control is-invalid" id="name" placeholder="Nom" autocomplete="off">
                    <label for="name">Nom</label>
                </div>
            </div>
            <div class="col-8">
                <div class="form-floating mb-3">
                    <input v-model="surnames" type="text" class="form-control is-invalid" id="surnames" placeholder="Cognoms" autocomplete="off">
                    <label for="surnames">Cognoms</label>
                </div>
            </div>
        </div>
       

        <div class="form-floating" id="antecedents-textArea-container">
            <textarea v-model="record" class="form-control" placeholder="Anota antecedents" id="antecedentsTextarea"></textarea>
            <label for="antecedentsTextarea">Antecedents</label>
        </div>

        <div class="form-check form-switch form-check-reverse my-4" id="save-interlocutor-container">
            <label class="form-check-label" for="saveInterlocutor">Guardar interlocutor</label>
            <input v-model="saveInterlocutor" @change="emitSaveInterlocutor" class="form-check-input" type="checkbox" role="switch" id="saveInterlocutor" tabindex="5" @focusout="nextForm">
        </div>
    </form>
</template>
<script>
export default {
    emits: [
        'is-new-interlocutor',
        'save-interlocutor',
        'carta-interlocutor',
    ],
    data() {
        return {
            isNewInterlocutor: true,
            saveInterlocutor: false,
            phone: '',
            name: '',
            surnames: '',
            record: '',
            cartaInterlocutor: {
                telefon: '',
                antecedents: '',
                nom: '',
                cognom: ''
            }
        }
    },
    methods: {
        nextForm () {
            console.log("To the next form i say!")
        },
        removeValidationClasses(classList) {
            classList.remove('is-valid', 'is-invalid');
        },
        validateInput (input) {
            if (input.value != '') {
                input.classList.add('is-valid')
                input.classList.remove('is-invalid')
            } else {
                input.classList.add('is-invalid')
                input.classList.remove('is-valid')
            }
        },
        emitInterlocutor () {     
            this.$emit('carta-interlocutor', this.cartaInterlocutor)
        },
        emitSaveInterlocutor() {
            console.log('FORM MAIN INTERLOCUTOR: input detected\nValue of saveInterlocutor')
            console.log(this.saveInterlocutor)
            this.$emit('save-interlocutor', this.saveInterlocutor)
        },
        emitIsNewInterlocutor () {
            this.$emit('is-new-interlocutor', this.isNewInterlocutor)
        }
    },
    mounted() {

        // DEBUGGING DEV DEV DEV
        this.emitIsNewInterlocutor

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