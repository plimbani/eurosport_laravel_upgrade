<template>
	<div class="modal" id="sponsor_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentSponsorOperation == 'add' ? $lang.website_add_sponsor : $lang.website_edit_sponsor }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('logo') }">
            <label class="col-sm-5 form-control-label">{{ $lang.website_sponsor_logo }}*</label>
            <div class="col-sm-6">
              <img :src="getSponsorLogo" width="100px" height="100px"/>
              <button type="button" class="btn btn-default" @click="selectLogo()">{{$lang.tournament_tournament_choose_button}}</button>
              <input type="file" id="sponsor_logo" style="display:none;" @change="onLogoChange">
              <input type="hidden" v-model="formValues.logo" name="logo" v-validate="'required'" />
              <span class="help is-danger" v-show="errors.has('logo')">{{ errors.first('logo') }}</span>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.website_sponsor_name }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="'required'"
                :class="{'is-danger': errors.has('name') }"
                name="name" type="text"
                class="form-control" placeholder="Enter name">
                <i v-show="errors.has('name')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}
                </span>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.website_sponsor_website }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.website" v-validate="{'required':true, 'url':true}"
                :class="{'is-danger': errors.has('website') }"
                name="website" type="text"
                class="form-control" :placeholder="$lang.website_sponsor_website">
                <i v-show="errors.has('website')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('website')">{{ errors.first('website') }}
                </span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $lang.cancel_button }}</button>
          <button type="button" class="btn btn-primary" @click="validateForm()">{{ $lang.save_button }}</button>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import { ErrorBag } from 'vee-validate';

	export default {
		props: ['currentSponsorOperation'],
		data() {
			return {
				formValues: {
					id: '',
					name: '',
					logo: '',
					website: '',
				},
			};
		},
		created() {
	    this.$root.$on('setSponsorData', this.setSponsorData);
	  },
	  computed: {
	  	getSponsorLogo() {
	  		return this.formValues.logo == '' ? 'http://placehold.it/250x250' : this.formValues.logo;
	  	},
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then(() => {
					if(this.currentSponsorOperation == 'add') {
						this.$emit('storeSponsor', this.formValues);
					} else {
						this.$emit('updateSponsor', this.formValues);
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setSponsorData(sponsorData) {
				this.formValues.id = sponsorData.id;
				this.formValues.name = sponsorData.name;
				this.formValues.logo = sponsorData.logo;
				this.formValues.website = sponsorData.website;
				this.$validator.clean();
			},
			selectLogo() {
				$('#sponsor_logo').trigger('click');
			},
			onLogoChange(e) {
				var vm = this;
				var files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return;

				var reader = new FileReader();
				reader.onload = (r) => {
					vm.formValues.logo = r.target.result;
				};

				reader.readAsDataURL(files[0]);
			},
		},
	};
</script>