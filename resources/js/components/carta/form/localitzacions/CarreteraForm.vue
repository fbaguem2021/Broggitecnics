<template>
  <form @input="emitDescription">
    <div class="row">
      <div class="col-4">
        <div class="form-floating mb-3">
          <input v-model="name" type="text" class="form-control" id="nomCarretera" placeholder="Nom de la carretera" autocomplete="off">
          <label for="nomCarretera">Nom</label>
        </div>
      </div>
      <div class="col-4">
        <div class="form-floating mb-3">
          <input v-model="km" type="text" class="form-control" id="kmCarretera" placeholder="Kilometre" autocomplete="off">
          <label for="kmCarretera">Kilòmetre</label>
        </div>
      </div>
      <div class="col-4">
        <div class="form-floating mb-3">
          <input v-model="direction" @input="emitDescription" type="text" class="form-control" id="dirCarretera" placeholder="Direcció" autocomplete="off">
          <label for="dirCarretera">Direcció</label>
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
      km: '',
      direction: ''
    }
  },
  methods: {
    emitDescription() {
      this.$emit('update-description', `${this.name} km${this.km} direcció ${this.direction}`)
    }
  },
}
</script>
<style scoped>
  
</style>