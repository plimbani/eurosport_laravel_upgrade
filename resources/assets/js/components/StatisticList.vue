<template>
	<div>
		<draggable v-model="statistics" :options="{draggable:'.statistic-item', handle: '.statistic-handle'}">
	  	<div class="col-sm-12 statistic-item" v-for="(statistic, index) in statistics" :key="statistic.id">
        {{ statistic.content }}
        <a class="text-primary" href="javascript:void(0)"
        	@click="deleteStatistic(index)">
        	<i class="jv-icon jv-dustbin"></i>
        </a>
        <a class="text-primary" href="javascript:void(0)"
        	@click="editStatistic(statistic, index)">
        	<i class="jv-icon jv-edit"></i>
        </a>
        <a class="text-primary statistic-handle draggable-handle" href="javascript:void(0)">
        	<i class="fa fa-bars"></i>
        </a>
	    </div>
	    <button slot="footer" type="button" class="btn btn-primary" @click="addStatistic()">{{ $lang.homepage_add_statistic }}</button>
		</draggable>
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
			this.getStatistics();
		},
		methods: {
			getStatistics() {
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
		},
	}
</script>