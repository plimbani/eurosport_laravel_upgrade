<template>
	<div class="modal" id="organiser_logo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentOrganiserLogoOperation == 'add' ? $lang.homepage_add_an_organiser_logo : $lang.homepage_edit_organiser_logo }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('logo') }">
            <label class="col-sm-5 form-control-label">{{ $lang.homepage_organiser_logo }}*</label>
            <div class="col-sm-6">
              <img v-show="isLoad" :src="getOrganiserLogo" class="thumb-size" @load="loaded"/>
              <img class="thumb" v-show="!isLoad" src="/images/loader2.gif">
              <button :disabled="isOrganiserLogoUploading" type="button" class="btn btn-default" @click="selectLogo()">{{isOrganiserLogoUploading ? $lang.uploading : $lang.tournament_tournament_choose_button}}</button>
              <input type="file" id="organiser_logo" style="display:none;" @change="onLogoChange">
              <input type="hidden" v-model="formValues.logo" name="logo" v-validate="'required'" />
              <span class="help is-danger" v-show="errors.has('logo')">{{ errors.first('logo') }}</span>
							<p class="help-block text-muted pb-0 mb-0">Preferred size: 200px × 200px (jpg, png or gif)</p>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.homepage_organiser_name }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="'required'"
                :class="{'is-danger': errors.has('name') }"
                name="name" type="text"
                class="form-control" placeholder="Enter name">
                <i v-show="errors.has('name')" class="fas fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}
                </span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $lang.cancel_button }}</button>
          <button :disabled="isOrganiserLogoUploading" type="button" class="btn btn-primary" @click="validateForm()">{{ $lang.save_button }}</button>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import { ErrorBag } from 'vee-validate';

	export default {
		props: ['currentOrganiserLogoOperation'],
		data() {
			return {
				formValues: {
					id: '',
					name: '',
					logo: '',
				},
				isOrganiserLogoUploading: false,
				isLoad: false,
			};
		},
		created() {
	    this.$root.$on('setOrganiserLogoData', this.setOrganiserLogoData);
	  },
	  beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setOrganiserLogoData');
    },
	  computed: {
	  	getOrganiserLogo() {
	  		return this.formValues.logo == '' ? '/assets/img/noimage.png' : this.formValues.logo;
	  	},
	  },
		methods: {
	    loaded() {
	      this.isLoad = true;
	    },
			validateForm() {
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentOrganiserLogoOperation == 'add') {
							this.$emit('storeOrganiserLogo', this.formValues);
						} else {
							this.$emit('updateOrganiserLogo', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setOrganiserLogoData(organiserLogoData) {
				this.formValues.id = organiserLogoData.id;
				this.formValues.name = organiserLogoData.name;
				this.formValues.logo = organiserLogoData.logo;
				this.clearErrorMsgs();
			},
			selectLogo() {
				$('#organiser_logo').trigger('click');
			},
			onLogoChange(e) {
				var vm = this;
				var files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return;

		    if(Plugin.ValidateImageType(files[0]) == false) {
	        toastr['error']('Organiser logo is not a valid image', 'Error');
	        return;
	      }

				vm.isOrganiserLogoUploading = true;
	      var formData = new FormData();
	      formData.append('image', files[0]);
	      axios.post('/api/websites/uploadOrganiserLogo', formData).then(
		      (response)=> {
		      	vm.formValues.logo = response.data;
		      	vm.isOrganiserLogoUploading = false;
		      	this.isLoad = false;
		      },
		      (error)=>{
		      }
	      );
			},
		},
	};
</script>
