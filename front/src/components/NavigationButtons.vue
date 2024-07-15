<template>
  <div class="navigation-buttons">
    <div class="dpad">
      <div class="up-container" @click="navigate('up')">
        <div id="up">
          <vue-feather type="triangle" stroke="var(--btntext)" fill="var(--btntext)" size="33"></vue-feather>
        </div>
      </div>
      <div class="sides-container">
        <div id="left">
          <vue-feather type="triangle" stroke="var(--btntext)" fill="var(--btntext)" size="33"
                       style="transform: rotate(-90deg);"></vue-feather>
        </div>
        <div id="middle">
          <vue-feather type="circle" stroke="var(--btntext)" fill="var(--btntext)" size="33"></vue-feather>
        </div>
        <div id="right">
          <vue-feather type="triangle" stroke="var(--btntext)" fill="var(--btntext)" size="33"
                       style="transform: rotate(90deg);"></vue-feather>
        </div>
      </div>
      <div class="down-container" @click="navigate('down')">
        <div id="down">
          <vue-feather type="triangle" stroke="var(--btntext)" fill="var(--btntext)" size="33"
                       style="transform: rotate(180deg);"></vue-feather>
        </div>
      </div>
    </div>

    <div class="buttons">
      <div class="confirm" @click="confirmAction">A</div>
      <div class="cancel" @click="router.go(-1)">B</div>
      <div class="home" @click="goHome">
        <vue-feather type="home" stroke="var(--btntext)" size="33"></vue-feather>
      </div>
    </div>
  </div>
</template>

<script setup>
import {useRouter} from 'vue-router';
import { defineEmits } from 'vue';

const router = useRouter();
const emit = defineEmits(['navigate', 'confirm']);

function navigate(direction) {
  emit('navigate', direction);
}

function confirmAction() {
  emit('confirm');
}

function cancelAction() {
  // Annuler ou retour à l'écran précédent
  console.log('Action canceled');
}

function goHome() {
  router.push('/');
}
</script>

<style scoped>
.navigation-buttons {
  display: flex;
  justify-content: space-around;
}

.dpad {
  width: 189px;
  display: flex;
  flex-direction: column;
}

.buttons {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.dpad > div > div {
  width: 63px;
  height: 63px;
  background-color: var(--buttons);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);

}

.up-container, .down-container {
  width: 100%;
  display: flex;
  justify-content: center;
}

.sides-container {
  display: flex;
}

.confirm, .cancel, .home {
  border-radius: 100%;
  background-color: var(--buttons);
  width: 50px;
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: 800;
  font-size: 33px;
  color: var(--btntext);
  cursor: pointer;
  border: 2px solid var(--btntext);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
  -moz-user-select: none; /* Firefox */
  -webkit-user-select: none; /* Chrome, Safari, Opéra depuis la version 15 */
  -ms-user-select: none; /* Internet explorer depuis la version 10 et Edge */
  user-select: none; /* Propriété standard */
}

#up {
  border-radius: 10px 10px 0 0;
  border-top: 2px solid var(--btntext);
  border-right: 2px solid var(--btntext);
  border-left: 2px solid var(--btntext);
}

#left {
  border-radius: 10px 0 0 10px;
  border-top: 2px solid var(--btntext);
  border-bottom: 2px solid var(--btntext);
  border-left: 2px solid var(--btntext);
}

#middle {
  border: 2px solid var(--buttons);
}

#right {
  border-radius: 0 10px 10px 0;
  border-top: 2px solid var(--btntext);
  border-bottom: 2px solid var(--btntext);
  border-right: 2px solid var(--btntext);
}

#down {
  border-radius: 0 0 10px 10px;
  border-right: 2px solid var(--btntext);
  border-bottom: 2px solid var(--btntext);
  border-left: 2px solid var(--btntext);
}
</style>
