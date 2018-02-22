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
  // Get all website sponsors
  getSponsors(websiteId) {
    return axios.get('/api/getSponsors/' + websiteId);
  },
  // Get all website details
  websiteSummaryData(websiteId) {
    return api.post('websites/websiteSummary',{'websiteId':websiteId})
  },
  // Save home page data
  saveHomePageData(pageData) {
    return api.post('/saveHomePageData', pageData);
  },
  // Save WebsiteTournament data
  saveWebsiteTournamentPageData(pageData) {
    return api.post('/saveWebsiteTournamentPageData', pageData);
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
  // Save visitor page data
  saveVisitorPageData(pageData) {
    return api.post('/saveVisitorPageData', pageData);
  },
  // Get visitor page data
  getVisitorPageData(websiteId) {
    return axios.get('/api/getVisitorPageData/' + websiteId);
  },
  // Save staypage data
  saveStayPageData(pageData) {
    return api.post('/saveStayPageData', pageData);
  },
  // Get staypage data
  getStayPageData(websiteId) {
    return axios.get('/api/getStayPageData/' + websiteId);
  },
  // save additional pages
  saveAdditionalPage(pageData) {
    return api.post('/saveAdditionalPageData', pageData);
  },
  // Get WebsiteTournament data
  getWebsiteTournamentPageData(websiteId) {
    return axios.get('/api/getWebsiteTournamentPageData/' + websiteId);
  },
  // Get all website age categories
  getAgeCategories(websiteId) {
    return axios.get('/api/getAgeCategories/' + websiteId);
  },
  // Get all website team page data
  getTeamPageData(websiteId) {
    return axios.get('/api/getTeamPageData/' + websiteId);
  },
  // Save teampage data
  saveTeamPageData(pageData) {
    return api.post('/saveTeamPageData', pageData);
  },
  getAgeCategories(websiteId) {
    return axios.get('/api/getAgeCategories/' + websiteId);
  },
  // Import age category and team data
  importAgeCategoryAndTeamData(formData) {
    return api.post('/importAgeCategoryAndTeamData', formData);
  },
  // get all itineraries
  getItineraries(websiteId) {
    return axios.get('/api/getItineraries/' + websiteId);
  },
  // save program page data
  saveProgramPageData(pageData) {
    return api.post('/saveProgramPageData', pageData);
  },
  // get program page data
  getProgramPageData(websiteId) {
    return axios.get('/api/getProgramPageData/' + websiteId);
  },
  // Get all photos
  getPhotos(websiteId) {
    return axios.get('/api/getPhotos/' + websiteId);
  },
  // Get all documents
  getDocuments(websiteId) {
    return axios.get('/api/getDocuments/' + websiteId);
  },
  // Save media page
  saveMediaPageData(pageData) {
    return api.post('/saveMediaPageData', pageData);
  },
  // Save venue data
  saveVenuePageData(pageData) {
    return api.post('/saveVenuePageData', pageData);
  },
  // Get all venue locations
  getLocations(websiteId) {
    return axios.get('/api/getLocations/' + websiteId);
  },
  // Get contact details
  getContactDetails(websiteId) {
    return axios.get('/api/getContactDetails/' + websiteId);
  },
  // Save contact details
  saveContactDetails(contactDetails) {
    return axios.post('/api/saveContactDetails', contactDetails);
  },
  // Get website details
  getWebsiteDetails(websiteId) {
    return axios.get('/api/getWebsiteDetails/' + websiteId);
  },
}