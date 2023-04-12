<template>
  <form @input="emitDescription">
    <div class="row">
      <div class="col">
        <div class="form-floating mb-3">
          <input v-model="name" @input="updatePSName" type="text" class="form-control" id="nomPuntSingular" placeholder="Nom del punt singular" autocomplete="off">
          <label for="nomPuntSingular">Nom del punt singular</label>
        </div>
      </div>
    </div>
  </form>
</template>
<script>
export default {
  data() {
    return {
      name: ''
    }
  },
  methods: {
      emitDescription() {
      this.$emit('update-description', this.name)
    }
  }
}
</script>
<style lang="">
  
</style>