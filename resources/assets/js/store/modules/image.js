import * as types from '../mutation-types';

// state
const state = {
  'websiteTournamentLogoPath': '',
  'socialSharingGraphicPath': '',
  'heroImagePath': '',
  'welcomeImagePath': '',
  'organiserLogoPath': '',
  'sponsorLogoPath': '',
  'photoPath': '',
  'documentPath': '',
  'editorImagePath': '',
};

// getters
const getters = {

};

// actions
const actions = {
};

// mutations
const mutations = {
	[types.SET_IMAGE_PATH] (state, imagePath) {
		state.websiteTournamentLogoPath = imagePath.website_tournament_logo;
		state.socialSharingGraphicPath = imagePath.social_sharing_graphic;
		state.heroImagePath = imagePath.hero_image;
		state.welcomeImagePath = imagePath.welcome_image;
		state.organiserLogoPath = imagePath.organiser_logo;
		state.sponsorLogoPath = imagePath.sponsor_logo;
    state.photoPath = imagePath.photo;
    state.documentPath = imagePath.document;
    state.editorImagePath = imagePath.editor_image;
	},
};

export default {
  state,
  getters,
  actions,
  mutations,
};