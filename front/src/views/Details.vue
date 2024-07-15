<template>
  <div class="details-bg">
    <div class="details">
      <div class="gauche">
        <div class="img-container" v-if="pokemonDetails.sprites && pokemonDetails.sprites.front_default">
          <img :src="pokemonDetails.sprites.front_default" @load="imageLoaded = true" :class="{ 'hidden': !imageLoaded }">
        </div>
        <div class="types">
          <p v-if="pokemonDetails.types" v-for="(typeInfo) in pokemonDetails.types" :key="typeInfo.type.name"
                :style="{'background-color': getStyleForType(typeInfo.type.name)}">
            {{ typeInfo.type.name }}
          </p>
        </div>
        <div class="infos">
          <p><span style="font-weight: 800">Taille :</span> {{pokemonDetails.height/10}} m</p>
          <p><span style="font-weight: 800">Poids :</span> {{pokemonDetails.weight/10}} kg</p>
        </div>
      </div>
      <div class="droite">
        <div style="display: flex; align-items: center;">
          <h2>{{ pokemonDetails.name }}</h2>
          <PokeballCapture :pokemon-id="pokemonDetails.id"></PokeballCapture>
        </div>

        <ul>
          <li v-for="(stat, index) in pokemonDetails.stats" :key="index">
            <p>{{ stat.stat.name }}:</p>
            <statistique :base="stat.base_stat" :statType="stat.stat.name" />
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import {useRoute} from 'vue-router';
import Statistique from "@/components/statistique.vue";
import PokeballCapture from "@/components/PokeballCapture.vue";

const route = useRoute();
const pokemonId = route.params.id;
const pokemonDetails = ref({});
const imageLoaded = ref(false);  // État pour suivre si l'image est chargée

async function fetchPokemonDetails() {
  const url = `http://localhost:80/pokemon/${pokemonId}`;
  try {
    const response = await fetch(url);
    const data = await response.json();
    pokemonDetails.value = data;
    console.log(pokemonDetails.value)
    imageLoaded.value = false;
  } catch (error) {
    console.error('Failed to load pokemon details:', error);
  }
}

function getStyleForType(typeName) {
  return `var(--${typeName})`;
}

onMounted(fetchPokemonDetails);
</script>

<style scoped>
.hidden {
  display: none;
}

.details-bg {
  background-color: var(--blue);
  height: 100%;
  border-radius: 13px;
  display: flex;
  align-items: center;
}

.details {
  background-color: #bdbec6;
  height: 80%;
  width: 100%;
  display: flex;
}

.gauche, .droite {
  width: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.img-container {
  width: 75%;
  height: 100px;
  overflow: hidden;
}

img {
  width: 100%; /* Assurez-vous que l'image remplit le conteneur */
  height: 100%;
  object-fit: cover; /* L'image couvre tout l'espace disponible, recadrée si nécessaire */
}

h2 {
  margin: 0;
  font-weight: 800;
}

.types {
  display: flex;
}

.types > p {
  border-radius: 50px;
  padding: 0 7px 1px 7px;
  margin: 0 3px;
  font-weight: 400;
  color: white;
  letter-spacing: 1px;
}

.infos{
  border: 2px solid black;
  border-radius: 3px;
  background-color: white;
  padding: 0 10px;
  margin: 20px 0 0 0;
}

.infos > p {
  margin: 5px 0;
}

ul{
  list-style-type: none;
  margin: 5px;
  padding: 0;
}

li {
  display: flex;
  align-items: center;
  text-align: start;
}

li > p {
  width: 67px;
  margin: 2px 0;
}

.pokeball {
  width: 30px;
  margin: 0 5px;
}
</style>
