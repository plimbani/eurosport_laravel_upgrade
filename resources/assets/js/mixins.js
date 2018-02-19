// inject a handler for `myOption` custom option
Vue.mixin({
	computed: {
		userDetails: function() {
      return this.$store.state.Users.userDetails
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
  }
})
new Vue({
})