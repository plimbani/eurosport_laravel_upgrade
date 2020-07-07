<template>
<div class="modal fade" id="refreesModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{$lang.add_referees_model}}</h5>
            <button type="button" class="close" @click="closeAddRefereesModal()">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form name="frmReferee" id="frmReferee" >
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">{{$lang.add_refree_modal_first_name}}</label>
              <div class="col-sm-6">
                <input type="text" name="first_name" id="first_name"  v-validate="'required'" v-model="formValues.first_name" class="form-control"  :class="{'is-danger': errors.has('ageCategory_name') }" >
                <i v-show="errors.has('first_name')" class="fas fa-warning"> </i>

               <span class="help is-danger" v-show="errors.has('first_name')">{{$lang.add_refree_modal_validation}}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">{{$lang.add_refree_modal_last_name}}</label>
              <div class="col-sm-6">
                <input type="text" name="last_name" id="last_name"  v-validate="'required'" v-model="formValues.last_name"  class="form-control" >
                <i v-show="errors.has('last_name')" class="fas fa-warning"> </i>
                <span class="help is-danger" v-show="errors.has('last_name')">{{$lang.add_refree_modal_validation}}</span>
               </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">{{$lang.add_refree_modal_telephone}}</label>
              <div class="col-sm-6">
                <input type="text" name="telephone" id="telephone" v-model="formValues.telephone" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">{{$lang.add_refree_modal_email}}</label>
              <div class="col-sm-6">
                <input type="email"  name="email" id="email" v-model="formValues.email" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">{{$lang.add_refree_modal_category_age}}</label>
              <div class="col-sm-6">
                <!-- <select  name="ageCategories" id="ageCategories" v-model="formValues.age_group_id"  class="form-control ls-select2">
                      <option value="">Please Select</option>
                      <option value="">U11</option>
                      <option value="">U15</option>
                      <option value="">U19</option>
                  </select> -->
                  <input type="checkbox" name="chk_ageCategory" id="chk_ageCategory" v-model="formValues.is_all_age_categories_selected" :true-value="true" :false-value="false" :checked="formValues.is_all_age_categories_selected == true ? ' checked' : ''">&nbsp; Select all
                 <multiselect name="sel_ageCategory" id="sel_ageCategory" :options="competationList" :multiple="true" :hide-selected="false" :ShowLabels="false" :value="value" track-by="id"  label="category_age"   :clear-on-select="false" :Searchable="true"  @input="onChange"  @close="onTouch" @select="onSelect" @remove="onRemove"></multiselect>
                   <!-- <select name="sel_ageCategory"  v-model="formValues.age_group_id"  v-validate="'required'" v-bind:multiple="isMultiple" :class="{'is-danger': errors.has('sel_ageCategory') }"  class="form-control" id="sel_ageCategory" >
                        <option value="">Select</option>
                        <option v-for="(competation, index) in competationList" :value="competation.id">{{competation.category_age}}</option>
                    </select> -->
                    
               <span class="help is-danger" v-show="isInvalid">{{$lang.add_refree_modal_validation}}</span>
               </label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">{{$lang.add_refree_modal_availability}}</label>
              <div class="col-sm-6">
                <textarea name="availability" id="availability" v-model="formValues.comments" class="form-control" placeholder="e.g. Day 1 all day"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer d-flex flex-row justify-content-between">
            <div>
              <button type="button" v-if="refereeId!=''" class="btn btn-danger pull-left"  data-toggle="modal" data-target="#delete_modal">{{$lang.add_refree_modal_delete}}</button>
            </div>
            <div>
              <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.add_refree_modal_cancel}}</button>
              <button type="button" class="btn btn-primary" @click="saveReferee()">{{$lang.add_refree_modal_save}}</button>
            </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import DeleteModal from '../components/DeleteModal.vue'
import Multiselect from 'vue-multiselect'
import _ from 'lodash'

