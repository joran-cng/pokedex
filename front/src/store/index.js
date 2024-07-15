import { createStore } from 'vuex'

const store = createStore({
  state() {
    return {
      captures: {}
    }
  },
  mutations: {
    toggleCapture(state, pokemonId) {
      if (state.captures[pokemonId]) {
        delete state.captures[pokemonId];
      } else {
        state.captures[pokemonId] = true;
      }
    }
  },
  actions: {
    toggleCapture({ commit }, pokemonId) {
      commit('toggleCapture', pokemonId);
    }
  },
  getters: {
    isCaptured: (state) => (pokemonId) => {
      return !!state.captures[pokemonId];
    }
  }
})

export default store
