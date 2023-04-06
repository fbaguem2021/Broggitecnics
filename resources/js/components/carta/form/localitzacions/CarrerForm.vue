<template>
  <form @input.prevent="emitDescription">
    <div class="row">
      <div class="col">
        <div class="form-floating mb-3">
          <input v-model="name" type="text" class="form-control" id="nomCarrer" placeholder="Nom del carrer" autocomplete="off">
          <label for="nomCarrer">Nom</label>
        </div>
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
export default {
  emits: ["update-description"],
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
  }
}
</script>
<style scoped>
  
</style>