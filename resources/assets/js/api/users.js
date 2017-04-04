import api from './siteconfig'

export default {  
  getUserDetails(userData) {  	
  	return api.post('user/getDetails',{'userData':userData})
  }
}