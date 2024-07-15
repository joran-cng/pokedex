<template>
  <div class="pokedex">
    <screen></screen>
    <navigation-buttons @navigate="handleNavigation" @confirm="handleConfirm"></navigation-buttons>
  </div>
</template>

<script setup>
import { inject } from 'vue';
import Screen from './Screen.vue';
import NavigationButtons from './NavigationButtons.vue';

const EventBus = inject('EventBus');

function handleNavigation(direction) {
  EventBus.emit('navigate', direction);
}

function handleConfirm() {
  EventBus.emit('confirm');
}
</script>

<style scoped>
.pokedex {
  background-color: var(--red);
  width: 500px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  border-radius: 20px;
  border: 5px solid var(--btntext);
  padding-bottom: 50px;
  box-shadow: 20px 20px 50px rgba(0, 0, 0, 0.5);
}
</style>
