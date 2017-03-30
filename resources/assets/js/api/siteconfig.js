import axios from 'axios'

// EndPoint API

var instance = axios.create({
  baseURL: 'http://kamal-eurosport.dev.aecortech.com/api/',
  timeout: 50000
})


export default instance
