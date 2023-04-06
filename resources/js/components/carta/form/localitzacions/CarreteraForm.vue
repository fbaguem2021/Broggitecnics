<template>
<div class="row">
      <div class="col-6">
        <div class="form-floating mb-3">
          <input v-model="name" @input="emitDescription" type="text" class="form-control" id="nomCarrer" placeholder="Nom del carrer" autocomplete="off">
          <label for="nomCarrer">NOM CARRETERAAAA</label>
        </div>
      </div>
      <div class="col-6">
        <div class="form-floating mb-3">
          <input v-model="km" @input="emitDescription" type="text" class="form-control" id="nomCarrer" placeholder="Nom del carrer" autocomplete="off">
          <label for="nomCarrer">Kilometre</label>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  emits: ["update-description"],
  data() {
    return {
      name: '',
      km: ''
    }
  },
  methods: {
    emitDescription() {
      this.$emit('update-description', `${this.name} km${this.km}`)
    }
  },
}
</script>
<style scoped>
  
</style>