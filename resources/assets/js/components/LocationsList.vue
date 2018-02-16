<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="locations" :options="{draggable:'.location-item', handle: '.location-handle'}">
		  	<div class="location-item draggable--section-card" v-for="(location, index) in locations" :key="location.id">
		  		<div class="draggable--section-card-header">
		  			<div class="draggable--section-card-header-panel">
		  				<div>
			  				{{ location.content }}
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
		</div>
		<button type="button" class="btn btn-primary" @click="addLocation()">{{ $lang.homepage_add_location }}</button>
		<location-modal :currentLocationOperation="currentLocationOperation" @storeLocation="storeLocation"></location-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import LocationModal  from  './LocationModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				locations: [],
				currentStatisticIndex: -1,
				currentLocationOperation: 'add',
			};
		},
		components: {
			draggable,
			LocationModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			// Get all statistics
			// this.getAllStatistics();
			this.$root.$on('getStatistics', this.getStatistics);
		},
		methods: {
			getAllLocations() {
				Website.getStatistics(this.getWebsite).then(
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
					venue_name: '',
					venue_address: '',
				};
				this.currentLocationIndex = this.locations.length;
				this.currentLocationOperation = 'add';
				this.initializeModel(formData);
			},
			storeLocation(locationData) {
				this.locations.push({ id: '', venue_name: locationData.venue_name, venue_address: locationData.venue_address });
				$('#location_modal').modal('hide');
			},
			/*editLocation(location, index) {
				var formData = {
					id: location.id,
					location: location.content,
				};
				this.currentStatisticIndex = index;
				this.currentStatisticOperation = 'edit';
				this.initializeModel(formData);
			},*/
			/*updateLocation(locationData) {
				this.locations[this.currentStatisticIndex].content = statisticData.location;
				$('#location_modal').modal('hide');
			},*/
			/*deleteLocation(deleteIndex) {
				this.locations = _.remove(this.locations, function(stat, index) {
				  return index != deleteIndex;
				});
			},*/
			initializeModel(formData) {
				// console.log(formData,'formData');
				var vm = this;
				this.$root.$emit('setLocationData', formData);
				$('#location_modal').modal('show');
			},
			/*getLocations() {
        this.$emit('setStatistics', this.locations);
      },*/
		},
	}
</script>