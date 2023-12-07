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
  getRolesWithData() {
    return api.get('roles')
  },
  getUsersByRegisterType(param) {
    return api.post('users/getUsersByRegisterType',param)
  },

  getUserTableData(param) {
    //return api.get('users/getUserTableData?report_download=yes&registerType=desktop')
    return api.get('users/getUserTableData?' + param)
  },

  // getUserReportData() {
  //     return api.get('users/getUsersByRegisterType/generate?'+ data)
  // },

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
  },
  changeTournamentPermission(permissionData) {
    return api.post('user/changeTournamentPermission', permissionData)
  },
  changePermissions(permissionData) {
    return api.post('user/changePermissions', permissionData)
  },
  getUserTournaments(userId) {
    return api.get('user/getUserTournaments/' + userId)
  },
  getSignedUrlForUsersTableData(userData) {
    return api.post('getSignedUrlForUsersTableData?' +userData);
  },
  getAllCountries() {
    return api.get('getCountries');
  },
  getAllLanguages() {
    return api.get('getAllLanguages');
  },
  validateUserEmail(formData) {
    return api.post('user/validateemail',formData)
  },
  verifyResultAdminUser(data) {
    return api.post('user/verifyResultAdminUser', data);
  }
}
