import axios from 'axios'
// import NProgress from 'nprogress'
// var NProgress = require("nprogress")
// var PulseLoader = require('vue-spinner/src/PulseLoader.vue');
// EndPoint API

var instance = axios.create({

  // baseURL: 'http://rishab-eurosport.dev.aecortech.com/api/',
  baseURL: 'http://esr.aecordigitalqa.com/api/',
  timeout: 50000
})
/*
instance.interceptors.request.use(function (config) {
   	NProgress.start();

  	return config
}, function (error) {

  return Promise.reject(error)
})
// Add a response interceptor
instance.interceptors.response.use(function (response) {

  NProgress.done(true);
  NProgress.remove();

  return response
}, function (error) {
  // Loading.hide()
  // alert('hi1233454345')
  return Promise.reject(error)
})
*/
export default instance
