<template>
  <div style="display: flex; width: 148px">
    <h5>{{props.base}}</h5>
    <div class="stat-container">
      <div class="stat-bar" :style="{ width: fillWidth }"></div>
    </div>
  </div>

</template>

<script setup>
import {computed} from 'vue';

const props = defineProps({
  base: Number,
  statType: String // Utilisation de 'statType' au lieu de 'isHP'
});

const max = computed(() => {
  // Calcul du maximum basé sur si c'est HP ou un autre stat
  return props.statType === "hp"
    ? (2 * props.base + 31 + (252 / 4)) * 1 + 110
    : (2 * props.base + 31 + (252 / 4)) * 1 + 5;
});

const fillWidth = computed(() => {
  const fillPercentage = (props.base / max.value) * 100;
  return `${fillPercentage}%`;
});
</script>

<style scoped>
.stat-container {
  width: 100%;
  background-color: #eee;
  border: 1px solid #ccc;
  height: 10px;
  border-radius: 20px;
  overflow: hidden;
}

.stat-bar {
  background-color: var(--blue);
  height: 100%;
  transition: width 0.3s ease-in-out;
}

h5 {
  margin: 0 5px;
}
</style>
