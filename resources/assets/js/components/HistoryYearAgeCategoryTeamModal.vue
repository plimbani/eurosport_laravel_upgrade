<template>
	<div class="modal" :id="`history_year_age_category_team_modal_${modalIndex}`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentHistoryYearAgeCategoryTeamOperation == 'add' ? $lang.add_a_team : $lang.edit_team }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('name') }">
            <label class="col-sm-5 form-control-label">{{ $lang.team_name }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.name" v-validate="{'required':true}" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" :placeholder="$lang.team_name">
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
    props: ['currentHistoryYearAgeCategoryTeamOperation', 'modalIndex'],
    data() {
      return {
        formValues: {
          id: '',
          name: ''
        },
      };
    },
    created() {
      this.$root.$on('setHistoryYearAgeCategoryTeamData', this.setHistoryYearAgeCategoryTeamData);
    },
    computed: {

    },
    methods: {
      validateForm() {
        this.$validator.validateAll().then((response) => {
          if(response) {
            if(this.currentHistoryYearAgeCategoryTeamOperation == 'add') {
              this.$emit('storeHistoryYearAgeCategoryTeam', this.formValues);
            } else {
              this.$emit('updateHistoryYearAgeCategoryTeam', this.formValues);
            }
          }
        }).catch(() => {
          // fail stuff
        });
      },
      setHistoryYearAgeCategoryTeamData(historyYearAgeCategoryTeamData) {
        this.formValues.id = historyYearAgeCategoryTeamData.id;
        this.formValues.name = historyYearAgeCategoryTeamData.name;
        this.errors.clear();
      },
    },
  };
</script>