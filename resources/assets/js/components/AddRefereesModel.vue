<template>
<div class="modal fade" id="refreesModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Referee Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form name="frmReferee" id="frmReferee" >
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">First name*</label>
              <div class="col-sm-6">
                <input type="text" name="first_name" id="first_name"  v-validate="'required'" v-model="formValues.first_name" class="form-control"  :class="{'is-danger': errors.has('ageCategory_name') }" >
                <i v-show="errors.has('first_name')" class="fa fa-warning"> </i>

               <span class="help is-danger" v-show="errors.has('first_name')">This field is required</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Last name*</label>
              <div class="col-sm-6">
                <input type="text" name="last_name" id="last_name"  v-validate="'required'" v-model="formValues.last_name"  class="form-control" >
                <i v-show="errors.has('last_name')" class="fa fa-warning"> </i>
                <span class="help is-danger" v-show="errors.has('last_name')">This field is required</span>
               </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Telephone</label>
              <div class="col-sm-6">
                <input type="text" name="telephone" id="telephone" v-model="formValues.telephone" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Email</label>
              <div class="col-sm-6">
                <input type="email"  name="email" id="email" v-model="formValues.email" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Category age*</label>
              <div class="col-sm-6">
                <!-- <select  name="ageCategories" id="ageCategories" v-model="formValues.age_group_id"  class="form-control ls-select2">
                      <option value="">Please Select</option>
                      <option value="">U11</option>
                      <option value="">U15</option>
                      <option value="">U19</option>
                  </select> -->
                 <multiselect name="sel_ageCategory" id="sel_ageCategory" :options="competationList" :multiple="true" :hide-selected="false" :ShowLabels="false" :value="value" track-by="id"  label="category_age"   :clear-on-select="false" :Searchable="true"  @input="onChange"  @close="onTouch" @select="onSelect"></multiselect>
                   <!-- <select name="sel_ageCategory"  v-model="formValues.age_group_id"  v-validate="'required'" v-bind:multiple="isMultiple" :class="{'is-danger': errors.has('sel_ageCategory') }"  class="form-control" id="sel_ageCategory" >
                        <option value="">Select</option>
                        <option v-for="(competation, index) in competationList" :value="competation.id">{{competation.category_age}}</option>
                    </select> -->
                    
               <span class="help is-danger" v-show="isInvalid">This field is required</span>
               </label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Availability</label>
              <div class="col-sm-6">
                <textarea name="availability" id="availability" v-model="formValues.comments" class="form-control" placeholder="e.g. Day 1 all day"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer d-flex flex-row justify-content-between">
            <div>
              <button type="button" v-if="refereeId!=''" class="btn btn-danger pull-left"  data-toggle="modal" data-target="#delete_modal">Delete</button>
            </div>
            <div>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" @click="saveReferee()">Save</button>
            </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg"   @confirmed="deleteConfirmed()"></delete-modal>
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
   // computed: {
   //    isInvalid () {
   //      return this.isTouched && this.value.length === 0
   //    }
   //  },
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
               vm.options = competitionOption
          },1000)
        },
      },
  mounted() {
   
    
      
  },
  methods: {

    saveReferee () {
                this.isInvalid = false
                if(this.value.length === 0) {
                  this.isInvalid = true

                }
                this.$validator.validateAll().then(() => {
                  if(this.isInvalid != false) {
                    return false
                  }
                      let age_category = []
                      _.forEach(this.value, function(opt) {
                        age_category.push(opt.id)
                      });
                      
                    let ReportData = {'tournament_id': this.tournamentId,'age_category':age_category.join(), 'first_name': $('#first_name').val(),'last_name': $('#last_name').val(),'telephone': $('#telephone').val(),'email': $('#email').val(),'comments': $('#availability').val(),'refereeId':this.refereeId}
                     if(this.refereeId != ''){
                      Tournament.updateReferee(ReportData).then(
                      (response) => {
                          toastr['success']('Referee edited successfully.', 'Success');
                          $('#refreesModal').modal('hide')
                          this.$root.$emit('setRefereeReset')
                          this.$root.$emit('setPitchPlanTab','refereeTab')
                      }
                      )
                     }else{
                      Tournament.saveReferee(ReportData).then(
                      (response) => {
                          toastr['success']('Referee added successfully.', 'Success');
                          $('#refreesModal').modal('hide')

                          this.$root.$emit('setRefereeReset')
                          this.$root.$emit('setPitchPlanTab','refereeTab')
                      }
                      )
                     }
                })

            },
      deleteConfirmed() {

      Tournament.removeReferee(this.refereeId).then(
        (response) => {
             toastr['success']('Referee has been removed successfully', 'Success');
             $('#delete_modal').modal('hide')
             $('#refreesModal').modal('hide')
             this.$root.$emit('setRefereeReset')
             this.$root.$emit('setPitchPlanTab','refereeTab')
        }
        )
    },
    onChange (value) {
      this.value = value
      if (value.indexOf('Reset me!') !== -1) this.value = []
    },
    onSelect (option) {
      if (option === 'Disable me!') this.isDisabled = true
    },
    onTouch () {
      this.isTouched = true
    }
  }

}
</script>
