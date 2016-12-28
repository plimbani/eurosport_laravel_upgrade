import Vue from 'vue';
import Axios from 'axios';

Axios.defaults.baseURL = process.env.API_LOCATION;
Axios.defaults.headers.common.Accept = 'application/json';
Axios.interceptors.response.use(
response => response);
Vue.$http = Axios;
