import axios from 'axios'

// EndPoint API

var instance = axios.create({
  baseURL: 'http://krunal-eurosport.dev.aecortech.com/api/',
  timeout: 50000
})


export default instance
