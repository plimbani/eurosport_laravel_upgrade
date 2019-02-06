<template>
	<div class="modal" id="age_category_team_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentAgeCategoryTeamOperation == 'add' ? $lang.add_a_team : $lang.edit_team }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.team_name }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="{'required':true}" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control">
                <i v-show="errors.has('name')" class="fas fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('name')">{{ errors.first('name') }}<br>
                </span>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('country') }">
            <label class="col-sm-5 form-control-label">{{ $lang.country }}*</label>
            <div class="col-sm-6">
              <select class="form-control ls-select2" v-validate="{'required':true}" name="country" v-model="formValues.country">
								<option value="">Please select</option>
								<option v-for="country in countries" v-bind:value="country">
										{{ country.name }}
								</option>
							</select>
							<span class="help is-danger" v-show="errors.has('country')">{{ errors.first('country') }}</span>
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
		props: ['currentAgeCategoryTeamOperation', 'countries'],
		data() {
			return {
				formValues: {
					id: '',
					name: '',
					country: '',
				},
			};
		},
		created() {
      this.$root.$on('setAgeCategoryTeamData', this.setAgeCategoryTeamData);
      this.$root.$on('clearAgeCategoryTeamError', this.clearAgeCategoryTeamError);
    },
    beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setAgeCategoryTeamData');
      this.$root.$off('clearAgeCategoryTeamError');
    },
    computed: {
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentAgeCategoryTeamOperation == 'add') {
							this.$emit('storeAgeCategoryTeam', this.formValues);
						} else {
							this.$emit('updateAgeCategoryTeam', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setAgeCategoryTeamData(ageCategoryTeamData) {
				this.formValues.id = ageCategoryTeamData.id;
				this.formValues.name = ageCategoryTeamData.name;
				this.formValues.country = ageCategoryTeamData.country;
				this.clearErrorMsgs();
			},
		},
	};
</script>