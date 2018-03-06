<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.itinerary_title}}</strong></h6>
				<div class="form-group justify-content-between row">
					<div class="col-sm-6">
	      		<itinerary-list @setItineraries="setItineraries"></itinerary-list>
	      	</div>
	      </div>
	      <hr class="my-4">
	      <h6><strong>{{$lang.additional_page}}</strong></h6>
	      <additional-pages @setAdditionalPages="setAdditionalPages" :additional_pages="programpage.additional_pages"></additional-pages>
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
import Tournament from '../../../api/tournament.js';
import Website from '../../../../js/api/website.js';
import ItineraryList from '../../../components/ItineraryList.vue';
import AdditionalPages  from  '../../../components/AdditionalPages.vue';
import InsertTextEditor from '../../../components/InsertTextEditor/InsertTextEditor.vue';

export default {
	components: {
		InsertTextEditor,
		ItineraryList,
		AdditionalPages,
	},
	data() {
		return {
			programpage: {
				websiteId: null,
				itineraries: [],
				additional_pages: [],
				parent_id: '',
			},
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_program',
			currentPage:'Program'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
		this.getProgramPageContent();
	},
	computed: {
	},
	methods: {
		redirectToForward() {
			var route = this.getWebsiteForwardRoute('program');
      if(route) {
        this.$router.push({name:route});
      }
		},
		redirectToBackward() {
			var route = this.getWebsiteBackwardRoute('program');
      if(route) {
        this.$router.push({name:route});
      }
		},
		setItineraries(itineraries) {
			this.programpage.itineraries = itineraries;
		},
		next() {
			this.$root.$emit('getEditorValue');
			this.$root.$emit('getItineraries');
			this.programpage.websiteId = this.getWebsiteId();
			this.$root.$emit('getAdditionalPages');
			$("body .js-loader").removeClass('d-none');
			Website.saveProgramPageData(this.programpage).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Program page has been updated successfully.', 'Success');
          this.redirectToForward();
        },
        (error)=>{
        }
      );
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		setAdditionalPages(pages) {
			this.programpage.additional_pages = pages;
		},
		getProgramPageContent() {
			var websiteId = this.getWebsiteId();
			Website.getProgramPageData(websiteId).then(
				(response)=> {
					this.programpage.parent_id = response.data.data.pagesData.id;
					this.programpage.additional_pages = response.data.data.additionalPages;
					this.$root.$emit('setPages', this.programpage.additional_pages);
				},
				(error) => {

				}
			);
		}
	},
}
</script>
