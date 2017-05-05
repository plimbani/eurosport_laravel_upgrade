<template>
<div class="modal fade" id="refreesModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Referee details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form name="frmReferee" id="frmReferee" >
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">First name *</label>
              <div class="col-sm-6">
                <input type="text" name="first_name" id="first_name"  v-validate="'required'" v-model="formValues.first_name" class="form-control"  :class="{'is-danger': errors.has('ageCategory_name') }" >
                <i v-show="errors.has('first_name')" class="fa fa-warning"> </i>
              </div>
               <span class="help is-danger" v-show="errors.has('first_name')">This field is required</span>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Last name *</label>
              <div class="col-sm-6">
                <input type="text" name="last_name" id="last_name"  v-validate="'required'" v-model="formValues.last_name"  class="form-control" >
                <i v-show="errors.has('last_name')" class="fa fa-warning"> </i>
              </div>
               <span class="help is-danger" v-show="errors.has('last_name')">This field is required</span>
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
              <label class="col-sm-5 form-control-label">Age categories *</label>
              <div class="col-sm-6">
                <!-- <select  name="ageCategories" id="ageCategories" v-model="formValues.age_group_id"  class="form-control ls-select2">
                      <option value="">Please Select</option>
                      <option value="">U11</option>
                      <option value="">U15</option>
                      <option value="">U19</option>
                  </select> -->
                   <select name="sel_ageCategory" v-model="formValues.age_group_id"  v-validate="'required'" :class="{'is-danger': errors.has('sel_ageCategory') }"  class="form-control" id="sel_ageCategory" >
                        <option value="">Select</option>
                        <option v-for="(competation, index) in competationList" :value="competation.id">{{competation.group_name}}</option>
                        
                    </select>
                     <i v-show="errors.has('sel_ageCategory')" class="fa fa-warning"></i>
              </div>
               <span class="help is-danger" v-show="errors.has('sel_ageCategory')">This field is required</span>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Availability *</label>
              <div class="col-sm-6">
                <textarea name="availability" id="availability" v-validate="'required'" :class="{'is-danger': errors.has('availability') }"  v-model="formValues.availability" class="form-control" placeholder="e.g. Day 1 all day. Day 2 from 11 onwords"></textarea>
                <i v-show="errors.has('availability')" class="fa fa-warning"></i>
              </div>
               <span class="help is-danger" v-show="errors.has('availability')">This field is required</span>
            </div>
          </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveReferee()">Save</button>
        </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
   props: ['formValues','tournamentId','competationList','refereeId'],
   mounted() {
    
   },
  methods: {
    saveReferee () {
                this.$validator.validateAll().then(() => {
                    
                    let ReportData = {'tournament_id': this.tournamentId,'age_category': $('#sel_ageCategory').val(),'first_name': $('#first_name').val(),'last_name': $('#last_name').val(),'telephone': $('#telephone').val(),'email': $('#email').val(),'comments': $('#availability').val(),'refereeId':this.refereeId}
                     if(this.refereeId != ''){
                      Tournament.updateReferee(ReportData).then(
                      (response) => {  
                          toastr['success']('Referee detail has been updated successfully', 'Success');
                          $('#refreesModal').modal('hide')
                      }
                      )
                     }else{
                      Tournament.saveReferee(ReportData).then(
                      (response) => {  
                           toastr['success']('Referee detail has been added successfully', 'Success');
                          $('#refreesModal').modal('hide')
                      }
                      )
                     }
                      
                    
                        
                       
                })
 
            },
  }

}
</script>