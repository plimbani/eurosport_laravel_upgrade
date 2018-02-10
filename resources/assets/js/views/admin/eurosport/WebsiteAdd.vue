<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_information}}</strong></h6>
				<form name="websiteName" enctype="multipart/form-data">
					<div class="row justify-content-between">
						<div class="col-md-6">
			        <div class="form-group row" :class="{'has-error': errors.has('website.tournament_name') }">
			          <label class="col-sm-4 form-control-label">{{$lang.tournament_name}}*</label>
			          <div class="col-sm-8">
				            <input type="text" class="form-control" placeholder="Enter the name of your tournament" 
				            v-model="website.tournament_name" name="tournament_name"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
		            		<i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('tournament_name')">Tournament name required</span>
			          </div>
			        </div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{$lang.tournament_dates}}*</label>
			          <div class="col-sm-8">
				            <input type="text" class="form-control" placeholder="Enter the tournament date" 
				            v-model="website.tournament_date" name="tournament_date"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_date') }">
		            		<i v-show="errors.has('tournament_date')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('tournament_date')">Tournament dates required</span>
			          </div>
							</div>			        
			        <div class="form-group row" :class="{'has-error': errors.has('website.tournament_location') }">
			          <label class="col-sm-4 form-control-label">{{$lang.tournament_location_field}}*</label>
			          <div class="col-sm-8">
				            <input type="text" class="form-control" placeholder="Enter the location" 
				            v-model="website.tournament_location" name="tournament_location"  v-validate="'required'" :class="{'is-danger': errors.has('tournament_location') }">
		            		<i v-show="errors.has('tournament_location')" class="fa fa-warning"></i>
		            		<span class="help is-danger" v-show="errors.has('tournament_location')">Tournament location required</span>
			          </div>
			        </div>
			        <div class="form-group row">
			          <label class="col-sm-4 form-control-label">{{$lang.domain_name}}</label>
			          <div class="col-sm-8">
				            <input type="text" class="form-control" placeholder="Enter the domain name" v-validate="'url'" :class="{'is-danger': errors.has('domain_name') }" v-model="website.domain_name" name="domain_name">
				            <span class="help is-danger" v-show="errors.has('domain_name')">The domain name is not a valid URL.</span>
			          </div>
			        </div>
			        <div class="form-group row">
			        	<label class="col-sm-4 form-control-label">{{$lang.linked_tournament}}</label>
			        	<div class="col-sm-8">
			        		<div class="form-group">
			        			<select v-if="website.publishedTournaments != null" class="form-control ls-select2" name="linked_tournament" v-model="website.linked_tournament">
		                    <option value="">Please select</option>
		                    <option v-for="tournament in website.publishedTournaments" v-bind:value="tournament.id">
		                        {{ tournament.name }}
		                    </option>
			        			</select>
			        		</div>
			        	</div>
			        </div>
			        <div class="form-group row">
			          <label class="col-sm-4 form-control-label">{{$lang.google_analytics_id}}</label>
			          <div class="col-sm-8">
				            <input type="text" class="form-control" placeholder="Enter the google analytics id" 
				            v-model="website.google_analytics_id" name="google_analytics_id">
			          </div>
			        </div>
			      </div>
			      <div class="col-md-5">
			      	<div class="form-group row">
			      		<label class="col-sm-4 form-control-label">{{$lang.tournament_logo}}</label>
			      		<div class="pull-right">
                    <div v-if="!tournament_logo_image">
                      <img src="http://placehold.it/250x250?text=noimage" width="100px" height="100px"/>
                      <button type="button" class="btn btn-default" name="btnSelect" id="btnSelect">{{$lang.tournament_tournament_choose_button}}</button>
                      <input type="file" id="selectFile" style="display:none;" @change="onTournamentLogoChange">
                    </div>
                    <div v-else>
                        <img :src="tournament_logo_image"
                         width="100px" height="100px"/>
                        <button class="btn btn-default" @click="removeImage">{{$lang.tournament_tournament_remove_button}}</button>
                    </div>
			      		</div>
			      	</div>
							<div class="form-group row">
			      		<label class="col-sm-4 form-control-label">{{$lang.social_sharing_graphic}}</label>
			      		<div class="pull-right">
                    <div v-if="!social_sharing_graphic_image">
                      <img src="http://placehold.it/250x250?text=noimage" width="100px" height="100px"/>
                      <button type="button" class="btn btn-default" name="btnSelect" id="btnSelect_social_sharing">{{$lang.tournament_tournament_choose_button}}</button>
                      <input type="file" id="select_file_social_sharing" style="display:none;" @change="onSocialSharingGraphicImageChange">
                    </div>
                    <div v-else>
                        <img :src="social_sharing_graphic_image"
                         width="100px" height="100px"/>
                        <button class="btn btn-default" @click="removeSocialSharingImage">{{$lang.tournament_tournament_remove_button}}</button>
                    </div>
			      		</div>
			      	</div>			      	
			      </div>
		      </div>

		      <div class="row justify-content-between">
		      	<div class="col-md-12">
		      		<h6><strong>{{$lang.website_customisation}}</strong></h6>
		      	</div>
		        <div class="col-md-6">
							<div class="row">
							  <label class="col-sm-12 form-control-label">{{$lang.website_primary_color}}</label>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<div v-for="primaryColor in customisation.primary_colors" class="websiteColourBox" :style="{'background-color': primaryColor}" @click="setWebsitePrimaryColor(primaryColor)" :class="{ 'website-color-active' : website.primary_color == primaryColor }">
									</div>
								</div>
							</div>
							<div class="row">
							  <label class="col-sm-12 form-control-label">{{$lang.website_secondary_color}}</label>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<div v-for="secondaryColor in customisation.secondary_colors" class="websiteColourBox" :style="{'background-color': secondaryColor}" @click="setWebsiteSecondaryColor(secondaryColor)" :class="{ 'website-color-active' : website.secondary_color == secondaryColor }">
									</div>
								</div>
							</div>
							<div class="row">
							  <div class="col-md-6">
							  	<label class="col-sm-12 form-control-label">{{$lang.website_heading_fonr}}</label>
									<div class="col-md-12" v-for="headingFont in customisation.heading_font">
							      <label class="radio-inline control-label">
							          <input type="radio" name="headingFont" :value="headingFont" class="mr-2" v-model="website.heading_font">{{headingFont}}
							      </label><br>
									</div>
							  </div>
							  <div class="col-md-6">
							  	<label class="col-sm-12 form-control-label">{{$lang.website_body_fonr}}</label>
							  	<div class="col-md-12" v-for="bodyFont in customisation.body_font">
										<label class="radio-inline control-label">
										    <input type="radio" name="bodyFont" :value="bodyFont" class="mr-2" v-model="website.body_font">{{bodyFont}}
										</label><br>
									</div>
							  </div>
							</div>
	          </div>
	          <div class="col-md-6">
	          	<!-- Preview section -->
	          </div>
		      </div>
					<div class="row justify-content-between mt-4">
		      	<div class="col-md-12">
		      		<h6 class="mb-2"><strong>{{$lang.website_sponsors}}</strong></h6>
		      	</div>
		      	<div class="col-sm-6">
	        		<sponsors-list @setSponsors="setSponsors"></sponsors-list>
	        	</div>
		      </div>
		      <div class="row justify-content-between mt-4">
		      	<div class="col-md-12">
		      		<h6 class="mb-2"><strong>{{$lang.website_page_permission}}</strong></h6>
		      	</div>
		      	<div class="col-md-12">
							<div class="form-group row">
								<div class="col-md-8">
								  <ul>
								  	<li class="row">
							      	<span class="col-sm-4"><strong>Page</strong></span>
							      	<span class="col-sm-4"><strong>Enable</strong></span>
							      	<span class="col-sm-4"><strong>Publish</strong></span>
							      </li>
					    			<li class="row" v-for="page in website.pages" v-if="page.is_permission_changeable != 0">
					    				<span class="col-sm-4">{{ page.title }}</span>
					    				<span class="col-sm-4"><input type="checkbox" v-model="page.is_enabled" :true-value="1" :false-value="0"></span>
					    				<span class="col-sm-4"><input type="checkbox" v-model="page.is_published" :true-value="1" :false-value="0"></span>
					    				<ul class="col-sm-12" v-if="page.children && page.children.length > 0">
							      		<li class="row" v-for="childPage in page.children">
							    				<span class="col-sm-4">{{ childPage.title }}</span>
							    				<span class="col-sm-4"><input type="checkbox" v-model="childPage.is_enabled" :true-value="1" :false-value="0"></span>
							    				<span class="col-sm-4"><input type="checkbox" v-model="childPage.is_published" :true-value="1" :false-value="0"></span>
							    			</li>
							      	</ul>
					    			</li>
							    </ul>
								</div>
							</div>
						</div>
		      </div>
				</form>
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	        <!-- <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.tournament_button_home}}</button> -->
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
import Website from '../../../api/website.js';
import SponsorsList from '../../../components/SponsorsList.vue';