export default {
   props: ['formValues','tournamentId','competationList','refereeId'],
   mounted() {
    // $('#sel_ageCategory').multiSelect()
   },
  components: { Multiselect },
    data(){
    return {
      isMultiple: true,
      deleteConfirmMsg: 'Are you sure you would like to delete this referee? All information associated with this referee will be permanently deleted.',
      value: [],
      isDisabled: false,
      isTouched: false,
      isInvalid: false,
      options: []

    }
   },
   components: {
    DeleteModal
   },
    watch : {
      formValues : function (value) {
         let vm = this
          setTimeout(function(){
            vm.options = []
             vm.value = []
            let competitionOption =[]
             _.forEach(vm.competationList, function(competition,value) {
               let cmp = {'id':competition.id,'category_age':competition.category_age}
              if($.inArray(competition.id,vm.formValues.age_group_id) != -1){
                vm.value.push(cmp)
              }
              competitionOption.push(cmp)
            });
             // vm.options = competitionOption
        },1000)
      },
    },
    mounted() {
      let vm = this
      $('#refreesModal').on('click','#chk_ageCategory',function(){
        vm.value = []
        if($(this).is(':checked')){
          _.forEach(vm.competationList, function(competition,value) {
             let cmp = {'id':competition.id,'category_age':competition.category_age}
              vm.value.push(cmp)
          });
        }
      })
        
    },
    methods: {
      saveReferee ()
        {
          this.isInvalid = false
          if(this.value.length === 0) {
            this.isInvalid = true
          }
          this.$validator.validateAll().then((response) => {
              if(this.isInvalid != false) {
                return false
              }
              let age_category = []
              _.forEach(this.value, function(opt) {
                age_category.push(opt.id)
              });
              
              if(response) {
                let ReportData = {'tournament_id': this.tournamentId,'age_category':age_category.join(), 'first_name': $('#first_name').val(),'last_name': $('#last_name').val(),'telephone': $('#telephone').val(),'email': $('#email').val(),'comments': $('#availability').val(),'refereeId':this.refereeId, 'is_all_age_categories_selected': $('#chk_ageCategory').prop('checked') }
                 if(this.refereeId != '') {
                  Tournament.updateReferee(ReportData).then(
                  (response) => {
                      toastr['success']('Referee edited successfully.', 'Success');
                      $('#refreesModal').modal('hide')
                      this.$store.dispatch('getAllReferee',this.$store.state.Tournament.tournamentId).then(function() {
                        if($("#save_schedule_fixtures").is(':visible') === true) {
                          $('.js-referee-draggable-block').draggable('disable');
                        } else {
                          $('.js-referee-draggable-block').draggable('enable');
                        }
                      });
                      // this.$root.$emit('setRefereeReset')
                      // this.$root.$emit('setPitchPlanTab','refereeTab')
                    }
                  )
                 } else {
                  Tournament.saveReferee(ReportData).then(
                  (response) => {
                      toastr['success']('Referee added successfully.', 'Success');
                      $('#refreesModal').modal('hide')
                      this.$store.dispatch('getAllReferee',this.$store.state.Tournament.tournamentId).then(function() {
                        if($("#save_schedule_fixtures").is(':visible') === true) {
                          $('.js-referee-draggable-block').draggable('disable');
                        } else {
                          $('.js-referee-draggable-block').draggable('enable');
                        }
                      });
                      // this.$root.$emit('setRefereeReset')
                      // this.$root.$emit('setPitchPlanTab','refereeTab')
                    }
                  )
                }
              }
          })
       },
        deleteConfirmed() {

        Tournament.removeReferee(this.refereeId).then(
          (response) => {
               toastr['success']('Referee has been removed successfully', 'Success');
               $('#delete_modal').modal('hide')
               $('#refreesModal').modal('hide')
                this.$store.dispatch('getAllReferee',this.$store.state.Tournament.tournamentId).then(function() {
                  if($("#save_schedule_fixtures").is(':visible') === true) {
                    $('.js-referee-draggable-block').draggable('disable');
                  } else {
                    $('.js-referee-draggable-block').draggable('enable');
                  }
                });
          }
          )
      },
      onRemove() {
        $('#chk_ageCategory').prop('checked', false)
      },
      onChange (value) {
        this.value = value
        if(this.value.length == this.competationList.length){
        $('#chk_ageCategory').prop('checked', true)

        }
        if (value.indexOf('Reset me!') !== -1) this.value = []
      },
      onSelect (option) {
        if (option === 'Disable me!') this.isDisabled = true
      },
      onTouch () {
        this.isTouched = true
      },
      closeAddRefereesModal() {
        $("#refreesModal").modal('hide');
      },
    }
}
</script>
