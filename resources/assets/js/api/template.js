import api from './siteconfig'

export default {
	getTemplates(templateData) {
		return api.post('getTemplates', templateData)
	},
	getTemplateDetail(templateData) {
		return api.post('getTemplateDetail', {'templateData': templateData})
	},
	getUsersForFilter() {
		return api.get('templates/getUsersForFilter')
	},
	deleteTemplate(deleteUrl) {
		return api.post(deleteUrl)
	}
}