<template>
  <div class="form-floating">
    <textarea class="form-control" placeholder="Referencies" id="notaComunaTextarea"></textarea>
    <label for="notaComunaTextarea">Nota comuna</label>
  </div>
</template>
<script>
export default {
  
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
</style>