<template>
	<div>
		<draggable v-model="organiserLogos" :options="{draggable:'.organiser-logo-item', handle: '.organiser-logo-handle'}">
	  	<div class="col-sm-12 organiser-logo-item" v-for="(organiserLogo, index) in organiserLogos" :key="organiserLogo.id">
        {{ organiserLogo.name }}
        <a class="text-primary" href="javascript:void(0)"
        	@click="deleteOrganiserLogo(index)">
        	<i class="jv-icon jv-dustbin"></i>
        </a>
        <a class="text-primary" href="javascript:void(0)"
        	@click="editOrganiserLogo(organiserLogo, index)">
        	<i class="jv-icon jv-edit"></i>
        </a>
        <a class="text-primary organiser-logo-handle draggable-handle" href="javascript:void(0)">
        	<i class="fa fa-bars"></i>
        </a>
	    </div>
	    <button slot="footer" type="button" class="btn btn-primary" @click="addOrganiserLogo()">{{ $lang.homepage_add_organiser }}</button>
		</draggable>
		<organiser-logo-modal :currentOrganiserLogoOperation="currentOrganiserLogoOperation" @storeOrganiserLogo="storeOrganiserLogo" @updateOrganiserLogo="updateOrganiserLogo"></organiser-logo-modal>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import OrganiserLogoModal  from  './OrganiserLogoModal.vue';
	import _ from 'lodash';

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
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
		},
		mounted() {
			// Get all organisers
			this.getOrganisers();
		},
		methods: {
			getOrganisers() {
				Website.getOrganisers(this.getWebsite).then(
	        (response) => {
	          this.organiserLogos = response.data.data;
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
		},
	}
</script>