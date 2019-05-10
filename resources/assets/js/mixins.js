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
    getWebsitePages: function() {
      return this.$store.state.Website.pages;
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
      return this.getRoute('forward', pageName);
    },
    getWebsiteBackwardRoute(pageName) {
      return this.getRoute('backward', pageName);
    },
    getRoute(type, pageName) {
      var websiteRoutes = _.cloneDeep(this.getWebsiteRoutes);
      var websitePages = _.cloneDeep(this.getWebsitePages);
      var websiteRouteKeys = Object.keys(websiteRoutes);
      var websiteRouteNames = Object.keys(websiteRoutes).map(function(key) {
        return websiteRoutes[key];
      });
      var currentRouteIndex = _.indexOf(websiteRouteKeys, pageName);
      var routeIndex = type == 'forward' ? (currentRouteIndex + 1) : (currentRouteIndex - 1);
      if(routeIndex == 0 || routeIndex >= websiteRoutes.length) {
        return null;
      }
      if(this.isAdmin) {
        return websiteRoutes[websiteRouteKeys[routeIndex]];
      } else {
        var route = null;
        var availableRoutes;
        if(type == 'forward') {
          availableRoutesKeys = _.slice(websiteRouteKeys, routeIndex);
        } else {
          availableRoutesKeys = _.reverse(_.slice(websiteRouteKeys, 0, routeIndex + 1));
        }
        _.forEach(availableRoutesKeys, function(name, index) {
          var page = _.find(websitePages, function(o) { return o.name == name && o.is_enabled == 1 });
          if(page) {
            route = websiteRouteNames[_.indexOf(websiteRouteKeys, page.name)];
            return false
          }
        });
        return route;
      }
    },
  }
})
new Vue({
})