export default {
	components: {
		SponsorsList,
	},
	data() {
		return {
			website: {
				websiteId: null,
				tournament_name: '',
				tournament_date: '',
				tournament_location: '',
				domain_name: '',
				linked_tournament: '',
				google_analytics_id: '',
				tournament_logo:'',
				social_sharing_graphic: '',
				publishedTournaments: null,
				primary_color: '',
				secondary_color: '',
				heading_font: '',
				body_font: '',
				pages: [],
				sponsors: [],
			},
			customisation: {
				primary_colors: [],
				secondary_colors: [],
				heading_font: [],
				body_font: [],
			},
			tournament_logo_image: '',			
			social_sharing_graphic_image: '',
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_add', 
			currentPage:'Create Website'
		};
		$('#btnSelect').on('click',function(){
		  $('#selectFile').trigger('click')
		});
		$('#btnSelect_social_sharing').on('click',function(){
		  $('#select_file_social_sharing').trigger('click')
		});
		this.$store.dispatch('setActiveTab', currentNavigationData);	
		this.getAllPublishedTournaments();
		this.getWebsiteCustomisationOptions();
		this.website.websiteId = this.$store.state.Website.id;
		if(this.website.websiteId) {
			this.getWebsiteSummary();
		} else {
			Website.getWebsiteDefaultPages(this.website.websiteId).then(
				(response) => {
					this.website.pages = response.data.data;
				},
				(error) => {
				  // if no Response Set Zero
				  //
				}
			);
		}
	},
	computed: {
	},
	methods: {
		next() {
			this.$validator.validateAll().then(
			(response) => {				
				this.website.tournament_logo = this.tournament_logo_image;
				this.website.social_sharing_graphic = this.social_sharing_graphic_image;
				if(this.$store.state.Website.id != null) {
					this.website.websiteId = this.$store.state.Website.id;					
				}
				this.$root.$emit('getSponsors');
				$("body .js-loader").removeClass('d-none');
				this.$store.dispatch('SaveWebsiteDetails', this.website)
				.then((response) => {
					$("body .js-loader").addClass('d-none');
					toastr['success']('Website details added successfully', 'Success');
					this.redirectToForward();
				})
				.catch((response) => {
					toastr['error']('Error while saving data', 'Error');
				});
			},
			(error) => {

			})
		},
		getAllPublishedTournaments() {
			Tournament.getAllPublishedTournaments().then(
        (response) => {
          this.website.publishedTournaments = response.data.data;
        },
        (error) => {
        }
      )
		},
		redirectToForward() {
			this.$router.push({name:'website_homepage'})
		},
		onTournamentLogoChange(e) {
	    var vm = this;
	    var files = e.target.files || e.dataTransfer.files;

	    if (!files.length)
	     return;

	    var reader = new FileReader();
	    reader.onload = (r) => {
	     vm.tournament_logo_image = r.target.result;
	    };

	    reader.readAsDataURL(files[0]);
	  },
		onSocialSharingGraphicImageChange(e) {
	    var vm = this;
	    var files = e.target.files || e.dataTransfer.files;

	    if (!files.length)
	     return;

		    var reader = new FileReader();
		    reader.onload = (r) => {
	        var image = new Image();
	        image.src = r.target.result;
	               
	        //Validate the File Height and Width.
	        image.onload = function () {
	        	if(Plugin.ValidateImageDimension(this, 1200, 635) == false) {
		          toastr['error']('Social sharing graphic size should be 1200x635', 'Error');
	          } else {
	          	vm.social_sharing_graphic_image = r.target.result;
	          }
	        };		     
		    };

		    reader.readAsDataURL(files[0]);	  	
	  },
		removeImage: function (e) {
			this.tournament_logo_image = '';
			e.preventDefault();
		},
		removeSocialSharingImage: function (e) {
			this.social_sharing_graphic_image = '';
			e.preventDefault();
		},
		getWebsiteCustomisationOptions() {
			Website.getWebsiteCustomisationOptions().then(
        (response) => {
          this.customisation.primary_colors = response.data.data.primary_colors;
          this.customisation.secondary_colors = response.data.data.secondary_colors;
          this.customisation.heading_font = response.data.data.heading_font;
          this.customisation.body_font = response.data.data.body_font;
        },
        (error) => {
        }
      )
		},
		setWebsitePrimaryColor(primaryColour) {
			this.website.primary_color = primaryColour;
		},
		setWebsiteSecondaryColor(secondaryColour) {
			this.website.secondary_color = secondaryColour;
		},
		getWebsiteSummary() {
			Website.websiteSummaryData(this.website.websiteId).then(
				(response) => {
					this.tournament_logo_image = response.data.data.tournament_logo;
					this.social_sharing_graphic_image = response.data.data.social_sharing_graphic;
					this.website.tournament_name = response.data.data.tournament_name;
					this.website.tournament_location = response.data.data.tournament_location;
					this.website.tournament_date = response.data.data.tournament_dates;
					this.website.domain_name = response.data.data.domain_name;
					this.website.linked_tournament = response.data.data.linked_tournament != null ? response.data.data.linked_tournament : '';
					this.website.google_analytics_id = response.data.data.google_analytics_id;
					this.website.primary_color = response.data.data.primary_color;
					this.website.secondary_color = response.data.data.secondary_color;
					this.website.heading_font = response.data.data.heading_font;
					this.website.body_font = response.data.data.body_font;
					this.website.pages = response.data.data.pageTreeArray;
				},
				(error) => {
				  // if no Response Set Zero
				  //
				}
			);
		},
		setSponsors(sponsors) {
			this.website.sponsors = sponsors;
		},
	}
}
</script>