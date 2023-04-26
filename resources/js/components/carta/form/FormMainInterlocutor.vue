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
                <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="What is your first name?"></i>
            </div>
            <div class="col-8">
                <div class="form-floating mb-3">
                    <input v-model="surnames.input" type="text" class="form-control is-invalid" id="surnames" placeholder="Cognoms" autocomplete="off" ref="surnamesInput">
                    <label for="surnames">Cognoms</label>
                </div>
                <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="What is your last name?"></i>
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
        'interlocutor-is-loaded',
        'interlocutor-error'
    ],
    props: {
        showHelp: {
            type: Boolean
        },
    },
    data() {
        return {
            isLoaded: false,
            isNewInterlocutor: true,
            saveInterlocutor: false,
            id: null,
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
        }
    },
    methods: {
        /**
         * Gets the cookie with the phone input data
        
         * If the phone number was typed at the menu, it checks if it exists in the db
        
         * If no phone number was typed it generates a random one
         */
        async getInterlocutorCookie () {
            const interlocutorCookie = this.getCookie('interlocutor_phone')
            if ( interlocutorCookie.isManual && interlocutorCookie.phone ) {
                await this.checkInterlocutor(interlocutorCookie.phone)
            } else {
                await this.generateNumber()
            }
            this.interlocutorIsLoaded()
        },
        /**
         * Gets the cookie generated in the controller with the data:
         
         * isManual true | false -> indicates if the user checked the manual checkbox (true) or automated (false)
         */
        getCookie(name) {
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith(name)) {
                    return JSON.parse(decodeURIComponent(cookie.substring(name.length + 1)));
                }
            }
        },
        /**
         * Checks in the database for interlocutor with same phone as user input
         
         * If it finds the interlocutor laods it to the component
         * If not sets the phone the user inputed
         */
        async checkInterlocutor(phone) {
            const self = this
            axios.get(`interlocutorCheck/${phone}`)
                    .then(response => {
                        if (response.data.match) {
                            self.loadInterlocutor(response.data.interlocutor)
                        } else {
                            self.phone = phone
                        }
                        self.updateCartaData()
                    })
                    .catch((error) => {
                        self.showError(error)
                        console.log(error)
                    })
        },
        /**
         * Gets a random phone number
         
         * 5% chance to load an existing phone number and all the interlocutor data
         */
         async generateNumber () {
            const self = this
            axios.get( 'interlocutorGenerate' )
                  .then(response => {
                    if ( !response.data.match ) {
                        self.phone = response.data.phone
                    } else {
                        self.loadInterlocutor( response.data.interlocutor )
                    }
                    self.updateCartaData()
                  })
                  .catch( (error) => {
                    self.showError(error)
                    console.log( error )
                  })
        },
        /**
         * Loads all interlocutor data to the component and sets newinterlocutor = false
         
         * Calls handleInput to validate the data and update it to the main component -> FormMain -> CartaTrucada
         */
        loadInterlocutor (interlocutor) {
            this.id = interlocutor.id
            this.phone = interlocutor.telefon
            this.name.input = interlocutor.nom
            this.surnames.input = interlocutor.cognoms
            this.record = interlocutor.antecedents
            this.isNewInterlocutor = false
            this.handleInput(this.$refs.nameInput)
            this.handleInput(this.$refs.surnamesInput)
        },
        interlocutorIsLoaded() {
            this.isLoaded = true
            this.$emit('interlocutor-is-loaded', this.isLoaded)
        },
        /**
         * Updates the object that is sended to the father component with this component data values
         */
        updateCartaData () {
           const cartaInterlocutor = {
                id: this.id,
                telefon: this.phone,
                antecedents: this.record,
                nom: this.name.input,
                cognom: this.surnames.input,
                isValid: this.name.isValid && this.surnames.isValid,
                isNewInerlocutor: this.isNewInterlocutor,
                saveInterlocutor: this.saveInterlocutor
            };
            
            this.$emit('get-interlocutor', cartaInterlocutor)
        },
        /**
         * Removes all validation calsses when focusing an input to improve visability
         */
        removeValidationClasses(el) {
            el.classList.remove('is-valid', 'is-invalid');
        },
        /**
         * Validate the elements input and upadtes this component data to father
         */
        handleInput (el) {
            this.validateInput(el)
            this.updateCartaData()
        },
        /**
         * Simple input validation that sets valid the input if is not empty
         */
        validateInput (el) {
            if (el === this.$refs.nameInput || el === this.$refs.surnamesInput) {
                this[el.id].isValid = this[el.id].input != '' ? true : false;
                el.classList.toggle('is-valid', this[el.id].isValid)
                el.classList.toggle('is-invalid', !this[el.id].isValid)
            }
        },
        showError (error) {
            this.$emit('interlocutor-error', error)
        }
    },
    mounted() {
        this.getInterlocutorCookie()
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
        top: -10px;
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