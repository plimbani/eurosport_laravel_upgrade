// inject a handler for `myOption` custom option
Vue.mixin({
	computed: {
		userDetails: function() {
      return this.$store.state.Users.userDetails
    },
    getWebsiteRoutes: function() {
      return this.$store.state.Website.routes;
    },
		isTournamentAdministrator: function() {
      return this.userDetails.role_slug == 'tournament.administrator';
    },
    isAdmin: function() {
			return this.userDetails.role_slug != 'tournament.administrator';
		},
	},
  methods: {
  	clearErrorMsgs() {
		this.$nextTick()
			.then(() => {
    	this.errors.clear();
	  	});
  	},
  	isPageEnabled(pageName) {
      if(this.isTournamentAdministrator) {
        var websitePages = this.$store.state.Website.pages;
        var page = _.find(websitePages, {'name': pageName, 'is_enabled': 1});
        if(!page) {
          return false;
        }
      }
      return true;
    },
    getWebsiteForwardRoute(pageName) {
      if(this.isTournamentAdministrator) {
        
      }
      return true;
    },
    getBackwardRoute(pageName) {
      if(this.isTournamentAdministrator) {
        
      }
      return true;
    },
  }
})
new Vue({
})