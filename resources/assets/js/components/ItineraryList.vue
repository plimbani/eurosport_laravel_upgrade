<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="itineraries.length" v-model="itineraries" :options="{draggable:'.itinerary-item', handle: '.itinerary-handle'}">
				<div class="itinerary-item draggable--section-card" v-for="(itinerary, index) in itineraries" :key="index">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
			  				{{ itinerary.day }}, {{ itinerary.time }}, {{ itinerary.item }}
			  			</div>
			  			<div class="draggable--section-card-header-icons">
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deleteItinerary(index)">
				        	<i class="jv-icon jv-dustbin"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editItinerary(itinerary, index)">
				        	<i class="jv-icon jv-edit"></i>
				        </a>
				        <a class="text-primary itinerary-handle draggable-handle" href="javascript:void(0)">
				        	<i class="fa fa-bars"></i>
				        </a>
					    </div>			  			
						</div>
					</div>
				</div>
			</draggable>
			<p v-else class="help-block text-muted">{{ $lang.no_itinerary_found }}</p>
		</div>
		<button type="button" class="btn btn-primary" @click="addItinerary()">{{ $lang.homepage_add_itinerary }}</button>
		<itinerary-modal :currentItineraryOperation="currentItineraryOperation" @storeItinerary="storeItinerary" @updateItinerary="updateItinerary"></itinerary-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import ItineraryModal  from  './ItineraryModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				itineraries: [],
				currentItineraryIndex: -1,
				currentItineraryOperation: 'add',
			};
		},
		components: {
			draggable,
			ItineraryModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			this.getAllItineraries();
			this.$root.$on('getItineraries', this.getItineraries);
		},
		methods: {
			getAllItineraries() {
				Website.getItineraries(this.getWebsite).then(
	        (response) => {
	          this.itineraries = response.data.data;
	        },
	        (error) => {
	        }
	      );
			},			
			addItinerary() {
				var formData = {
					id: '',
					day: '',
					time: '',
					item: '',
				};
				this.currentItineraryIndex = this.itineraries.length;
				this.currentItineraryOperation = 'add';
				this.initializeModel(formData);
			},      
			storeItinerary(itineraryData) {
				this.itineraries.push({ id: '', day: itineraryData.day, time: itineraryData.time, item: itineraryData.item });
				$('#itinerary_modal').modal('hide');
			},
      editItinerary(itinerary, index) {
				var formData = {
					id: itinerary.id,
					day: itinerary.day,
					time: itinerary.time,
					item: itinerary.item,
				};
				this.currentItineraryIndex = index;
				this.currentItineraryOperation = 'edit';
				this.initializeModel(formData);      	
      },
			updateItinerary(itineraryData) {
				this.itineraries[this.currentItineraryIndex].day = itineraryData.day;
				this.itineraries[this.currentItineraryIndex].time = itineraryData.time;
				this.itineraries[this.currentItineraryIndex].item = itineraryData.item;
				$('#itinerary_modal').modal('hide');
			},      
      deleteItinerary(deleteIndex) {
				this.itineraries = _.remove(this.itineraries, function(stat, index) {
				  return index != deleteIndex;
				});
      },
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setItineraryData', formData);
				$('#itinerary_modal').modal('show');
			},
			getItineraries() {
        this.$emit('setItineraries', this.itineraries);
      },			
		},
	}
</script>