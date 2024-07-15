<template>
  <div>
    <input v-model="searchQuery" placeholder="Search Pokémon by name..." type="text" />
    <ul>
      <li v-for="(pokemon, index) in visiblePokemons" :key="pokemon.name">
        <PokemonSolo :pokemon="pokemon" :isSelected="index === 0" />
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, inject, onUnmounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import PokemonSolo from "@/components/PokemonSolo.vue";

const pokemons = ref([]);
const currentIndex = ref(0);
const displayCount = 5;
const searchQuery = ref('');
const router = useRouter();
const allDataLoaded = ref(false);

const filteredPokemons = computed(() => {
  if (searchQuery.value) {
    return pokemons.value.filter(pokemon =>
      pokemon.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }
  return pokemons.value;
});

const visiblePokemons = computed(() => {
  return filteredPokemons.value.slice(currentIndex.value, currentIndex.value + displayCount);
});

const EventBus = inject('EventBus');

EventBus.on('navigate', (direction) => {
  const maxIndex = filteredPokemons.value.length - displayCount;
  if (direction === 'up' && currentIndex.value > 0) {
    currentIndex.value--;
  } else if (direction === 'down' && currentIndex.value < maxIndex) {
    currentIndex.value++;
  }
});

EventBus.on('confirm', () => {
  const selectedPokemon = visiblePokemons.value[0];  // Premier élément actuellement sélectionné
  if (selectedPokemon) {
    const parts = selectedPokemon.url.split('/');
    const pokemonId = parts[parts.length - 2];
    localStorage.setItem('currentIndex', currentIndex.value.toString());
    router.push(`/pokemon/${pokemonId}`);
  }
});

async function loadAllPokemons() {
  let page = 1;
  while (!allDataLoaded.value) {
    const response = await fetch(`http://localhost/pokemon?page=${page}`);
    const data = await response.json();
    if (data.results.length === 0) {
      allDataLoaded.value = true;
      break;
    }
    pokemons.value.push(...data.results);
    page++;
  }
}

watch(searchQuery, (newValue) => {
  if (newValue && newValue.length > 2 && !allDataLoaded.value) {
    loadAllPokemons();
  }
});

onMounted(() => {
  if (pokemons.value.length === 0) loadAllPokemons();  // Load initially if not loaded
});

</script>

<style scoped>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
}

li {
  width: 75%;
}

input[type="text"] {
  width: 90%; /* Ajustez selon la largeur désirée */
  padding: 8px 10px;
  margin: 10px auto;
  display: block;
  border-radius: 12px; /* Arrondit les coins */
  border: 2px solid #ccc; /* Couleur de bordure légère */
  background-color: #f8f8f8; /* Couleur de fond légère pour le contraste */
  color: #333; /* Couleur du texte */
  font-size: 16px; /* Taille de police appropriée */
  transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out; /* Transition douce */
}

input[type="text"]:focus {
  border-color: #0075be;
  box-shadow: 0 0 8px rgba(0, 117, 190, 0.5);
  outline: none;
}

input[type="text"] {
  font-family: "Pixelify Sans", sans-serif;
  -moz-user-select: none; /* Firefox */
  -webkit-user-select: none; /* Chrome, Safari, Opéra depuis la version 15 */
  -ms-user-select: none; /* Internet explorer depuis la version 10 et Edge */
  user-select: none;
}
</style>
