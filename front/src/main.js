import { createApp, reactive } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './style.css';
import VueFeather from 'vue-feather';

const EventBus = reactive({
  events: {},
  emit(event, data) {
    if (this.events[event]) {
      this.events[event].forEach(callback => callback(data));
    }
  },
  on(event, callback) {
    if (!this.events[event]) {
      this.events[event] = [];
    }
    this.events[event].push(callback);
  }
});

const app = createApp(App)
app.component(VueFeather.name, VueFeather);
app.use(router)
app.use(store)
app.provide('EventBus', EventBus);
app.mount('#app')
