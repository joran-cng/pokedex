<template>
  <div class="screen">
      <RouterView></RouterView>
  </div>
</template>

<script setup>

</script>

<style scoped>
.screen {
  background-color: var(--screen);
  width: 90%;
  height: 300px;
  margin: 20px auto;
  position: relative;
  border-radius: 16px;
  border: 3px solid var(--btntext);
  font-family: "Pixelify Sans", sans-serif;
}

</style>
