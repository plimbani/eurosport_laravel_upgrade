import api from './siteconfig';

export default {
	// Get user's accessible websites
  getUserAccessibleWebsites() {
  	return axios.get('/api/getUserAccessibleWebsites');
  },
  // Get all websites
  getAllWebsites() {
  	return axios.get('/api/websites');
	},
  // Save website
  saveWebsite(websiteData) {
  	return axios.post('/api/saveWebsiteData', {'websiteData': websiteData});
  },
  // Get all website statistics
  getStatistics(websiteId) {
    return axios.get('/api/getStatistics/' + websiteId);
  },
  // Get all website organisers
  getOrganisers(websiteId) {
    return axios.get('/api/getOrganisers/' + websiteId);
  },
  // Get all website details
  websiteSummaryData(websiteId) {
    return api.post('websites/websiteSummary',{'websiteId':websiteId})
  },
  // Save home page data
  saveHomePageData(pageData) {
    return api.post('/saveHomePageData', pageData);
  },
  // Get website all colours
  getWebsiteCustomisationOptions() {
    return axios.get('/api/websites/customisation/options');
  },
  // Get image path
  getImagePath() {
    return axios.get('/api/getImagePath');
  },
  // Get website default pages
  getWebsiteDefaultPages() {
    return axios.get('/api/websites/getWebsiteDefaultPages');
  },
  // Get home page data
  getHomePageData(websiteId) {
    return axios.get('/api/getHomePageData/' + websiteId);
  },
}