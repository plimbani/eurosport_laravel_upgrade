<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="organiserLogos.length" v-model="organiserLogos" :options="{draggable:'.organiser-logo-item', handle: '.organiser-logo-handle'}">
		  	<div class="draggable--section-card organiser-logo-item" v-for="(organiserLogo, index) in organiserLogos" :key="index">
		  		<div class="draggable--section-card-header">
			  		<div class="draggable--section-card-header-panel">
			  			<div class="d-flex align-items-center">
			  					<transition-image :image_url="organiserLogo.logo"  :image_class="''"></transition-image>
			  				<div>{{ organiserLogo.name }}</div>
			  			</div>
			        <div class="draggable--section-card-header-icons">
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deleteOrganiserLogo(index)">
				        	<i class="fas fa-trash"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editOrganiserLogo(organiserLogo, index)">
				        	<i class="fas fa-pencil"></i>
				        </a>
				        <a class="text-primary organiser-logo-handle draggable-handle" href="javascript:void(0)">
				        	<i class="fas fa-bars"></i>
				        </a>
				      </div>
			      </div>
		      	<!-- Add child tags like draggable--section-child-1 -->
		      </div>
		    </div>
			</draggable>
			<p v-else class="text-muted">{{ $lang.no_organiser_found }}</p>
			<button type="button" class="btn btn-primary" @click="addOrganiserLogo()">{{ $lang.homepage_add_organiser }}</button>
			<organiser-logo-modal :currentOrganiserLogoOperation="currentOrganiserLogoOperation" @storeOrganiserLogo="storeOrganiserLogo" @updateOrganiserLogo="updateOrganiserLogo"></organiser-logo-modal>
		</div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import OrganiserLogoModal  from  './OrganiserLogoModal.vue';
	import _ from 'lodash';
	import TransitionImage from './TransitionImage.vue';

	export default {
		data() {
			return {
				organiserLogos: [],
				currentOrganiserLogoIndex: -1,
				currentOrganiserLogoOperation: 'add',
			};
		},
		components: {
			draggable,
			OrganiserLogoModal,
			TransitionImage,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
			getOrganiserLogoImagePath() {
				return this.$store.state.Image.organiserLogoPath;
			},
		},
		mounted() {
			// Get all organisers
			this.getOrganisers();
			this.$root.$on('getOrganiserLogos', this.getOrganiserLogos);
		},
		beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('getOrganiserLogos');
    },
		methods: {
			getOrganisers() {
				var vm = this;
				Website.getOrganisers(this.getWebsite).then(
	        (response) => {
	          vm.organiserLogos = response.data.data;
	          vm.organiserLogos = _.map(response.data.data, function(organiser) {
						  organiser.logo = vm.getOrganiserLogoImagePath + organiser.logo;
						  return organiser;
						});
	        },
	        (error) => {
	        }
	      );
			},
			addOrganiserLogo() {
				var formData = {
					id: '',
					name: '',
					logo: '',
				};
				this.currentOrganiserLogoIndex = this.organiserLogos.length;
				this.currentOrganiserLogoOperation = 'add';
				this.initializeModel(formData);
			},
			storeOrganiserLogo(organiserLogoData) {
				this.organiserLogos.push({ id: '', name: organiserLogoData.name, logo: organiserLogoData.logo });
				$('#organiser_logo_modal').modal('hide');
			},
			editOrganiserLogo(organiserLogo, index) {
				var formData = {
					id: organiserLogo.id,
					name: organiserLogo.name,
					logo: organiserLogo.logo,
				};
				this.currentOrganiserLogoIndex = index;
				this.currentOrganiserLogoOperation = 'edit';
				this.initializeModel(formData);
			},
			updateOrganiserLogo(organiserLogoData) {
				this.organiserLogos[this.currentOrganiserLogoIndex].name = organiserLogoData.name;
				this.organiserLogos[this.currentOrganiserLogoIndex].logo = organiserLogoData.logo;
				$('#organiser_logo_modal').modal('hide');
			},
			deleteOrganiserLogo(deleteIndex) {
				this.organiserLogos = _.remove(this.organiserLogos, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeModel(formData) {
				var vm = this;
				this.$root.$emit('setOrganiserLogoData', formData);
				$('#organiser_logo_modal').modal('show');
			},
			getOrganiserLogos() {
        this.$emit('setOrganiserLogos', this.organiserLogos);
      },
		},
	}
</script>