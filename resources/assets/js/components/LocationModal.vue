<template>
	<div class="modal" id="location_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentLocationOperation == 'add' ? $lang.homepage_add_a_location : $lang.homepage_edit_location }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-5 form-control-label">{{ $lang.homepage_venue }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.venue_name" name="venue_name" type="text" class="form-control" v-validate="{'required':true}" :class="{'is-danger': errors.has('venue_name') }">
                <i v-show="errors.has('venue_name')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('venue_name')">{{ errors.first('venue_name') }}<br>
                </span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-5 form-control-label">{{ $lang.homepage_venue_address }}*</label>
            <div class="col-sm-6">
                <textarea v-model="formValues.venue_address" name="venue_address" class="form-control" v-validate="{'required':true}" :class="{'is-danger': errors.has('venue_address') }"></textarea>
                <i v-show="errors.has('venue_address')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('venue_address')">{{ errors.first('venue_address') }}<br>
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
		props: ['currentLocationOperation'],
		data() {
			return {
		    hasError: false,
				formValues: {
					id: '',
					venue_name: '',
					venue_address: '',
				},
			};
		},
		created() {
      this.$root.$on('setLocationData', this.setLocationData);
    },
    computed: {
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then(() => {
					if(this.currentLocationOperation == 'add') {
						this.$emit('storeLocation', this.formValues);
					} else {
						// this.$emit('updateLocation', this.formValues);
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setLocationData(locationData) {
				// console.log(locationData,'locationData');
				this.formValues.id = locationData.id;
				this.formValues.venue_name = locationData.venue_name;
				this.formValues.venue_address = locationData.venue_address;
				this.errors.clear();
			},
		},
	};
</script>