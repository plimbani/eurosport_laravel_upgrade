<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_locations}}</strong></h6>
				<form name="website_venue" enctype="multipart/form-data">
					<div class="form-group row">
	        	<div class="col-sm-6">
	        		<website-location-list @setLocations="setLocations"></website-location-list>
	        	</div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-sm-6">
	        		<h6><strong>{{$lang.website_map}}</strong></h6>
	        		<website-location-map></website-location-map>
	        	</div>
	        </div>
				</form>
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	        <button class="btn btn-primary" @click="redirectToBackward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
	      </div>
	      <div class="pull-right">
	        <button class="btn btn-primary" @click="redirectToForward()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
	      </div>
	    </div>
	  </div>
  </div>
</template>
<script>
var moment = require('moment');
import * as VueGoogleMaps from 'vue2-google-maps';
import Vue from 'vue';
import Website from '../../../../js/api/website.js';
import WebsiteLocationList from '../../../components/WebsiteLocationList.vue';
import WebsiteLocationMap from '../../../components/WebsiteLocationMap.vue';

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyCUkAy2II7BJnOURSZWB9_3FyuSgBnq2Lc',
    libraries: 'places', //// If you need to use place input
  }
});

export default {
	components: {
		WebsiteLocationList,
		WebsiteLocationMap,
	},
	data() {
		return {
			venue: {
				websiteId: null,
				locations: [],
			},
			center: {lat: 10.0, lng: 10.0},
      markers: [{
        position: {lat: 10.0, lng: 10.0}
      }, {
        position: {lat: 11.0, lng: 11.0}
      }]
		}
	},
	/*watch: {
	  '$route'(to, from) {
	    // Call resizePreserveCenter() on all maps
	    Vue.$gmapDefaultResizeBus.$emit('resize')
	  }
	},*/
	mounted() {
		let currentNavigationData = {
			activeTab:'website_venue',
			currentPage:'Venue'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {
	},
	methods: {
		redirectToForward() {
      this.$root.$emit('getLocations');

      this.venue.websiteId = this.getWebsiteId();
      $("body .js-loader").removeClass('d-none');
      Website.saveVenuePageData(this.venue).then(
        (response)=> {
          $("body .js-loader").addClass('d-none');
          toastr.success('Venue page has been updated successfully.', 'Success');
          this.$router.push({name:'website_tournament'});
        },
        (error)=>{
        }
      );
		},
		setLocations(locations) {
    	this.venue.locations = locations;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		redirectToBackward() {
			this.$router.push({name:'website_teams'})
		}
	},
}
</script>
