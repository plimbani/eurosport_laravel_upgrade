import api from './siteconfig'

export default {  
  getUserDetails(userData) {  	
  	return api.post('user/getDetails',{'userData':userData})
  },
  getEditUser(id) {
  	return api.get('user/edit/'+id)
  }
}