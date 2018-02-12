<template>
	<div class="tab-content">
		<form name="website_staypage" enctype="multipart/form-data">
			<div class="card">
				<div class="card-block">
					<h6><strong>{{$lang.website_stay}}</strong></h6>				
						<div class="form-group justify-content-between row">
		        	<div class="col-sm-6">
		        		<div class="row">
			          	<label class="col-sm-12 no-padding form-control-label">{{$lang.website_stay_introduction_content}}</label>
			          	<div class="col-sm-12">
			          		<insert-text-editor :id="'stay_introduction_content'" :value="staypage.stay_introduction_content" @setEditorValue="setStayIntroductionContent"></insert-text-editor>
			          	</div>
		          	</div>
		          </div>
						</div>				
				</div>
				<div class="card-block">
					<h6><strong>{{$lang.website_meals}}</strong></h6>
						<div class="form-group justify-content-between row">
		        	<div class="col-sm-6">
		        		<div class="row">
			          	<label class="col-sm-12 no-padding form-control-label">{{$lang.page_content}}</label>
			          	<div class="col-sm-12">
			          		<insert-text-editor :id="'meals_page_content'" :value="staypage.meals_page_content" @setEditorValue="setMealsPageContent"></insert-text-editor>
			          	</div>
		          	</div>
		          </div>
						</div>
				</div>
				<div class="card-block">
					<h6><strong>{{$lang.website_accommodation}}</strong></h6>
						<div class="form-group justify-content-between row">
		        	<div class="col-sm-6">
		        		<div class="row">
			          	<label class="col-sm-12 no-padding form-control-label">{{$lang.page_content}}</label>
			          	<div class="col-sm-12">
			          		<insert-text-editor :id="'accommodation_page_content'" :value="staypage.accommodation_page_content" @setEditorValue="setAccommodationPageContent"></insert-text-editor>
			          	</div>
		          	</div>
		          </div>
						</div>
				</div>				
			</div>
		</form>
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
import Website from '../../../api/website.js';
import Tournament from '../../../api/tournament.js';
import InsertTextEditor from '../../../components/InsertTextEditor/InsertTextEditor.vue';

export default {
	components: {
		InsertTextEditor
	},
	data() {
		return {
			staypage: {
				websiteId: null,
				stay_introduction_content: '',
				meals_page_content: '',
				accommodation_page_content: '',
			},
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_stay', 
			currentPage:'Stay'
		};		
		this.$store.dispatch('setActiveTab', currentNavigationData);
		this.getStayPageContent();
	},
	computed: {
	},
	methods: {
		redirectToForward() {
			this.$router.push({name:'website_visitors'})
		},
		redirectToBackward() {
			this.$router.push({name:'website_program'})
		},
		setStayIntroductionContent(content) {
			this.staypage.stay_introduction_content = content;
		},
		setMealsPageContent(content) {
			this.staypage.meals_page_content = content;
		},
		setAccommodationPageContent(content) {
			this.staypage.accommodation_page_content = content;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		next() {
			this.$root.$emit('getEditorValue');
			this.staypage.websiteId = this.getWebsiteId();
			$("body .js-loader").removeClass('d-none');
			Website.saveStayPageData(this.staypage).then(
				(response)=> {
					$("body .js-loader").addClass('d-none');
					toastr.success('Staypage has been updated successfully.', 'Success');
					this.redirectToForward();
				},
        (error)=>{
        }				
			);
		},
		getStayPageContent() {
			var websiteId = this.getWebsiteId();
			Website.getStayPageData(websiteId).then(
				(response)=> {
					this.staypage.stay_introduction_content = response.data.data.stay.content;
					this.staypage.meals_page_content = response.data.data.meals.content;
					this.staypage.accommodation_page_content = response.data.data.accommodation.content;
				},
				(error) => {					
				}
			);
		},
	},
}
</script>