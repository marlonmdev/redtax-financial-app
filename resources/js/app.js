import './bootstrap';
import './functions';

import axios from 'axios';
import Alpine from 'alpinejs';

axios.defaults.baseURl = 'http://redtaxfinancialservices.test';

axios.interceptors.request.use((config) => {
    config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    return config;
});

window.Alpine = Alpine;
window.axios = axios;

Alpine.start();
