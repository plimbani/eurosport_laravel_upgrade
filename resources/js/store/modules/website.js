import * as types from '../mutation-types';
import Website from '../../api/website';

// state
const state = {
	id: null,
  tournament_name: null,
  tournament_dates: null,
  tournament_location: null,
  pages: null,
  preview_domain: null,
  preview_url: null,
  preview_domain_generated_at: null,
  routes: {
  	'website': 'website_add',
  	'home': 'website_homepage',
  	'teams': 'website_teams',
  	'venue': 'website_venue',
  	'tournament': 'website_tournament',
  	'program': 'website_program',
  	'stay': 'website_stay',
  	'visitors': 'website_visitors',
  	'media': 'website_media',
  	'contact': 'website_contact',
  },
};

// getters
const getters = {

};

// actions
const actions = {
	SetWebsite ({commit}, currentWebsite) {
  	commit(types.CURRENT_WEBSITE, currentWebsite);
  },
	SaveWebsiteDetails ({commit}, websiteData) {
		return new Promise((resolve, reject) => {
			Website.saveWebsite(websiteData).then(
				(response) => {
					if(response.data.status_code == 200) {
			      commit(types.WEBSITE_DATA, response.data.data)
					} else {
					  alert('Error Occured')
					}
					resolve(response.data.data);
				},
	  		(error) => {
	  			reject(response);
	  		}
	  	)
  	});
	},
	ResetWebsiteDetail ({commit}) {
  	commit(types.RESET_WEBSITE_DETAIL);
  },
  SetWebsitePreviewDetail ({commit}, previewDetail) {
  	commit(types.SET_PREVIEW_DETAIL, previewDetail);
  },
};

// mutations
const mutations = {
	[types.CURRENT_WEBSITE] (state, currentWebsite) {
		state.id = currentWebsite.id;
	  state.tournament_name = currentWebsite.tournament_name;
	  state.tournament_dates = currentWebsite.tournament_dates;
		state.tournament_location = currentWebsite.tournament_location;
		state.pages = currentWebsite.pages;
		state.preview_domain = currentWebsite.preview_domain;
		state.preview_url = window.appScheme + '://' + currentWebsite.preview_domain;
		state.preview_domain_generated_at = currentWebsite.preview_domain_generated_at;
	},
	[types.WEBSITE_DATA] (state, websiteData) {
		state.id = websiteData.id;
		state.tournament_name = websiteData.tournament_name;
		state.tournament_dates = websiteData.tournament_dates;
		state.tournament_location = websiteData.tournament_location;
		state.domain_name = websiteData.domain_name;
		state.linked_tournament = websiteData.linked_tournament;
		state.google_analytics_id = websiteData.google_analytics_id;
		state.tournament_logo = websiteData.tournament_logo;
		state.social_sharing_graphic = websiteData.social_sharing_graphic;
		state.font = websiteData.font;
		state.color = websiteData.color;
	},
	[types.RESET_WEBSITE_DETAIL] (state) {
		state.id = null;
		state.tournament_name = null;
		state.tournament_dates = null;
		state.tournament_location = null;
		state.domain_name = null;
		state.linked_tournament = null;
		state.google_analytics_id = null;
		state.tournament_logo = null;
		state.social_sharing_graphic = null;
		state.font = null;
		state.color = null;
	},
	[types.SET_PREVIEW_DETAIL] (state, previewDetail) {
		state.preview_domain = previewDetail.preview_domain;
		state.preview_url = window.appScheme + '://' + previewDetail.preview_domain;
		state.preview_domain_generated_at = previewDetail.preview_domain_generated_at;
	},
};

export default {
  state,
  getters,
  actions,
  mutations,
};