<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="sponsors" :options="{draggable:'.organiser-logo-item', handle: '.organiser-logo-handle'}">
		  	<div class="draggable--section-card organiser-logo-item" v-for="(sponsor, index) in sponsors" :key="sponsor.id">
		  		<div class="draggable--section-card-header">
			  		<div class="draggable--section-card-header-panel">
			  			<div>
				        <div class="float-left"><img class="thumb sponsor-thumb" :src="sponsor.logo"></div>
				        <div class="float-left">
				        	{{ sponsor.name }} <br> {{ sponsor.website }}
				  			</div>
			  			</div>
			        <div class="draggable--section-card-header-icons">
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deleteSponsor(index)">
				        	<i class="jv-icon jv-dustbin"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editSponsor(sponsor, index)">
				        	<i class="jv-icon jv-edit"></i>
				        </a>
				        <a class="text-primary organiser-logo-handle draggable-handle" href="javascript:void(0)">
				        	<i class="fa fa-bars"></i>
				        </a>
				      </div>
			      </div>
		      	<!-- Add child tags like draggable--section-child-1 -->
		      </div>
		    </div>
			</draggable>
			<button type="button" class="btn btn-primary" @click="addSponsor()">{{ $lang.website_add_sponsor }}</button>
			<website-sponsor-modal :currentSponsorOperation="currentSponsorOperation" @storeSponsor="storeSponsor" @updateSponsor="updateSponsor"></website-sponsor-modal>
		</div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import WebsiteSponsorModal  from  './WebsiteSponsorModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				sponsors: [],
				currentSponsorIndex: -1,
				currentSponsorOperation: 'add',
			};
		},
		components: {
			draggable,
			WebsiteSponsorModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
			getSponsorLogoImagePath() {
				return this.$store.state.Image.sponsorLogoPath;
			},
		},
		mounted() {
			// Get all organisers
			this.getSponsorsList();
			this.$root.$on('getSponsors', this.getSponsors);
		},
		methods: {
			getSponsorsList() {
				var vm = this;
				Website.getSponsors(this.getWebsite).then(
	        (response) => {
	          vm.sponsors = response.data.data;
	          vm.sponsors = _.map(response.data.data, function(sponsor) {
						  sponsor.logo = vm.getSponsorLogoImagePath + sponsor.logo;
						  return sponsor;
						});
	        },
	        (error) => {
	        }
	      );
			},
			addSponsor() {
				var formData = {
					id: '',
					name: '',
					logo: '',
					website: '',
				};
				this.currentSponsorIndex = this.sponsors.length;
				this.currentSponsorOperation = 'add';
				this.initializeModel(formData);
			},
			storeSponsor(sponsorData) {
				this.sponsors.push({ id: '', name: sponsorData.name, logo: sponsorData.logo, website: sponsorData.website });
				$('#sponsor_modal').modal('hide');
			},
			editSponsor(sponsor, index) {
				var formData = {
					id: sponsor.id,
					name: sponsor.name,
					logo: sponsor.logo,
					website: sponsor.website,
				};
				this.currentSponsorIndex = index;
				this.currentSponsorOperation = 'edit';
				this.initializeModel(formData);
			},
			updateSponsor(sponsorData) {
				this.sponsors[this.currentSponsorIndex].name = sponsorData.name;
				this.sponsors[this.currentSponsorIndex].logo = sponsorData.logo;
				this.sponsors[this.currentSponsorIndex].website = sponsorData.website;
				$('#sponsor_modal').modal('hide');
			},
			deleteSponsor(deleteIndex) {
				this.sponsors = _.remove(this.sponsors, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setSponsorData', formData);
				$('#sponsor_modal').modal('show');
			},
			getSponsors() {
        this.$emit('setSponsors', this.sponsors);
      },
		},
	}
</script>