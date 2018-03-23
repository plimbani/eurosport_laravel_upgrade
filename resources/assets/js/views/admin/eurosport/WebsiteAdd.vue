<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_information}}</strong></h6>
				<form name="frm_website_add_edit" enctype="multipart/form-data">
					<div class="row justify-content-between">
						<div class="col-md-6" v-if="this.isAdmin">
							<div class="form-group row" :class="{'has-error': errors.has('website.tournament_name') }">
								<label class="col-sm-4 form-control-label">{{$lang.tournament_name}}*</label>
								<div class="col-sm-8">
										<input type="text" class="form-control" placeholder="Enter the name of your tournament"
										v-model="website.tournament_name" name="tournament_name" data-vv-as="tournament name" v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
										<i v-show="errors.has('tournament_name')" class="fa fa-warning"></i>
										<span class="help is-danger" v-show="errors.has('tournament_name')">{{ errors.first('tournament_name') }}</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{$lang.tournament_dates}}*</label>
								<div class="col-sm-8">
										<input type="text" class="form-control" placeholder="Enter the tournament date"
										v-model="website.tournament_date" name="tournament_date" data-vv-as="tournament dates" v-validate="'required'" :class="{'is-danger': errors.has('tournament_date') }">
										<i v-show="errors.has('tournament_date')" class="fa fa-warning"></i>
										<span class="help is-danger" v-show="errors.has('tournament_date')">{{ errors.first('tournament_date') }}</span>
								</div>
							</div>
							<div class="form-group row" :class="{'has-error': errors.has('website.tournament_location') }">
								<label class="col-sm-4 form-control-label">{{$lang.tournament_location_field}}*</label>
								<div class="col-sm-8">
										<input type="text" class="form-control" placeholder="Enter the location"
										v-model="website.tournament_location" name="tournament_location" data-vv-as="tournament location" v-validate="'required'" :class="{'is-danger': errors.has('tournament_location') }">
										<i v-show="errors.has('tournament_location')" class="fa fa-warning"></i>
										<span class="help is-danger" v-show="errors.has('tournament_location')">{{ errors.first('tournament_location') }}</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{$lang.domain_name}}</label>
								<div class="col-sm-8">
										<input type="text" class="form-control" placeholder="Enter the domain name" v-validate="domainNameValidationRules" :class="{'is-danger': errors.has('domain_name') }" v-model="website.domain_name" name="domain_name">
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
							<div class="form-group row mb-0">
								<div class="col-sm-8">
										<div class="checkbox d-flex align-items-center">
											<div class="c-input">
												<input type="checkbox" v-bind:id="'is_website_offline'" class="euro-checkbox" v-model="website.is_website_offline" :true-value="1" :false-value="0" @change="resetOfflineRedirectUrl()" />
												<label class="mb-0" v-bind:for="'is_website_offline'">{{$lang.is_website_offline}}</label>
											</div>
										</div>
								</div>
								<div class="col-sm-12 mt-2" v-if="website.is_website_offline == 1">
									<input type="text" class="form-control" v-validate="offlineRedirectUrlValidationRules" :class="{'is-danger': errors.has('offline_redirect_url') }" :placeholder="$lang.offline_redirect_url"
										v-model="website.offline_redirect_url" name="offline_redirect_url">
									<span class="help is-danger" v-show="errors.has('offline_redirect_url')">{{ $lang.offline_redirect_url_error_message }}</span>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group row" v-if="this.isAdmin">
								<label class="col-sm-12 form-control-label">{{$lang.tournament_logo}}</label>
								<div class="col-sm-12">
										<div class="row align-items-center">
											<div class="col-sm-3">
												<transition-image v-if="tournament_logo_image != ''" :image_url="tournament_logo_image" :image_class="'img-fluid'"></transition-image>
												<img v-if="tournament_logo_image == ''" src="http://placehold.it/250x250?text=No%20image" class="img-fluid" />
											</div>
											<div class="col-sm-9">
												<button v-if="tournament_logo_image != '' && is_tournament_logo_uploading == false" class="btn btn-default" @click="removeImage($event)">{{$lang.tournament_tournament_remove_button}}</button>
												<button v-else :disabled="is_tournament_logo_uploading" type="button" class="btn btn-default" @click="selectTournamentLogo()">{{is_tournament_logo_uploading ? $lang.uploading :$lang.tournament_tournament_choose_button}}</button>
												<input type="file" id="selectFile" style="display:none;" @change="onTournamentLogoChange($event)">
											</div>
										</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 form-control-label">{{$lang.social_sharing_graphic}}</label>
								<div class="col-sm-12">
										<div class="row align-items-center">
											<div class="col-sm-3">
												<transition-image v-if="social_sharing_graphic_image != ''" :image_url="social_sharing_graphic_image" :image_class="'img-fluid'"></transition-image>
												<img v-if="social_sharing_graphic_image == ''" src="http://placehold.it/250x250?text=No%20image" class="img-fluid" />
											</div>
											<div class="col-sm-9">
												<button v-if="social_sharing_graphic_image != '' && is_social_sharing_image_uploading == false" class="btn btn-default" @click="removeSocialSharingImage($event)">{{$lang.tournament_tournament_remove_button}}</button>
												<button v-else :disabled="is_social_sharing_image_uploading" type="button" class="btn btn-default" name="btnSelect" @click="selectSocialSharingGraphic()">{{is_social_sharing_image_uploading ? $lang.uploading : $lang.tournament_tournament_choose_button}}</button>
												<input type="file" id="select_file_social_sharing" style="display:none;" @change="onSocialSharingGraphicImageChange($event)">
											</div>
										</div>
								</div>
							</div>
						</div>
					</div>
					<hr class="my-4">
					<div class="row justify-content-between">
						<div class="col-md-12">
							<h6><strong>{{$lang.website_customisation}}</strong></h6>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-12 form-control-label">{{$lang.website_color}}</label>
								<div class="col-md-12">
									<div v-for="color in customisation.colors" class="websiteColourBox pull-left mr-2" :style="{'background-color': color}" @click="setWebsiteColor(color)" :class="{ 'website-color-active' : website.color == color }">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-sm-12 form-control-label">{{$lang.website_font}}</label>
										<div class="col-md-12" v-for="font in customisation.fonts">
											<div class="radio mb-2">
												<div class="r-input">
													<input type="radio" name="font" v-bind:id="font" class="euro-radio" v-model="website.font" :value="font" />
													<label v-bind:for="font">{{ font }}</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<!-- Preview section -->
						</div>
					</div>
					<hr class="my-4">
					<div class="row justify-content-between">
						<div class="col-md-12">
							<h6><strong>{{$lang.website_sponsors}}</strong></h6>
						</div>
						<div class="col-sm-6">
							<sponsors-list @setSponsors="setSponsors"></sponsors-list>
						</div>
					</div>
					<hr class="my-4" v-if="this.isAdmin">
					<div class="row justify-content-between" v-if="this.isAdmin">
						<div class="col-md-12">
							<h6><strong>{{$lang.website_page_permission}}</strong></h6>
						</div>
						<div class="col-md-12">
							<div class="form-group row">
								<div class="col-md-6">
									<ul class="pl-0">
										<li class="row mb-2 align-items-center">
											<div class="col-sm-8"><strong>Page</strong></div>
											<div class="col-sm-2"><strong>Enable</strong></div>
											<div class="col-sm-2"><strong>Publish</strong></div>
										</li>
										<li class="row mb-2 align-items-center" v-for="page in website.pages" v-if="page.is_permission_changeable != 0">
											<div class="col-sm-8">{{ page.title }}</div>
											<div class="col-sm-2">
												<div class="checkbox d-flex align-items-center">
													<div class="c-input">
														<input type="checkbox" v-bind:id="`enable-${page.name}`" class="euro-checkbox" v-model="page.is_enabled" :true-value="1" :false-value="0" />
														<label class="mb-0" v-bind:for="`enable-${page.name}`"></label>
													</div>
												</div>
											</div>
											<div class="col-sm-2">
												<div class="checkbox d-flex align-items-center">
													<div class="c-input">
														<input type="checkbox" v-bind:id="`publish-${page.name}`" class="euro-checkbox" v-model="page.is_published" :true-value="1" :false-value="0" />
														<label class="mb-0" v-bind:for="`publish-${page.name}`"></label>
													</div>
												</div>
											</div>
											<ul class="col-sm-12" v-if="page.children && page.children.length > 0">
												<li class="row mt-2" v-for="childPage in page.children">
													<div class="col-sm-8"><span class="ml-2">- {{ childPage.title }}</span></div>
													<div class="col-sm-2">
														<div class="checkbox d-flex align-items-center" :class="[isParentEnabled(page, childPage) ? 'is-disabled' : '']">
															<div class="c-input">
																<input type="checkbox" v-bind:id="`enable-${childPage.name}`" class="euro-checkbox" v-model="childPage.is_enabled" :true-value="1" :false-value="0" />
																<label class="mb-0" v-bind:for="`enable-${childPage.name}`"></label>
															</div>
														</div>
													</div>
													<div class="col-sm-2">
														<div class="checkbox d-flex align-items-center" :class="[isParentPublished(page, childPage) ? 'is-disabled' : '']">
															<div class="c-input">
																<input type="checkbox" v-bind:id="`publish-${childPage.name}`" class="euro-checkbox" v-model="childPage.is_published" :true-value="1" :false-value="0" />
																<label class="mb-0" v-bind:for="`publish-${childPage.name}`"></label>
															</div>
														</div>
													</div>
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
				<div class="pull-right">
					<button :disabled="isImageUploading" class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
