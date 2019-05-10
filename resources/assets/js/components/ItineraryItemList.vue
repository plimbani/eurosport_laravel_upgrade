<template>
	<div>
		<div class="draggable--section" :class="childClassNames">
			<draggable v-model="itineraryItems" :options="{draggable:'.itinerary-items', handle: '.itinerary-item-handle'}" @end="onDragEnd()">
		  	<div class="itinerary-items draggable--section-card" v-for="(itineraryItem, index) in itineraryItems" :key="index">
		  		<div class="draggable--section-card-header">
		  			<div class="draggable--section-card-header-panel">
		  				<div>
			  				{{ (itineraryItem.day !== null && itineraryItem.day.trim() !== '' ) ? (itineraryItem.day + ', ') : '' }}{{ itineraryItem.time }}, {{ itineraryItem.item }}
			  			</div>

			  			<div class="draggable--section-card-header-icons">
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="deleteItineraryItem(index)">
					        	<i class="fas fa-trash"></i>
					        </a>
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="editItineraryItems(itineraryItem, index)">
					        	<i class="fas fa-pencil"></i>
					        </a>
					        <a class="text-primary itinerary-item-handle draggable-handle" href="javascript:void(0)">
					        	<i class="fas fa-bars"></i>
					        </a>
					    </div>
		  			</div>
		  			<!-- Add child tags like draggable--section-child-1 -->
		  		</div>
		    </div>
			</draggable>
		</div>
		<button type="button" class="btn btn-primary" @click="addItineraryItems()">{{ $lang.add_item }}</button>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import _ from 'lodash';

	export default {
		props: ['childClassNames', 'items', 'parentIndex'],
		data() {
			return {
				itineraryItems: [],
				currentItineraryItemIndex: -1,
				currentItineraryItemOperation: 'add',
			};
		},
		components: {
			draggable,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		watch: {
			items: {
				handler(value){
					this.itineraryItems = _.cloneDeep(value);
				},
				deep: true,
			},
		},
		mounted() {
			// Get all itinerary items
			this.itineraryItems = this.items;
			this.$root.$on('getItineraryItems', this.getItineraryItems);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getItineraryItems');
    },
		methods: {
			addItineraryItems() {
				var formData = {
					id: '',
					day: '',
					time: '',
					item: '',
				};
				this.currentItineraryItemIndex = this.items.length;
				this.currentItineraryItemOperation = 'add';
				var additionalParams = {
					currentItineraryItemOperation: this.currentItineraryItemOperation,
					currentItineraryItemIndex: this.currentItineraryItemIndex,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeItemModal', formData, additionalParams);
			},
			editItineraryItems(itineraryItem, index) {
				var formData = {
					id: itineraryItem.id,
					day: itineraryItem.day,
					time: itineraryItem.time,
					item: itineraryItem.item,
				};
				this.currentItineraryItemIndex = index;
				this.currentItineraryItemOperation = 'edit';
				var additionalParams = {
					currentItineraryItemOperation: this.currentItineraryItemOperation,
					currentItineraryItemIndex: this.currentItineraryItemIndex,
					parentIndex: this.parentIndex,
				};
				this.$emit('initializeItemModal', formData, additionalParams);
			},
			deleteItineraryItem(deleteIndex) {
				this.$emit('deleteItineraryItem', deleteIndex, this.parentIndex);
			},
			onDragEnd() {
				this.getItineraryItems();
			},
			getItineraryItems() {
        this.$emit('setItineraryItems', _.cloneDeep(this.itineraryItems), this.parentIndex);
      },
		},
	}
</script>