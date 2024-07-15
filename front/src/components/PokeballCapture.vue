<template>
  <div class="pokeball" @click="toggleCapture">
    <img :src="isCaptured ? '/img/pokeballRemplie.png' : '/img/pokeballVide.png'"
         :style="rotationStyle"
         class="pokeball-image">
  </div>
</template>

<script setup>
import {computed, ref, watchEffect} from 'vue';
import axios from 'axios';

const props = defineProps({
  pokemonId: Number
});

const isCaptured = ref(false);
const cumulativeRotation = ref(0);

async function checkCaptureStatus() {
  if (props.pokemonId) {
    try {
      const response = await axios.get(`http://localhost:80/pokemon/${props.pokemonId}/checkCapture`);
      isCaptured.value = response.data.isCaptured;
    } catch (error) {
      console.error('Failed to check capture status:', error);
    }
  }
}

async function toggleCapture() {
  if (props.pokemonId) {
    cumulativeRotation.value += 360;
    try {
      const response = await axios.post(`http://localhost:80/pokemon/${props.pokemonId}/toggleCapture`);
      isCaptured.value = response.data.captures[props.pokemonId] !== undefined;
    } catch (error) {
      console.error('Failed to toggle capture status:', error);
    }
  }
}

const rotationStyle = computed(() => ({
  transform: `rotate(${cumulativeRotation.value}deg)`,
  transition: 'transform 0.6s ease'
}));

watchEffect(checkCaptureStatus);
</script>

<style scoped>
.pokeball {
  width: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.pokeball-image {
  width: 100%;
  height: auto;
}
</style>
