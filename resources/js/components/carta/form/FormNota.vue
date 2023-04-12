<template>
  <form @input="handleInput($event.target)">
    <div class="form-floating">
      <textarea v-model="notaCoumna.input" class="form-control is-invalid" placeholder="Referencies" id="notaComunaTextarea"></textarea>
      <label for="notaComunaTextarea">Nota comuna<span id="label-referencia">-- Relacio interlocuto-incident / Telèfon de contacte / Descripció dels fets </span></label>
    </div>
  </form>
</template>
<script>
export default {
  emits: [
    'get-notaComuna'
  ],
  data() {
    return {
      notaCoumna: {
        input: '',
        isValid: false
      }
    }
  },
  methods: {
    handleInput (el) {
            this.validateInput(el)
            this.updateCartaData()
    },
    validateInput (el) {
      this.notaCoumna.isValid = el.value != '' ? true : false;
      el.classList.toggle('is-valid', this.notaCoumna.isValid)
      el.classList.toggle('is-invalid', !this.notaCoumna.isValid)

    },
    updateCartaData () {
      this.$emit('get-notaComuna', this.notaCoumna)
    }
  }
}
</script>
<style scoped>
  #notaComunaTextarea {
    height: 10vh;
    transition: height .4s ease-in-out;
    resize: none;
  }
  #form-nota.expanded #notaComunaTextarea {
    height: 30vh;
    max-height: 30vh;
  }
  #notaComunaTextarea ~ label {
    width: 96%;
  }
  #label-referencia {
    padding-left: 10px;
    font-size: 14px;
    opacity: .8;
  }
</style>