<template>
	<div class="modal" id="statistic_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentStatisticOperation == 'add' ? $lang.homepage_add_a_statistic : $lang.homepage_edit_statistic }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('statistic') }">
            <label class="col-sm-5 form-control-label">{{ $lang.homepage_statistic }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.statistic" v-validate="{'required':true, 'max': 25}" :class="{'is-danger': errors.has('statistic') }" name="statistic" type="text" class="form-control" :placeholder="$lang.homepage_statistic_model_placeholder" maxlength="25">
                <i v-show="errors.has('statistic')" class="fas fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('statistic')">{{ errors.first('statistic') }}<br>
                </span>
                <p class='help-block text-muted' v-bind:class="{'text-danger': hasError }">{{remainingCount}}/{{maxCount}} remaining characters</p>
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
		props: ['currentStatisticOperation'],
		data() {
			return {
		    maxCount: 25,
		    hasError: false,
				formValues: {
					id: '',
					statistic: '',
				},
			};
		},
		created() {
      this.$root.$on('setStatisticData', this.setStatisticData);
    },
    beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setStatisticData');
    },
    computed: {
    	remainingCount() {
	    	return this.maxCount - this.formValues.statistic.length;
	    }
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentStatisticOperation == 'add') {
							this.$emit('storeStatistic', this.formValues);
						} else {
							this.$emit('updateStatistic', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setStatisticData(statisticData) {
				this.formValues.id = statisticData.id;
				this.formValues.statistic = statisticData.statistic;
				this.clearErrorMsgs();
			},
		},
	};
</script>