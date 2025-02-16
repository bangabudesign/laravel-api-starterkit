import './bootstrap';
import {createApp} from 'vue'

import App from './layouts/App.vue';
import Router from './router/index.js';

createApp(App)
    .use(Router)
    .mount("#app")
