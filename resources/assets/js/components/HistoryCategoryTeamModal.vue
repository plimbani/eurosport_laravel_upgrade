<template>
	<div class="modal" id="category_team_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ categoryTeamOperation == 'add' ? $lang.add_a_team : $lang.edit_team }}</h5>
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
    props: ['categoryTeamOperation', 'yearIndex', 'categoryIndex'],
    data() {
      return {
        formValues: {
          id: '',
          name: ''
        },
      };
    },
    created() {
      this.$root.$on('setCategoryTeamData', this.setCategoryTeamData);
    },
    computed: {

    },
    methods: {
      validateForm() {
        this.$validator.validateAll().then((response) => {
          if(response) {
            if(this.categoryTeamOperation == 'add') {
              this.$emit('storeCategoryTeam', this.formValues);
            } else {
              this.$emit('updateCategoryTeam', this.formValues);
            }
          }
        }).catch(() => {
          // fail stuff
        });
      },
      setCategoryTeamData(categoryTeamData) {
        var that = this;
        this.formValues.id = categoryTeamData.id;
        this.formValues.name = categoryTeamData.name;
        this.clearErrorMsgs();
      },
    },
  };
</script>