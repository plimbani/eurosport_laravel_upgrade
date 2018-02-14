<template>
	<div class="modal" :id="`history_year_age_category_modal_${modalIndex}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentHistoryYearAgeCategoryOperation == 'add' ? $lang.add_age_category : $lang.edit_age_category }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.age_category_label }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="{'required':true}" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" :placeholder="$lang.age_category_label">
                <i v-show="errors.has('name')" class="fa fa-warning"></i>
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
    props: ['currentHistoryYearAgeCategoryOperation', 'modalIndex'],
    data() {
      return {
        formValues: {
          id: '',
          name: ''
        },
      };
    },
    created() {
      this.$root.$on('setHistoryYearAgeCategoryData', this.setHistoryYearAgeCategoryData);
    },
    computed: {

    },
    methods: {
      validateForm() {
        this.$validator.validateAll().then((response) => {
          if(response) {
            if(this.currentHistoryYearAgeCategoryOperation == 'add') {
              this.$emit('storeHistoryYearAgeCategory', this.formValues);
            } else {
              this.$emit('updateHistoryYearAgeCategory', this.formValues);
            }
          }
        }).catch(() => {
          // fail stuff
        });
      },
      setHistoryYearAgeCategoryData(historyYearData) {
        this.formValues.id = historyYearData.id;
        this.formValues.name = historyYearData.name;
        this.errors.clear();
      },
    },
  };
</script>