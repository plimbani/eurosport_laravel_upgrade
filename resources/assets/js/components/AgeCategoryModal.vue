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
          <div class="form-group row" :class="{'has-error': errors.has('category') }">
            <label class="col-sm-5 form-control-label">{{ $lang.category }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.category" v-validate="{'required':true, 'max': 25}" :class="{'is-danger': errors.has('category') }" name="category" type="text" class="form-control" :placeholder="$lang.category">
                <i v-show="errors.has('category')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('category')">{{ errors.first('category') }}<br>
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
					category: '',
				},
			};
		},
		created() {
      this.$root.$on('setAgeCategoryData', this.setAgeCategoryData);
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
				this.formValues.category = ageCategoryData.category;
				this.errors.clear();
			},
		},
	};
</script>