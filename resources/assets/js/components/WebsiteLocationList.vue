<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="locations.length" v-model="locations" :options="{draggable:'.location-item', handle: '.location-handle'}">
		  	<div class="location-item draggable--section-card" v-for="(location, index) in locations" :key="index">
		  		<div class="draggable--section-card-header">
		  			<div class="draggable--section-card-header-panel">
		  				<div>
			  				{{ location.name + ', ' + location.address }}
			  			</div>
			  			<div class="draggable--section-card-header-icons">
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="deleteLocation(index)">
					        	<i class="jv-icon jv-dustbin"></i>
					        </a>
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="editLocation(location, index)">
					        	<i class="jv-icon jv-edit"></i>
					        </a>
					        <a class="text-primary location-handle draggable-handle" href="javascript:void(0)">
					        	<i class="fa fa-bars"></i>
					        </a>
					    </div>
		  			</div>
		  			<!-- Add child tags like draggable--section-child-1 -->
		  		</div>
		    </div>
			</draggable>
			<p v-else class="help-block text-muted">{{ $lang.no_location_found }}</p>
		</div>
		<button type="button" class="btn btn-primary" @click="addLocation()">{{ $lang.venue_add_location }}</button>
		<website-location-modal :currentLocationOperation="currentLocationOperation" @storeLocation="storeLocation" @updateLocation="updateLocation"></website-location-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import WebsiteLocationModal  from  './WebsiteLocationModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				locations: [],
				currentLocationIndex: -1,
				currentLocationOperation: 'add',
			};
		},
		components: {
			draggable,
			WebsiteLocationModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			// Get all locations
			this.getAllLocations();
			this.$root.$on('getLocations', this.getLocations);
		},
		methods: {
			getAllLocations() {
				Website.getLocations(this.getWebsite).then(
	        (response) => {
	          this.locations = response.data.data;
	        },
	        (error) => {
	        }
	      );
			},
			addLocation() {
				var formData = {
					id: '',
					name: '',
					address: '',
				};
				this.currentLocationIndex = this.locations.length;
				this.currentLocationOperation = 'add';
				this.initializeModel(formData);
			},
			storeLocation(locationData) {
				this.locations.push({ id: '', name: locationData.name, address: locationData.address });
				$('#website_location_modal').modal('hide');
			},
			editLocation(location, index) {
				var formData = {
					id: location.id,
					name: location.name,
					address: location.address,
				};
				this.currentLocationIndex = index;
				this.currentLocationOperation = 'edit';
				this.initializeModel(formData);
			},
			updateLocation(locationData) {
				this.locations[this.currentLocationIndex].name = locationData.name;
				this.locations[this.currentLocationIndex].address = locationData.address;
				$('#website_location_modal').modal('hide');
			},
			deleteLocation(deleteIndex) {
				this.locations = _.remove(this.locations, function(location, index) {
				  return index != deleteIndex;
				});
			},
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setLocationData', formData);
				$('#website_location_modal').modal('show');
			},
			getLocations() {
        this.$emit('setLocations', this.locations);
      },
		},
	}
</script>