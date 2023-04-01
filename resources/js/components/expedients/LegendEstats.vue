<template>
    <div v-for="(estat, index) in estatsUppercase()" :key="index" class="block">
        <div class="block-container" @click="sendData(estat.id)">
            <div class="title">
                {{ estat.estat }}
            </div>
            <div class="data">
                <div class="color-container" :class="'estat-' + estat.id"></div>
                <span class="count"><!-- {{estat.count}} -->100</span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
  props: {
    estats: {
      type: Array,
      required: true
    }
  },
  data () {
    return {

    }
  },
  mounted () {

  },
  methods: {
    estatsUppercase () {
      const upperCaseStatus = this.estats.map(estat => ({
        ...estat,
        estat: estat.estat.toUpperCase()
      }))
      return upperCaseStatus;
    },
    sendData (estatID) {
      this.$emit('estat', estatID);
    }
  }
}

</script>
<style lang="css">
.block {
    width: 200px;
    height: fit-content;
    margin: 0 10px;
    background-color: #F8F8F8;
    border-radius: 4px;
}

.block-container {
    display: flex;
    align-items: center;
    position: relative;
    height: 100%;
    padding: 2px 10px 2px 6px;
}

.block-container .title {
    font-size: 16px;
}

.block-container:hover {
    cursor: pointer;
}

.block-container .data {
    display: flex;
    align-items: center;
    margin-left: auto;
}

.block-container .color-container {
    height: 22px;
    width: 22px;
    border-radius: 4px;
}

.count {
    font-size: 18px;
    font-weight: bold;
    margin-left: 14px;
}

/* PROCES */
.estat-1 {
    background-color: #24DB4A;
}

/* SOLICITAT */
.estat-2 {
    background-color: #E9E241;
}

/* ACCEPTAT */
.estat-3 {
    background-color: #889D35;
}

/* TANCAT */
.estat-4 {
    background-color: #47B2FF;
}

/* IMMOBILITZAT */
.estat-5 {
    background-color: #8924D9;
    color: white;
}</style>