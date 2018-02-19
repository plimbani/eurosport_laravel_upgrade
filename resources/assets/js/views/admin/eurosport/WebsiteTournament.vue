<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<form name="website_homepage" enctype="multipart/form-data">
	        <h6><strong>{{$lang.competation_age_categories}}</strong></h6>
	        <div class="form-group justify-content-between row">
	        	<div class="col-sm-12">
	        		<div class="row">
		          	<div class="col-sm-12">
		          		<insert-text-editor :id="'age_categories'" :value="tournament.age_categories" @setEditorValue="setAgeCategoriesText"></insert-text-editor>
		          	</div>
	          	</div>
	          </div>
	        </div>
	        <div v-if="isPageEnabled('rules')">
		        <hr class="my-4">
		        <h6><strong>{{$lang.tournament_rules}}</strong></h6>
		        <div class="form-group justify-content-between row">
		        	<div class="col-sm-12">
		        		<div class="row">
			          	<div class="col-sm-12">
			          		<insert-text-editor :id="'rules'" :value="tournament.rules" @setEditorValue="setRulesText"></insert-text-editor>
			          	</div>
		          	</div>
		          </div>
		        </div>
		      </div>
		      <div v-if="isPageEnabled('history')">
		        <hr class="my-4">
		        <h6><strong>{{$lang.tournament_history}}</strong></h6>
		        <div class="row">
		          <div class="col-sm-8">
	        			<history-year-list @setHistoryData="setHistoryData" :countries="tournament.countries"></history-year-list>
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
	        <button class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
	      </div>
	    </div>
	  </div>
  </div>
</template>
<script>
var moment = require('moment');
import InsertTextEditor from '../../../components/InsertTextEditor/InsertTextEditor.vue';
import Website from '../../../api/website.js';
import HistoryYearList from '../../../components/HistoryYearList.vue';

export default {
	components: {
		InsertTextEditor,
		HistoryYearList,
	},
	data() {
		return {
			tournament: {
				age_categories: '',
				rules: '',
				websiteId: null,
				history: [],
				countries: [],
			},
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_tournament', 
			currentPage:'Tournament'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
		this.getWebsiteTournamentPageData();
	},
	computed: {
	},
	methods: {
		next() {
			this.$root.$emit('getEditorValue');
      this.tournament.websiteId = this.getWebsiteId();
      this.$root.$emit('getHistoryYears');
      $("body .js-loader").removeClass('d-none');
			Website.saveWebsiteTournamentPageData(this.tournament).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Tournament page has been updated successfully.', 'Success');
          this.$router.push({name:'website_program'});
        },
        (error)=>{
        }
      );
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		setAgeCategoriesText(content) {
			this.tournament.age_categories = content;
		},
		setRulesText(content) {
			this.tournament.rules = content;
		},
		redirectToBackward() {
			this.$router.push({name:'website_venue'})
		},
		getWebsiteTournamentPageData() {
			var websiteId = this.getWebsiteId();
			Website.getWebsiteTournamentPageData(websiteId).then(
				(response)=> {
					this.tournament.age_categories = response.data.data.tournament.content !== null ? response.data.data.tournament.content : '';
					this.tournament.rules = response.data.data.rules.content !== null ? response.data.data.rules.content : '';
					this.tournament.countries = response.data.data.countries;
					this.$root.$emit('setHistoryYears', response.data.data.history);
				},
				(error) => {
				}
			);
		},
		setHistoryData(historyData) {
			this.tournament.history = historyData;
		},
	},
}
</script>