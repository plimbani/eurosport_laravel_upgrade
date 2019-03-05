<template>
	<div class="modal" id="history_year_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentYearOperation == 'add' ? $lang.add_history_year : $lang.edit_history_year }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('year') }">
            <label class="col-sm-5 form-control-label">{{ $lang.year }}*</label>
            <div class="col-sm-6">
                <input maxlength="4" v-model="formValues.year" v-validate="{'required':true, 'digits':4}" :class="{'is-danger': errors.has('year') }" name="year" type="text" class="form-control" :placeholder="$lang.year">
                <i v-show="errors.has('year')" class="fas fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('year')">{{ $lang.add_history_year_error_message }}<br>
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
    props: ['currentYearOperation'],
    data() {
      return {
        formValues: {
          id: '',
          year: '',
          age_categories: [],
        },
      };
    },
    created() {
      this.$root.$on('setHistoryYearData', this.setHistoryYearData);
    },
    beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setHistoryYearData');
    },
    computed: {

    },
    methods: {
      validateForm() {
        this.$validator.validateAll().then((response) => {
          if(response) {
            if(this.currentYearOperation == 'add') {
              this.$emit('storeHistoryYear', this.formValues);
            } else {
              this.$emit('updateHistoryYear', this.formValues);
            }
          }
        }).catch(() => {
          // fail stuff
        });
      },
      setHistoryYearData(historyYearData) {
        this.formValues.id = historyYearData.id;
        this.formValues.year = historyYearData.year;
        this.formValues.age_categories = historyYearData.age_categories;
        this.clearErrorMsgs();
      },
    },
  };
</script>