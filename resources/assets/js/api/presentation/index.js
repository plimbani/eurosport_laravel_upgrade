import api from './siteconfig';

export default {
	getSignedUrlForMatchSchedulePrint(templateData) {
    	return api.post('getSignedUrlForMatchSchedulePrint', templateData);
  	},
}