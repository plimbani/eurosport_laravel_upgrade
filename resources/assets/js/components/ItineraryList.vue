<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="itineraries.length" v-model="itineraries" :options="{draggable:'.itinerary-item', handle: '.itinerary-handle'}">
				<div class="itinerary-item draggable--section-card" v-for="(itinerary, index) in itineraries" :key="index">
					<div class="draggable--section-card-header">
						<div class="draggable--section-card-header-panel">
							<div>
			  				{{ itinerary.name }}
			  			</div>
			  			<div class="draggable--section-card-header-icons">
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deleteItinerary(index)">
				        	<i class="fas fa-trash"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editItinerary(itinerary, index)">
				        	<i class="fas fa-pencil"></i>
				        </a>
				        <a class="text-primary itinerary-handle draggable-handle" href="javascript:void(0)">
				        	<i class="fas fa-bars"></i>
				        </a>
					    </div>			  			
						</div>
						<itinerary-item-list :parentIndex="index" :childClassNames="'draggable--section-child-1'" :items="itinerary.items" @setItineraryItems="setItineraryItems" @deleteItineraryItem="deleteItineraryItem" @initializeItemModal="initializeItemModal"></itinerary-item-list>
					</div>
				</div>
			</draggable>
			<p v-else class="text-muted">{{ $lang.no_itinerary_found }}</p>
		</div>
		<button type="button" class="btn btn-primary" @click="addItinerary()">{{ $lang.homepage_add_itinerary }}</button>
		<itinerary-modal :currentItineraryOperation="currentItineraryOperation" @storeItinerary="storeItinerary" @updateItinerary="updateItinerary"></itinerary-modal>
		<itinerary-item-modal :currentItineraryItemOperation="itemModalData.currentItineraryItemOperation" @storeItineraryItem="storeItineraryItem" @updateItineraryItem="updateItineraryItem" :modalIndex="itemModalData.parentIndex"></itinerary-item-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import ItineraryModal  from  './ItineraryModal.vue';
	import ItineraryItemList from './ItineraryItemList.vue';
	import ItineraryItemModal from './ItineraryItemModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				itineraries: [],
				currentItineraryIndex: -1,
				currentItineraryOperation: 'add',
				itemModalData: {
					currentItineraryItemOperation: 'add',
					currentItineraryItemIndex: -1,
					parentIndex: -1,
				}
			};
		},
		components: {
			draggable,
			ItineraryModal,
			ItineraryItemList,
			ItineraryItemModal,
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
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getItineraries');
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
					name: '',
					items: [],
				};
				this.currentItineraryIndex = this.itineraries.length;
				this.currentItineraryOperation = 'add';
				this.initializeModel(formData);
			},      
			storeItinerary(itineraryData) {
				this.itineraries.push({ id: '', name: itineraryData.name, items: [] });
				$('#itinerary_modal').modal('hide');
			},
      editItinerary(itinerary, index) {
				var formData = {
					id: itinerary.id,
					name: itinerary.name,
					items: itinerary.items,
				};
				this.currentItineraryIndex = index;
				this.currentItineraryOperation = 'edit';
				this.initializeModel(formData);      	
      },
			updateItinerary(itineraryData) {
				this.itineraries[this.currentItineraryIndex].name = itineraryData.name;
				this.itineraries[this.currentItineraryIndex].items = itineraryData.items;
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
			setItineraryItems(ItineraryItems, index) {
				this.itineraries[index].items = ItineraryItems;
			},
			initializeItemModal(formData, additionalParams) {
				this.itemModalData.currentItineraryItemOperation = additionalParams.currentItineraryItemOperation;
				this.itemModalData.currentItineraryItemIndex = additionalParams.currentItineraryItemIndex;
				this.itemModalData.parentIndex = additionalParams.parentIndex;
				this.$root.$emit('setItineraryItemData', formData);
				$('#itinerary_item_modal').modal('show');
			},
			storeItineraryItem(itineraryItemData) {
				var itineraryIndex = this.itemModalData.parentIndex;
				var currentItineraryItemIndex = this.itemModalData.currentItineraryItemIndex;
				this.itineraries[itineraryIndex]['items'].push({ id: '', day: itineraryItemData.day, time: itineraryItemData.time, item: itineraryItemData.item });
				$('#itinerary_item_modal').modal('hide');
			},
			updateItineraryItem(itineraryItemData) {
				var itineraryIndex = this.itemModalData.parentIndex;
				var currentItineraryItemIndex = this.itemModalData.currentItineraryItemIndex;
				this.itineraries[itineraryIndex]['items'][currentItineraryItemIndex].day = itineraryItemData.day;
				this.itineraries[itineraryIndex]['items'][currentItineraryItemIndex].time = itineraryItemData.time;
				this.itineraries[itineraryIndex]['items'][currentItineraryItemIndex].item = itineraryItemData.item;
				$('#itinerary_item_modal').modal('hide');
			},
			deleteItineraryItem(deleteIndex, itineraryIndex) {
				this.itineraries[itineraryIndex]['items'] = _.remove(this.itineraries[itineraryIndex]['items'], function(item, index) {
					return index != deleteIndex;
				});
			},
		},
	}
</script>