var offlineRedirectUrl = null;
var moment = require('moment');
import _ from 'lodash';
import Tournament from '../../../api/tournament.js';
import Website from '../../../api/website.js';
import SponsorsList from '../../../components/SponsorsList.vue';
import TransitionImage from '../../../components/TransitionImage.vue';

export default {
	components: {
		SponsorsList,
		TransitionImage,
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
				is_website_offline: 0,
				offline_redirect_url: null,
				tournament_logo:'',
				social_sharing_graphic: '',
				publishedTournaments: null,
				color: '',
				font: '',
				pages: [],
				sponsors: [],
			},
			customisation: {
				colors: [],
				fonts: [],
			},
			tournament_logo_image: '',
			social_sharing_graphic_image: '',
			is_tournament_logo_uploading: false,
			is_social_sharing_image_uploading: false,
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_add',
		};

		if (_.indexOf(['Super.administrator', 'Master.administrator', 'Internal.administrator'], this.$store.state.Users.userDetails.role_slug) > -1)
		{
			this.getAllPublishedTournaments();			
		}


		this.getWebsiteCustomisationOptions();
		this.website.websiteId = this.$store.state.Website.id;
		if(this.website.websiteId) {
			currentNavigationData.currentPage = 'Edit Website';
			this.getWebsiteSummary();
		} else {
			currentNavigationData.currentPage = 'Create Website';
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
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {
		userDetails: function() {
			return this.$store.state.Users.userDetails
		},
    isImageUploading: function() {
      return (this.is_tournament_logo_uploading || this.is_social_sharing_image_uploading);
    },
    domainNameValidationRules: function() {
    	return this.domainAndOfflineRedirectUrlValidation();
    },
    offlineRedirectUrlValidationRules: function() {
    	return this.domainAndOfflineRedirectUrlValidation();
    },
	},
	methods: {
		next() {
			this.$validator.validateAll().then(
			(response) => {
        if(response) {
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
        }
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
			var reader  = new FileReader();

			if (!files.length)
			 return;

			if(Plugin.ValidateImageType(files[0]) == false) {
				toastr['error']('Tournament logo is not a valid image', 'Error');
				return;
			}

			// Read image
			reader.addEventListener("load", function () {
				vm.tournament_logo_image = reader.result;
			}, false);
			if (files[0]) {
				reader.readAsDataURL(files[0]);
			}

			this.is_tournament_logo_uploading = true;
      var formData = new FormData();
      formData.append('image', files[0]);
      axios.post('/api/websites/uploadTournamentLogo', formData).then(
	      (response)=> {
	      	this.tournament_logo_image = response.data;
	      	this.is_tournament_logo_uploading = false;
	      },
	      (error)=>{
	      }
      );
		},
		onSocialSharingGraphicImageChange(e) {
			var vm = this;
			var files = e.target.files || e.dataTransfer.files;

			if (!files.length)
				return;

			if(Plugin.ValidateImageType(files[0]) == false) {
				toastr['error']('Social sharing graphic is not a valid image', 'Error');
				return;
			}

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
						vm.is_social_sharing_image_uploading = true;
			      var formData = new FormData();
			      formData.append('image', files[0]);
			      axios.post('/api/websites/uploadSocialGraphic', formData).then(
				      (response)=> {
				      	vm.social_sharing_graphic_image = response.data;
				      	vm.is_social_sharing_image_uploading = false;
				      },
				      (error)=>{
				      }
			      );

					}
				};
			};

			reader.readAsDataURL(files[0]);
		},
		removeImage: function (e) {
			this.tournament_logo_image = '';
			$('#selectFile').val('');
			e.preventDefault();
		},
		removeSocialSharingImage: function (e) {
			this.social_sharing_graphic_image = '';
			$('#select_file_social_sharing').val('');
			e.preventDefault();
		},
		getWebsiteCustomisationOptions() {
			Website.getWebsiteCustomisationOptions().then(
				(response) => {
					this.customisation.colors = response.data.data.colors;
					this.customisation.fonts = response.data.data.fonts;
				},
				(error) => {
				}
			)
		},
		setWebsiteColor(colour) {
			this.website.color = colour;
		},
		getWebsiteSummary() {
			Website.websiteSummaryData(this.website.websiteId).then(
				(response) => {
					this.tournament_logo_image = response.data.data.tournament_logo !=null ? response.data.data.tournament_logo : '';
					this.social_sharing_graphic_image = response.data.data.social_sharing_graphic !=null ? response.data.data.social_sharing_graphic : '';
					this.website.tournament_name = response.data.data.tournament_name;
					this.website.tournament_location = response.data.data.tournament_location;
					this.website.tournament_date = response.data.data.tournament_dates;
					this.website.domain_name = response.data.data.domain_name;
					this.website.linked_tournament = response.data.data.linked_tournament != null ? response.data.data.linked_tournament : '';
					this.website.google_analytics_id = response.data.data.google_analytics_id;
					this.website.is_website_offline = response.data.data.is_website_offline;
					this.website.offline_redirect_url = offlineRedirectUrl = response.data.data.offline_redirect_url;
					this.website.color = response.data.data.color;
					this.website.font = response.data.data.font;
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
		isParentEnabled(page, childPage) {
			if(page.is_enabled == 1) {
				return false;
			}
			childPage.is_enabled = 0;
			return true;
		},
		isParentPublished(page, childPage) {
			if(page.is_published == 1) {
				return false;
			}
			childPage.is_published = 0;
			return true;
		},
		selectTournamentLogo() {
			$('#selectFile').trigger('click');
		},
		selectSocialSharingGraphic() {
			$('#select_file_social_sharing').trigger('click');
		},
		resetOfflineRedirectUrl() {
			this.website.offline_redirect_url = offlineRedirectUrl;
		},
		domainAndOfflineRedirectUrlValidation() {
			var rules = { url: true };
    	if(this.website.is_website_offline == 1) {
    		rules.required = true;
    	}
    	return rules;
		},
	}
}
</script>
