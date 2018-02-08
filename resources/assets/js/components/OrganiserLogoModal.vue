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
              <img :src="getOrganiserLogo" width="100px" height="100px"/>
              <button type="button" class="btn btn-default" @click="selectLogo()">{{$lang.tournament_tournament_choose_button}}</button>
              <input type="file" id="organiser_logo" style="display:none;" @change="onLogoChange">
              <input type="hidden" v-model="formValues.logo" name="logo" v-validate="'required'" />
              <span class="help is-danger" v-show="errors.has('logo')">{{ errors.first('logo') }}</span>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.homepage_organiser_name }}*</label>
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
		props: ['currentOrganiserLogoOperation'],
		data() {
			return {
				formValues: {
					id: '',
					name: '',
					logo: '',
				},
			};
		},
		created() {
	    this.$root.$on('setOrganiserLogoData', this.setOrganiserLogoData);
	  },
	  computed: {
	  	getOrganiserLogo() {
	  		return this.formValues.logo == '' ? 'http://placehold.it/250x250' : this.formValues.logo;
	  	},
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then(() => {
					if(this.currentOrganiserLogoOperation == 'add') {
						this.$emit('storeOrganiserLogo', this.formValues);
					} else {
						this.$emit('updateOrganiserLogo', this.formValues);
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setOrganiserLogoData(organiserLogoData) {
				this.formValues.id = organiserLogoData.id;
				this.formValues.name = organiserLogoData.name;
				this.formValues.logo = organiserLogoData.logo;
			},
			selectLogo() {
				$('#organiser_logo').trigger('click');
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