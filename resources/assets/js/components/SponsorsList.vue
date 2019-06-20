<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="sponsors.length" v-model="sponsors" :options="{draggable:'.sponsor-logo-item', handle: '.sponsor-logo-handle'}">
			  	<div class="draggable--section-card sponsor-logo-item" v-for="(sponsor, index) in sponsors" :key="index">
			  		<div class="draggable--section-card-header">
				  		<div class="draggable--section-card-header-panel">
				  			<div class="d-flex align-items-center">
				  				<div class="thumb">
				  					<img :src="sponsor.logo">
				  				</div>
				        	<div class="draggable--section-card-header-panel-text-area">
				        		<div>{{ sponsor.name }}</div>
				        		<div>{{ sponsor.website }}</div>
					  			</div>
				  			</div>
				        	<div class="draggable--section-card-header-icons">
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="deleteSponsor(index)">
						        	<i class="fas fa-trash"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editSponsor(sponsor, index)">
						        	<i class="fas fa-pencil"></i>
						        </a>
						        <a class="text-primary sponsor-logo-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fas fa-bars"></i>
						        </a>
					      </div>
				      	</div>
				      	<!-- Add child tags like draggable--section-child-1 -->
				      </div>
			    </div>
			</draggable>
			<p v-else class="text-muted">{{ $lang.no_sponsors_found }}</p>
			<button type="button" class="btn btn-primary" @click="addSponsor()">{{ $lang.website_add_an_sponsor }}</button>
			<sponsor-modal :currentSponsorOperation="currentSponsorOperation" @storeSponsor="storeSponsor" @updateSponsor="updateSponsor"></sponsor-modal>
		</div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import SponsorModal  from  './SponsorModal.vue';
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
			SponsorModal,
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
			// Get all sponsors
			this.getSponsorsList();
			this.$root.$on('getSponsors', this.getSponsors);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getSponsors');
    },
		methods: {
			getSponsorsList() {
				if(this.getWebsite) {
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
				}
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