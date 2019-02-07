<template>
	<div class="modal" id="age_category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentAgeCategoryOperation == 'add' ? $lang.add_a_category : $lang.edit_category }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.category }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="{'required':true}" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" data-vv-as="category" :placeholder="$lang.category">
                <i v-show="errors.has('name')" class="fas fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}<br>
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
		props: ['currentAgeCategoryOperation'],
		data() {
			return {
				formValues: {
					id: '',
					name: '',
					teams: [],
				},
			};
		},
		created() {
      this.$root.$on('setAgeCategoryData', this.setAgeCategoryData);
    },
    beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setAgeCategoryData');
    },
    computed: {

    },
		methods: {
			validateForm() {
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentAgeCategoryOperation == 'add') {
							this.$emit('storeAgeCategory', this.formValues);
						} else {
							this.$emit('updateAgeCategory', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setAgeCategoryData(ageCategoryData) {
				this.formValues.id = ageCategoryData.id;
				this.formValues.name = ageCategoryData.name;
				this.formValues.teams = ageCategoryData.teams;
				this.clearErrorMsgs();
			},
		},
	};
</script>