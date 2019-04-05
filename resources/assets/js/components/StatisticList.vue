<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="statistics.length" v-model="statistics" :options="{draggable:'.statistic-item', handle: '.statistic-handle'}">
		  	<div class="statistic-item draggable--section-card" v-for="(statistic, index) in statistics" :key="index">
		  		<div class="draggable--section-card-header">
		  			<div class="draggable--section-card-header-panel">
		  				<div>
			  				{{ statistic.content }}
			  			</div>
			  			<div class="draggable--section-card-header-icons">
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="deleteStatistic(index)">
					        	<i class="fas fa-trash"></i>
					        </a>
					        <a class="text-primary" href="javascript:void(0)"
					        	@click="editStatistic(statistic, index)">
					        	<i class="fas fa-pencil"></i>
					        </a>
					        <a class="text-primary statistic-handle draggable-handle" href="javascript:void(0)">
					        	<i class="fas fa-bars"></i>
					        </a>
					    </div>
		  			</div>
		  			<!-- Add child tags like draggable--section-child-1 -->
		  		</div>
		    </div>
			</draggable>
			<p v-else class="text-muted">{{ $lang.no_statistics_found }}</p>
		</div>
		<button type="button" class="btn btn-primary" @click="addStatistic()">{{ $lang.homepage_add_statistic }}</button>
		<statistic-modal :currentStatisticOperation="currentStatisticOperation" @storeStatistic="storeStatistic" @updateStatistic="updateStatistic"></statistic-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import StatisticModal  from  './StatisticModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				statistics: [],
				currentStatisticIndex: -1,
				currentStatisticOperation: 'add',
			};
		},
		components: {
			draggable,
			StatisticModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			// Get all statistics
			this.getAllStatistics();
			this.$root.$on('getStatistics', this.getStatistics);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getStatistics');
    },
		methods: {
			getAllStatistics() {
				Website.getStatistics(this.getWebsite).then(
	        (response) => {
	          this.statistics = response.data.data;
	        },
	        (error) => {
	        }
	      );
			},
			addStatistic() {
				var formData = {
					id: '',
					statistic: '',
				};
				this.currentStatisticIndex = this.statistics.length;
				this.currentStatisticOperation = 'add';
				this.initializeModel(formData);
			},
			storeStatistic(statisticData) {
				this.statistics.push({ id: '', content: statisticData.statistic });
				$('#statistic_modal').modal('hide');
			},
			editStatistic(statistic, index) {
				var formData = {
					id: statistic.id,
					statistic: statistic.content,
				};
				this.currentStatisticIndex = index;
				this.currentStatisticOperation = 'edit';
				this.initializeModel(formData);
			},
			updateStatistic(statisticData) {
				this.statistics[this.currentStatisticIndex].content = statisticData.statistic;
				$('#statistic_modal').modal('hide');
			},
			deleteStatistic(deleteIndex) {
				this.statistics = _.remove(this.statistics, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setStatisticData', formData);
				$('#statistic_modal').modal('show');
			},
			getStatistics() {
        this.$emit('setStatistics', this.statistics);
      },
		},
	}
</script>