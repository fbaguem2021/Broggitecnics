<template>
    <form id="interlocutor-form" 
        @input.prevent="handleInput($event.target)"
        @focusin=" removeValidationClasses($event.target)"
        @focusout="this.validateInput($event.target)">
        <div id="invalid-legend">
            <i class="bi bi-exclamation-circle me-2"></i><span>Els camps amb aquest icona son obligatoris</span>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-floating mb-3" id="phone-input-container">
                    <input v-model="phone" type="phone" class="form-control" id="phone" placeholder="Telèfon" autocomplete="off" disabled>
                    <label for="phone">Telèfon</label>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-4">
                <div class="form-floating mb-3">
                    <input v-model="name.input" type="text" class="form-control is-invalid" id="name" placeholder="Nom" autocomplete="off" autofocus ref="nameInput">
                    <label for="name">Nom</label>
                </div>
            </div>
            <div class="col-8">
                <div class="form-floating mb-3">
                    <input v-model="surnames.input" type="text" class="form-control is-invalid" id="surnames" placeholder="Cognoms" autocomplete="off" ref="surnamesInput">
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
            <input v-model="saveInterlocutor" @change="updateCartaData" class="form-check-input" type="checkbox" role="switch" id="saveInterlocutor" @focusout="nextForm">
        </div>
    </form>
</template>
<script>
import axios from 'axios'

export default {
    emits: [
        'get-interlocutor',
    ],
    data() {
        return {
            isNewInterlocutor: true,
            saveInterlocutor: false,
            phone: '',
            name: {
                input: '',
                isValid: false
            },
            surnames: {
                input: '',
                isValid: false
            },
            record: '',
            cartaInterlocutor: {
                telefon: '',
                antecedents: '',
                nom: '',
                cognom: '',
                isValid: false,
                isNewInerlocutor: this.isNewInerlocutor,
                saveInterlocutor: this.saveInterlocutor
            }
        }
    },
    methods: {
        checkInterlocutor() {
            const interlocutorCookie = this.getCookie('interlocutor_phone')
            if (interlocutorCookie.isManual && interlocutorCookie.phone) {
                const self = this
                axios.get(`interlocutorCheck/${interlocutorCookie.phone}`)
                        .then(response => {
                            // If interlocutor is found in db load it
                            if (response.data.match) {
                                self.setInterlocutor(response.data.interlocutor)
                            // if not set the phone number to the user home input
                            } else {
                                self.phone = interlocutorCookie.phone
                            }
                        })
                        .catch((error) => {
                            console.log()
                        })
            } else {
                this.generateNumber()
            }
        },
        setInterlocutor (interlocutor) {
            this.phone = interlocutor.telefon
            this.name.input = interlocutor.nom
            this.surnames.input = interlocutor.cognoms
            this.record = interlocutor.antecedents
            this.isNewInterlocutor = false
        },
        generateNumber () {
            const self = this
            axios.get('interlocutorGenerate')
                  .then(response => {
                    // self.phone = response.phone
                    console.log(response)
                  })
                  .catch((error) => {})
        },
        getCookie(name) {
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith(name)) {
                    return JSON.parse(decodeURIComponent(cookie.substring(name.length + 1)));
                } else {
                    return null
                }
            }
        },
        removeValidationClasses(el) {
            el.classList.remove('is-valid', 'is-invalid');
        },
        handleInput (el) {
            this.validateInput(el)
            this.updateCartaData()
        },
        validateInput (el) {
            if (el === this.$refs.nameInput || el === this.$refs.surnamesInput) {
                this[el.id].isValid = el.value != '' ? true : false;
                el.classList.toggle('is-valid', this[el.id].isValid)
                el.classList.toggle('is-invalid', !this[el.id].isValid)
            }
        },
        updateCartaData () {
           this.cartaInterlocutor = {
                telefon: this.phone,
                antecedents: this.record,
                nom: this.name.input,
                cognom: this.surnames.input,
                isValid: this.name.isValid && this.surnames.isValid,
                isNewInerlocutor: this.isNewInterlocutor,
                saveInterlocutor: this.saveInterlocutor
            };
            this.$emit('get-interlocutor', this.cartaInterlocutor)
        },
    },
    mounted() {
        this.checkInterlocutor()
        this.updateCartaData()
    },
}
</script>
<style scoped>
    #interlocutor-form {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    #invalid-legend {
        position: absolute;
        right: 0;
        top: 0;
    }
    #invalid-legend span {
        font-size: 14px;
        opacity: .8;
    }
    #invalid-legend i::before {
        font-size: 16px;
        color: #e21212;
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
        max-height: 35vh;
        resize: none;
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