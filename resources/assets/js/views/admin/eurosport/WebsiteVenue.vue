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
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.website_map}}</strong></h6>
	        		<website-location-map @setMarkers="setMarkers"></website-location-map>
	        		<div class="mt-2" v-show="this.venue.isMarkerText">
					      <span class="help is-danger">Please add comment to all the markers.</span>
					    </div>
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
import Website from '../../../../js/api/website.js';
import WebsiteLocationList from '../../../components/WebsiteLocationList.vue';
import WebsiteLocationMap from '../../../components/WebsiteLocationMap.vue';

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
				markers: [],
      	isMarkerText: false
			}
		}
	},
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
			let vm = this;
      vm.$root.$emit('getLocations');
      vm.$root.$emit('getMarkers');
      vm.venue.isMarkerText == false;
      $.each(vm.venue.markers, function (index, value){
        if (value.information == '') {
          vm.venue.isMarkerText = true;
          return;
        }
      });
      if(vm.venue.isMarkerText == false) {
	      vm.venue.websiteId = vm.getWebsiteId();
	      $("body .js-loader").removeClass('d-none');
	      Website.saveVenuePageData(vm.venue).then(
	        (response)=> {
	        	// vm.venue.isMarkerText == true;
	          $("body .js-loader").addClass('d-none');
	          toastr.success('Venue page has been updated successfully.', 'Success');
	          var route = vm.getWebsiteForwardRoute('venue');
	          if(route) {
	            vm.$router.push({name:route});
	          }
	        },
	        (error)=>{
	        }
	      );
      }
		},
		setLocations(locations) {
    	this.venue.locations = locations;
		},
		setMarkers(markers) {
    	this.venue.markers = markers;
    	return;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		redirectToBackward() {
			var route = this.getWebsiteBackwardRoute('venue');
      if(route) {
        this.$router.push({name:route});
      }
		}
	},
}
</script>
