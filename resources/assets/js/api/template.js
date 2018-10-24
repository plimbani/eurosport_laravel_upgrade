import api from './siteconfig'

export default {
	getTemplates() {
		return api.get('getTemplates')
	},
	getTemplateDetail(templateData) {
		return api.post('getTemplateDetail', {'templateData': templateData})
	}
}