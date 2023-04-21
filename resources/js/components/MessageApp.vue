<template>
        <svg style="display: none;">
            <symbol id="success" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z">
                </path>
            </symbol>
            <symbol id="info" viewBox="0 0 16 16">
                <path
                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z">
                </path>
            </symbol>
            <symbol id="warning" viewBox="0 0 16 16">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                </path>
            </symbol>
            <symbol id="danger" viewBox="0 0 16 16">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                </path>
            </symbol>
        </svg>
        <div class="alert-container">
            <div v-if="errorAlert.display" ref="errorAlert" class="alert alert-dismissible fade" role="alert"
                :class=" 'alert-'+errorAlert.type">
                <svg id="alert-icon" class="bi flex-shrink-0 me-2" role="img"><use :xlink:href=" '#'+errorAlert.type "/></svg>
                {{ errorAlert.message }}
                <div v-if="errorAlert.data">
                    <hr>
                    <span class="mb-0">{{ errorAlert.data }}</span>
                </div>
                <button type="button" class="btn-close" aria-label="Close" @click="closeErrorAlert"></button>
            </div>
            <div v-if="messageAlert.display" ref="messageAlert" class="alert alert-dismissible fade" role="alert"
                :class=" 'alert-'+messageAlert.type">
                <svg id="alert-icon" class="bi flex-shrink-0 me-2" role="img"><use :xlink:href=" '#'+messageAlert.type "/></svg>
                {{ messageAlert.message }}
                <div v-if="messageAlert.data">
                    <hr>
                    <span class="mb-0">{{ messageAlert.data }}</span>
                </div>
                <button type="button" class="btn-close" aria-label="Close" @click="closeMessageAlert"></button>
            </div>
        </div>
</template>
<script>
export default {
    data() {
        return {
            errorAlert: {
                display: false,
                message: '',
                type: 'danger',
                data: ''
            },
            messageAlert: {
                display: false,
                message: '',
                type: '',
                data: ''
            }
            
        }
    },
    methods: {
        createAlert(message, type, data = null) {
            this.messageAlert.message = message
            this.messageAlert.type = type
            this.messageAlert.data = data
            this.showMessageAlert()
        },
        createErrorAlert(error) {
            console.log(error)
            if (error.response) {
                const status = error.response.status
                switch (status) {
                    case 400:
                        this.errorAlert.message = 'Petició incorrecta';
                        break;
                    case 401:
                        this.errorAlert.message = 'No autoritzat';
                        break;
                    case 403:
                        this.errorAlert.message = 'Prohibit el accès';
                        break;
                    case 404:
                        this.errorAlert.message = 'Recurs no trobat';
                        break;
                    case 500:
                        this.errorAlert.message = "Error intern del servidor - Posis en contacte amb un adminsitrador de l'aplicació";
                        if (error.response.data.message.startsWith("SQLSTATE")) {
                            const errorType = "SQLSTATE"
                            const errorCode = error.response.data.message.split("[")[2].split("]")[0];
                            this.errorAlert.data = `${errorType} Codi d'error: ${errorCode}`
                        }
                        break;
                    default:
                        this.errorAlert.message = 'Error';
                }
            } else if (error.request) {
                this.errorAlert.message = "No s'ha rebut cap resposta"
            } else {
                this.errorAlert.message = "S'ha produït un error"
                this.errorAlert.data = error.message;
            }
            this.showErrorAlert()
        },
        showErrorAlert () {
            this.errorAlert.display = true;
            setTimeout(()=>{
                this.$refs.errorAlert.classList.add('show')      
            }, 100)
            // setTimeout(()=>{this.closeAlert()}, 10000)
        },
        showMessageAlert () {
            this.messageAlert.display = true;
            setTimeout(()=>{
                this.$refs.messageAlert.classList.add('show')      
            }, 100)
            // setTimeout(()=>{this.closeAlert()}, 10000)
        },
        closeErrorAlert () {
            this.$refs.errorAlert.classList.remove('show')
            setTimeout(()=>{
                this.$refs.errorAlert.style.display = 'none';
                this.errorAlert.display = false
            }, 100)
        },
        closeMessageAlert () {
            this.$refs.messageAlert.classList.remove('show')
            setTimeout(()=>{
                this.$refs.messageAlert.style.display = 'none';
                this.messageAlert.display = false
            }, 100)
        }
    },
}
</script>
<style lang="css" scoped>
.alert-container {
    position: absolute;
    top: 0;
    display: flex;
    flex-direction: column;
    width: 50%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}
.alert {
    border-radius: 0 0 6px 6px;
    border-width: 1px;
    border-style: solid;
    margin: 0;
}
#alert-icon {
    width: 20px;
    height: 20px;
}
.alert-info {
    color: #052c65;
    background-color: #cfe2ff;
    border-color: #9ec5fe;
}
.alert-success {
    color: #0a3622;
    background-color: #d1e7dd;
    border-color: #a3cfbb;
}
.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
    border-color: #ffe69c;
}
.alert-danger {
    color: #58151c;
    background-color: #f8d7da;
    border-color: #f1aeb5;
}
</style>