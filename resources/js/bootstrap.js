import axios from 'axios';
import { auth } from './utility/auth.js';

window.axios = axios;

window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Authorization'] = `Bearer ${auth.getToken()}`;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
