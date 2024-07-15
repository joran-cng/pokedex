<template>
  <div class="pokemon-solo" :class="{ selected: isSelected }">
    <PokeballCapture :pokemon-id="pokemonId" />
    <h3>{{ pokemon.name }}</h3>
    <p class="pokemon-id">{{ formattedId }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import PokeballCapture from "@/components/PokeballCapture.vue";

const props = defineProps({
  pokemon: {
    type: Object,
    required: true
  },
  isSelected: Boolean
});

const pokemonId = computed(() => {
  const match = props.pokemon.url.match(/\/pokemon\/(\d+)\/$/); // Assurez-vous que l'URL se termine par un slash pour cette regex
  return match ? match[1] : undefined; // Retourne l'ID ou undefined si l'ID n'est pas trouvé
});

const formattedId = computed(() => {
  return pokemonId.value ? `#${pokemonId.value.padStart(3, '0')}` : ''; // Formatte l'ID avec des zéros
});
</script>

<style scoped>
.pokemon-solo {
  padding: 3px;
  margin: 5px 0;
  background-color: #f0f0f0;
  border-radius: 8px;
  text-align: center;
  font-size: 18px;
  border: 1px solid transparent;
  display: flex;
  align-items: center;
  justify-content: space-between;
  -moz-user-select: none; /* Firefox */
  -webkit-user-select: none; /* Chrome, Safari, Opéra depuis la version 15 */
  -ms-user-select: none; /* Internet explorer depuis la version 10 et Edge */
  user-select: none;
}

.pokemon-solo.selected {
  border-color: blue; /* Bordure pour le Pokémon sélectionné */
}

.pokemon-id {
  font-weight: bold; /* Style pour ID */
}

.pokeball {
  width: 30px;
}

h3, p {
  margin: 0;
}
</style>
