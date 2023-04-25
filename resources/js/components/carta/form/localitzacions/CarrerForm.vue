<template>
  <form @input.prevent="emitDescription">
    <div class="row">
      <div class="col">
        <div class="form-floating mb-3">
          <input v-model="name" type="text" class="form-control" id="nomCarrer" placeholder="Nom del carrer" autocomplete="off">
          <label for="nomCarrer">Nom</label>
        </div>
      <i v-show="showHelp" class="bi bi-chat-left-dots" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="What is the name of the street?"></i>
      </div>
    </div>
    <div class="row">
      <div class="col-3">
        <div class="form-floating mb-3">
          <input v-model="number" type="text" class="form-control" id="numCarrer" placeholder="Número" autocomplete="off">
          <label for="numCarrer">Número</label>
        </div>
      </div>
      <div class="col-3">
        <div class="form-floating mb-3">
          <input v-model="stairway" type="text" class="form-control" id="escalaCarrer" placeholder="Escala" autocomplete="off">
          <label for="escalaCarrer">Escala</label>
        </div>
      </div>
      <div class="col-3">
        <div class="form-floating mb-3">
          <input v-model="floor" type="text" class="form-control" id="pisCarrer" placeholder="Pis" autocomplete="off">
          <label for="pisCarrer">Pis</label>
        </div>
      </div>
      <div class="col-3">
        <div class="form-floating mb-3">
          <input v-model="door" type="text" class="form-control" id="portaPis" placeholder="Porta" autocomplete="off">
          <label for="portaPis">Porta</label>
        </div>
      </div>
    </div>
  </form>
</template>
<script>
import * as bootstrap from 'bootstrap';
export default {
  emits: ["update-description"],
  props: {
    showHelp: {
      type: Boolean
    }
  },
  data() {
    return {
      name: '',
      number: '',
      stairway: '',
      floor: '',
      door: '',
    }
  },
  methods: {
    emitDescription() {
      const streetArray = [this.name, this.number, this.stairway, this.floor, this.door].filter(Boolean);
      this.$emit('update-description', streetArray.join(' '))
    }
  },
  mounted() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  }
}
</script>
<style scoped>
  
</style>