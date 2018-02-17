<template>
	<div class="modal" id="website_location_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentLocationOperation == 'add' ? $lang.venue_add_a_location : $lang.venue_edit_location }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-4 form-control-label">{{ $lang.venue_name }}*</label>
            <div class="col-sm-7">
                <input v-model="formValues.name" v-validate="{'required':true}" :class="{'is-danger': errors.has('name') }" data-vv-as="venue name" name="name" type="text" class="form-control" :placeholder="$lang.venue_name">
                <i v-show="errors.has('name')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}<br>
                </span>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('address') }">
            <label class="col-sm-4 form-control-label">{{ $lang.venue_address }}*</label>
            <div class="col-sm-7">
                <textarea rows="5" v-model="formValues.address" v-validate="{'required':true}" :class="{'is-danger': errors.has('address') }"  data-vv-as="venue address" name="address" class="form-control" :placeholder="$lang.venue_address"></textarea>
                <span class="help is-danger" v-show="errors.has('address')">{{ errors.first('address') }}<br>
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
				formValues: {
					id: '',
					name: '',
					address: '',
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
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentLocationOperation == 'add') {
							this.$emit('storeLocation', this.formValues);
						} else {
							this.$emit('updateLocation', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setLocationData(locationData) {
				this.formValues.id = locationData.id;
				this.formValues.name = locationData.name;
				this.formValues.address = locationData.address;
				this.clearErrorMsgs();
			},
		},
	};
</script>