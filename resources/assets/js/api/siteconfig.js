import axios from 'axios'

// EndPoint API

var instance = axios.create({
  baseURL: 'http://esr.aecordigitalqa.com/api/',
  timeout: 50000
})


export default instance
