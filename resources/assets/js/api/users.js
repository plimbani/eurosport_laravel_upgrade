import api from './siteconfig'

export default {
  getUserDetails(userData) {
  	return api.post('user/getDetails',{'userData':userData})
  },
  getEditUser(id) {
  	return api.get('user/edit/'+id)
  },
 getRoles() {
    return api.get('roles-for-select')
  },
  getUsersByRegisterType(param) {
    return api.post('users/getUsersByRegisterType',param)
  },
  createUser(formData) {
    return api.post('user/create',formData)
  },
  updateUser(userId,formData) {
    return api.post('user/update/'+userId,formData)
  },
  resendEmail(emailData) {
    return api.post('user/resendEmail',emailData)
  },
  changeStatus(userData) {
    return api.post('user/status',userData)
  },
  deleteUser(deleteUrl) {
    return api.post(deleteUrl)
  }

}
