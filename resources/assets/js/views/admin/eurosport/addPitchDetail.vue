<template>
<div class="modal" id="addPitchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true" data-animation="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="tabs tabs-primary">
        <div class="modal-header">
          <h5 class="modal-title">{{$lang.pitch_Details}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="displayPitch(0)">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs col-md-12" role="tablist">
              <li class="nav-item col-md-6 padding0">
                  <a data-toggle="tab" href="#pitch" role="tab" class="nav-link active text-center">{{$lang.pitch_details}}</a>
              </li>
              <li class="nav-item col-md-6 padding0">
                  <a data-toggle="tab" href="#availability" role="tab" class="nav-link text-center">{{$lang.pitch_modal_availability}}</a>
              </li>
          </ul>
          <div class="tab-content">
            <div id="pitch" role="tabpanel" class="tab-pane active">
              <div class="mb-3">{{$lang.pitch_details_note}}</div>
              <form method="post" name="frmPitchDetail" id="frmPitchDetail">
                  <div class="card mb-2">
                      <div class="card-block">
                          <div class="form-group row">
                              <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_location}}*</label>
                              <div class="col-sm-6">
                                  <select name="location" id="location" class="form-control" v-validate="'required'" :class="{'is-danger': errors.has('location') }">
                                      <option :value="venue.id" v-for="(venue,key) in venues">{{venue.name}}</option>
                                  </select>
                                   <span class="help is-danger" v-show="errors.has('location')">{{$lang.pitch_modal_details_location_required}}</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_name}}</label>
                              <div class="col-sm-6">
                                  <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('pitch_number') }" name="pitch_number" id="pitch_number" class="form-control">
                                      <i v-show="errors.has('pitch_number')" class="fa fa-warning"></i>
                                  <span class="help is-danger" v-show="errors.has('pitch_number')">{{ $lang.pitch_modal_details_name_required }}</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_type}}*</label>
                              <div class="col-sm-6">
                                  <select name="pitch_type" id="pitch_type" class="form-control" v-validate="'required'" :class="{'is-danger': errors.has('pitch_type') }">
                                      <option value="">{{$lang.pitch_modal_pitch_type}}</option>
                                      <option value="Grass">{{$lang.pitch_modal_details_grass}}</option>
                                      <option value="Artificial">{{$lang.pitch_modal_details_artificial}}</option>
                                      <option value="Indoor">{{$lang.pitch_modal_details_indoor}}</option>
                                      <option value="Other">{{$lang.pitch_modal_details_other}}</option>
                                  </select>
                                  <span class="help is-danger" v-show="errors.has('pitch_type')">{{$lang.pitch_modal_details_type_required}}</span>
                              </div>
                          </div>
                          <div class="form-group row mb-0">
                              <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_size}}*</label>
                              <div class="col-sm-6">
                                  <select name="pitch_size" id="pitch_size" class="form-control pull-left" v-validate="'required'" :class="{'is-danger': errors.has('pitch_size') }">
                                      <option value="">{{$lang.pitch_modal_pitch_size}}</option>
                                      <option value="5-a-side">{{$lang.pitch_modal_details_size_side}}</option>
                                      <option value="7-a-side">{{$lang.pitch_modal_details_size_side_one}}</option>
                                      <option value="8-a-side">{{$lang.pitch_modal_details_size_side_two}}</option>
                                      <option value="9-a-side">{{$lang.pitch_modal_details_size_side_three}}</option>
                                      <option value="11-a-side">{{$lang.pitch_modal_details_size_side_four}}</option>
                                      <!--<option value="Handball">{{$lang.pitch_modal_details_size_side_handball}}</option>-->
                                      <!-- <option value="Indoor">{{$lang.pitch_modal_button_next}}</option> -->
                                  </select>
                                  <span class="help is-danger" v-show="errors.has('pitch_size')">{{$lang.pitch_modal_details_size_required}}</span>
                              </div>
                              <!-- <div class="col-md-12">
                                  <button type="button" id="add_stage" @click="nextStage()"  class="btn btn-primary">{{$lang.pitch_modal_button_next}}</button>
                              </div> -->
                          </div>
                      </div>
                  </div>
              </form>
            </div>
            <div id="availability" role="tabpanel" class="tab-pane row">
              <div class="col-md-12 mb-2">
                <div class="mb-3">{{$lang.pitch_detail_tabs}}</div>
                <div class="card">
                  <div class="card-block">
                    <div class="competition_list row">
                      <div class="col-md-3">
                          <span>{{$lang.pitch_modal_availability_stage}}</span>
                      </div>
                      <div class="col-md-3">
                          <span>{{$lang.pitch_modal_availability_date}}</span>
                      </div>
                      <div class="col-md-3">
                          <span>{{$lang.pitch_modal_availability_time}}</span>
                      </div>
                      <div class="col-md-3">
                          <span>{{$lang.pitch_modal_availability_capacity}}</span>
                      </div>
                    </div>
                    <form method="post" name="frmPitchAvailable" id="frmPitchAvailable" >
                      <div v-for="day in tournamentDays">
                        <div class="stage" :id="'stage'+day" v-if="displayDay(day)">
                          <div class="row justify-content-center">
                            <div class="card w-100">
                              <div class="card-block">
                                <div class="row align-items-center mb-3">
                                  <div class="col-md-3">
                                      Day {{day}} start
                                  </div>
                                  <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="jv-icon jv-calendar"></i>
                                        </span>
                                        <input type="hidden" :name="'totalBreaksForStage'+day" :id="'totalBreaksForStage'+day" v-model="stage_break[day]">
                                        <input type="text" :name="'stage_start_date'+day" :id="'stage_start_date'+day" value="" :class="[ errors.has('stage_start_date'+day)?'is-danger':'','form-control datestage'+day] " readonly="readonly">
                                             <!-- <i v-show="errors.has('stage_start_date'+day)" class="fa fa-warning"></i>
                                             <span class="help is-danger" v-show="errors.has('stage_start_date'+day)">{{ errors.first('stage_start_date'+day) }}</span> -->
                                        <!-- <input v-model="formValues.name" v-validate="'required|alpha'" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" placeholder="Your name"> -->
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                        <div class="align-self-center w-100">
                                            <input :name="'stage_start_time'+day" v-validate="'required'" :class="[errors.has('stage_start_time'+day)?'is-danger': '', 'form-control ls-timepicker stage_start_time']"  :id="'stage_start_time'+day"  type="text" >
                                        </div>
                                        <div class="align-self-center p-1">
                                            <i v-show="errors.has('stage_start_time'+day)" class="fa fa-warning text-danger" data-placement="top" title="Start time is required"></i>
                                        </div>
                                        <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                    </div>
                                  </div>
                                  <div class="col-md-3">

                                  </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                  <!-- <div class="col-md-3">
                                     {{$lang.pitch_capacity_break_start}}
                                  </div> -->
                                  <div class="col-md-3">
                                  <!-- <input type="checkbox" name="stage_chk_active">Check to add a break -->
                                  <input type="checkbox" :name="'stage_break_chk'+day" class="mr-1 stage_break_chk"  :id="'stage_break_chk_'+day" >Check to add a break
                                  </div>
                                  <!-- <div class="col-md-3">
                                    <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                        <div   :class="'align-self-center w-100 stageInvisible chk_disable_'+day ">
                                            <input type="text" :name="'stage_break_start'+day" v-validate="'required'" :class="[errors.has('stage_break_start'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day]" :id="'stage_break_start'+day" >
                                        </div>
                                        <div class="align-self-center p-1">
                                            <i v-show="errors.has('stage_break_start'+day)" class="fa fa-warning text-danger" data-placement="top" title="Break start time is required"></i>
                                        </div>

                                    </div>
                                     <a href="#" :class="'btn btn-primary stageInvisible chk_disable_'+day "  @click="addBreak(day)">{{$lang.pitch_detail_break_add}}</a>
                                  </div>
                                  <div class="col-md-3">
                                    <a href="#" :class="'btn btn-danger  stageInvisible chk_disable_'+day "  @click="removeBreak(day)">{{$lang.pitch_detail_break_remove}}</a>
                                  </div> -->
                                </div>
                                <!-- <div :class="'row align-items-center mb-3 stageInvisible chk_disable_'+day ">
                                  <div class="col-md-3">
                                      Break end
                                  </div>
                                  <div class="col-md-3">
                                    <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                        <div   :class="'align-self-center w-100 stageInvisible chk_disable_'+day ">
                                            <input type="text" :name="'stage_break_start'+day" v-validate="'required'" :class="[errors.has('stage_break_start'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day ]" :id="'stage_break_start'+day" >
                                        </div>
                                        <div class="align-self-center p-1">
                                            <i v-show="errors.has('stage_break_start'+day)" class="fa fa-warning text-danger" data-placement="top" title="Break start time is required"></i>
                                        </div>

                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                      <div :class="'align-self-center w-100 stageInvisible  chk_disable_'+day ">
                                          <input type="text" :name="'stage_continue_time'+day" v-validate="'required'" :class="[errors.has('stage_continue_time'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day]"  :id="'stage_continue_time'+day">
                                      </div>
                                      <div class="align-self-center p-1">
                                          <i v-show="errors.has('stage_continue_time'+day)" class="fa fa-warning text-danger" data-placement="top" title="Continue time is required"></i>
                                      </div>

                                    </div>
                                  </div>
                                  <div class="col-md-3">

                                  </div>
                                </div> -->
                                <div v-if="breakEnable[day]">
                                  <div  v-for="n in stage_break[day]">
                                  <div class="row align-items-center mb-3" >
                                    <div class="col-md-3">
                                      Break {{n}} start
                                    </div>
                                    <div class="col-md-3">
                                      <div class="input-group">
                                          <span class="input-group-addon">
                                              <i class="jv-icon jv-calendar"></i>
                                          </span>
                                          <input type="text" :name="'stage_break_start'+day" :id="'stage_break_start'+day" disabled="disabled" readonly="" :class="['form-control ls-datepicker datestage'+ day]">
                                      </div>
                                      <!-- <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                          <div   :class="'align-self-center w-100 chk_disable_'+day ">
                                              <input type="text" :name="'stage_break_start'+day+'-'+n" v-validate="'required'" :class="[errors.has('stage_break_start'+day+'-'+n)?'is-danger': '', 'form-control ls-timepicker stage_break_start stage_chk_active'+day]"  :id="'stage_break_start'+day+'-'+n" >
                                          </div>
                                          <div class="align-self-center p-1">
                                              <i v-show="errors.has('stage_break_start'+day+'-'+n)" class="fa fa-warning text-danger" data-placement="top" title="Break start time is required"></i>
                                          </div>

                                      </div> -->
                                    </div>
                                    <div class="col-md-3">
                                      <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                          <div   :class="'align-self-center w-100 chk_disable_'+day ">
                                              <input type="text" :name="'stage_break_start'+day+'-'+n" v-validate="'required'" :class="[errors.has('stage_break_start'+day+'-'+n)?'is-danger': '', 'form-control ls-timepicker stage_break_start stage_chk_active'+day]"  :id="'stage_break_start'+day+'-'+n" >
                                          </div>
                                          <div class="align-self-center p-1">
                                              <i v-show="errors.has('stage_break_start'+day+'-'+n)" class="fa fa-warning text-danger" data-placement="top" title="Break start time is required"></i>
                                          </div>

                                      </div>
                                    </div>
                                     <div class="col-md-3">

                                    </div>
                                  </div>
                                  <div class="row align-items-center mb-3" >
                                    <div class="col-md-3">
                                      Break {{n}}  end
                                    </div>
                                    <div class="col-md-3">
                                      <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="jv-icon jv-calendar"></i>
                                        </span>
                                        <input type="text" :name="'stage_end_date'+day" :id="'stage_end_date'+day" disabled="disabled" readonly="" :class="['form-control ls-datepicker datestage'+ day]">
                                      </div>
                                      <!-- <input type="text" :name="'stage_break'+day" :id="'stage_continue_date'+day" disabled="disabled" readonly="" :class="['form-control sdate ls-datepicker datestage'+ day]"> -->
                                     <!--  <input type="text" :name="'stage_continue_date'+day+'-'+n" :id="'stage_continue_date'+day+'-'+n" disabled="disabled" readonly="" :class="['form-control sdate ls-datepicker datestage'+day]"> -->
                                    </div>
                                    <div class="col-md-3">
                                      <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                        <div :class="'align-self-center w-100  chk_disable_'+day ">
                                            <input type="text" :name="'stage_continue_time'+day+'-'+n" v-validate="'required'" :class="[errors.has('stage_continue_time'+day+'-'+n)?'is-danger': '', 'form-control ls-timepicker stage_continue_time stage_chk_active'+day]"  :id="'stage_continue_time'+day+'-'+n">
                                        </div>
                                        <div class="align-self-center p-1">
                                            <i v-show="errors.has('stage_continue_time'+day+'-'+n)" class="fa fa-warning text-danger" data-placement="top" title="Continue time is required"></i>
                                        </div>
                                          <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                      </div>
                                    </div>
                                     <div class="col-md-3">

                                    </div>
                                  </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                  <div class=" col-md-3">

                                  </div>
                                  <div class="col-md-3">

                                     <a href="#" :class="'btn btn-primary chk_disable_'+day "  @click="addBreak(day)">{{$lang.pitch_detail_break_add}}</a>
                                  </div>
                                  <div class="col-md-3">
                                    <a href="#" :class="'btn btn-danger  chk_disable_'+day " v-if="stage_break[day] > 1" @click="removeBreak(day)">{{$lang.pitch_detail_break_remove}}</a>
                                  </div>
                                </div>
                              </div>
                                <div class="row align-items-center mb-3">
                                  <div class="col-md-3">
                                      Day {{day}} end
                                  </div>
                                  <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="jv-icon jv-calendar"></i>
                                        </span>
                                        <input type="text" :name="'stage_end_date'+day" :id="'stage_end_date'+day" readonly="" :class="['form-control datestage'+ day]">
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                        <div class="align-self-center w-100">
                                            <input :name="'stage_end_time'+day" :id="'stage_end_time'+day" type="text"  v-validate="'required'" :class="[errors.has('stage_end_time'+day)?'is-danger': '', 'form-control ls-timepicker']">
                                        </div>
                                        <div class="align-self-center p-1">
                                            <i v-show="errors.has('stage_end_time'+day)" class="fa fa-warning text-danger" data-placement="top" title="Day end time is required"></i>
                                        </div>
                                        <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                    </div>
                                  </div>
                                  <div class="col-md-3"><span :id="'stage_capacity1_span'+day"  class="badge badge-pill pitch-badge-info">0.00 hrs</span>
                                    <input type="hidden" :name="'stage_capacity'+day" :id="'stage_capacity'+day" value="0.00">
                                    <input type="hidden" class="stage_capacity_all" :name="'stage_capacity_min'+day" :id="'stage_capacity_min'+day" value="0">
                                  </div>
                                </div>
                              </div>
                              <div class="card-footer text-right">
                                  <a href="#" class="btn btn-danger"  @click="stageRemove(day)">{{$lang.pitch_detail_delete_stage}}</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row mt-3">
                          <button type="button" id="add_stage" @click="addStage()" :disabled="removeStage.length==0" class="btn btn-primary">{{$lang.pitch_modal_availability_button_addstage}}</button>
                      </div>
                    </form>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" @click="displayPitch(0)">{{$lang.pitch_modal_availability_button_close}}</button>
          <button type="button" class="btn button btn-primary" @click="savePitchDetails()" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.pitch_modal_availability_button_save}}</button>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
var moment = require('moment');
export default {

  data() {
      return {
          'tournamentId': this.$store.state.Tournament.tournamentId,
          'tournamentDays': this.$store.state.Tournament.tournamentDays,
          'stage_date':[],
          'tournamentStartDate': this.$store.state.Tournament.tournamentStartDate,
          'tournamentEndDate': this.$store.state.Tournament.tournamentEndDate,
          'removeStage': [],
          'disableDate': [],
          'stage_capacity' : [],
          'availableDate': [],
          'stage_break': [],
          'breakEnable': [],
          isSaveInProcess: false
          }
  },
  computed: {
      tournamentTime: function() {
          return this.$store.state.Tournament.currentTotalTime
      },
      pitches: function() {
          return this.$store.state.Pitch.pitches
      },
      venues: function() {
          return this.$store.state.Tournament.venues
      },
      pitchId: function() {
          return this.$store.getters.curPitchId
      },
      pitchData: function() {
          return this.$store.state.Pitch.pitchData
      },
      pitchCapacity: function() {
          return this.$store.state.Pitch.pitchCapacity
      },
      pitchAvailableBalance : function() {
          let pitchavailableBalance = []
          let tournamentAvailableTime =  this.tournamentTime
          let pitchCapacityTime =this.pitchCapacity
          let availableTime = tournamentAvailableTime - pitchCapacityTime
          var minutes = availableTime % 60;
          var hours = (availableTime - minutes) / 60;
          pitchavailableBalance.push (hours,minutes)
          return pitchavailableBalance

      }

  },
  mounted(){
    let vm =this;
      Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
      // this.stage_capacity1 ='5.30';
      // this.stage_capacity1 ='5.30';
      // this.stage_capacity1 ='5.30';
      // this.$store.dispatch('SetPitches',this.tournamentId);
      let capacity={}
      let sDate = []
      var startDate = new Date(moment(this.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY'))
      var obj ={}
      // var stageBreak=[] ;
      var stageBreak = '{"stageBreak":[]}';
      var sBreak = JSON.parse(stageBreak);
      $('[data-toggle="tooltip"]').tooltip();

      $('.ls-datepicker').datepicker('setStartDate', this.tournamentStartDate);

      $('.ls-datepicker').datepicker('setEndDate', this.tournamentEndDate);
      for(let i=1;i<=this.tournamentDays;i++){
          capacity['day'+i]= '0.00'
          let j= i-1;
          startDate = new Date(moment(this.tournamentStartDate, 'DD/MM/YYYY').add(j, 'days').format('MM/DD/YYYY'));
          $('.datestage'+i).val(moment(startDate, 'MM/DD/YYYY').format('DD/MM/YYYY'));
          this.disableDate.push( $('.datestage'+i).val());
          // startDate.setDate()
          obj['date'+i] = $('.datestage'+i).val();
          $('#stage_start_time'+i).timepicker({
              minTime: '08:00',
              maxTime: '23:00',
              'timeFormat': 'H:i'
          })
           stageBreak = [{'day':i,'break':'0'}];


          sBreak['stageBreak'].push({"day":i,"break":1});

          // let k = i+1;
         // stageBreak['day'+i] = '1';

         // jsonData[columnName] = column.value;
         // stageBreak.filter( Number );
         // array_filter(stageBreak);
      }
      // this.stage_break.push( va);
      // stageBreak = JSON.stringify(obj);

      let arr = [];
      let brk = [];
      _.map( sBreak['stageBreak'], function(s) {
        arr[s.day] = s.break;
        brk[s.day] = false
      });
       this.stage_break= arr;
       this.breakEnable= brk;
      let disableDate = this.disableDate;
      this.stage_date.push(obj)
      $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
      this.stage_capacity.push(capacity)

      $('#addPitchModal').on('hidden.bs.modal', function () {
        // Now here we have to call function for parent class
        // this.$root.$emit('displayPitch',0)
        //this.$emit('displayPitch',0)
      })
      $('#frmPitchAvailable').on("change",'.ls-timepicker ',function(){
         // this.stageCapacityCalc(1)
         let curId = $(this)[0].id
         let stage = $(this)[0].id;
         let curTime = ''
         stage = stage.replace('stage_start_time','')
         stage = stage.replace('stage_break_start','')
         stage = stage.replace('stage_continue_time','')
         stage = stage.replace('stage_end_time','')
         let stageArr = 0;
         let breakno=1;
         let updatedTime = '00:00';
         if(curId.indexOf('stage_break_start') >= 0 || curId.indexOf('stage_continue_time') >= 0) {
            stageArr = stage.split('-');
            stage = stageArr[0];
            breakno =   stageArr[1];
          }

          if( curId.indexOf('stage_start_time') >= 0){
              curTime = $('#stage_start_time'+stage).val()
            if($('#stage_break_chk_'+stage).is(':checked')){
               $('.stage_chk_active'+stage).attr('disabled','disabled')
               $('#stage_break_start'+stage+'-'+breakno).removeAttr('disabled')
               $('#stage_end_time'+stage).attr('disabled','disabled')
            }else{

              setTimeout(function(){
                $('.stage_chk_active'+stage).val($('#stage_start_time'+stage).val())
              },100)
              $('#stage_end_time'+stage).removeAttr('disabled')
            }

          }else if(curId.indexOf('stage_break_start') >= 0) {
            // let stageArr = stage.split('-');
            // stage = stageArr[0];
            // let breakno =   stageArr[1];

            if($('#stage_break_chk_'+stage).is(':checked')){
              $('#stage_continue_time'+stage+'-'+breakno).removeAttr('disabled');
              $('#stage_end_time'+stage).attr('disabled','disabled')
              curTime = $('#stage_break_start'+stage+'-'+breakno).val()

            }
          }else if(curId.indexOf('stage_continue_time') >= 0) {
            if($('#stage_break_chk_'+stage).is(':checked')){
              $('#stage_end_time'+stage).removeAttr('disabled')
              curTime = $('#stage_continue_time'+stage+'-'+breakno).val()
            }
          }else if(curId.indexOf('stage_end_time') >= 0) {
              curTime = $('#stage_end_time'+stage).val()
          }
          let newTime = ''

            updatedTime =  curTime.split(':')
          if(curTime.indexOf('pm') >= 0 && (updatedTime[0]!= '12')) {
              let hrs = parseInt(updatedTime[0])+12
              let min = updatedTime[1].split(' ')[0] == '30' ? '30' : '00'
               newTime = hrs+':'+min
          }else{
              // let updatedTime =  curTime.split(':')
              let hrs = parseInt(updatedTime[0])
              let min = updatedTime[1].split(' ')[0]  == '30' ? '30' : '00'
              newTime = hrs+':'+min+':00'
          }
          if(curId.indexOf('stage_start_time') >= 0){

              $('.stage_chk_active'+stage).timepicker({
                  minTime:  newTime,
                  maxTime: '23:00',
                  'timeFormat': 'H:i'
              });
              // $('.stage_continue_time').timepicker({
              //     minTime:  newTime,
              //     maxTime: '23:00',
              //     'timeFormat': 'H:i'
              // });

              $('#stage_end_time'+stage).timepicker({
                  minTime:  newTime,
                  maxTime: '23:00',
                  'timeFormat': 'H:i'
              });
              // console.log(newTime);
              $('#stage_break_start'+stage+'-'+breakno).timepicker('option', 'minTime', newTime);

              $('.stage_chk_active'+stage).val('')
              // $('.stage_continue_time').val('')
               $('#stage_end_time'+stage).val('')
          }
          if(curId.indexOf('stage_break_start') >= 0){
              //   $('#stage_continue_time'+stage+'-'+breakno).timepicker({
              //     minTime: newTime,
              //     maxTime: '23:00',
              //     'timeFormat': 'H:i'
              // });
              // $('.stage_chk_active'+stage).timepicker('option', 'minTime', newTime);
              $('#stage_continue_time'+stage+'-'+breakno).timepicker('option', 'minTime', newTime);

              $('#stage_continue_time'+stage+'-'+breakno).val('')
              $('#stage_end_time'+stage).val('');
              vm.removeBreak(stage,parseInt(breakno));
          }
          if(curId.indexOf('stage_continue_time') >= 0 ){
              $('#stage_end_time'+stage).timepicker({
                  minTime:  newTime,
                  maxTime: '23:00',
                  'timeFormat': 'H:i'
              });
              $('#stage_end_time'+stage).timepicker('option', 'minTime', newTime);
              $('#stage_end_time'+stage).val('');
              vm.removeBreak(stage,parseInt(breakno));
              // vm.stage_break[stage] = breakno;
          }

            vm.setStageCapacity(stage,breakno);
      })

      var that = this
      $('.ls-datepicker').datepicker().on('changeDate',function(){
          var stage = this.id

         stage = stage.replace("stage_start_date", "");
         if (stage.search('stage_end_date') != -1 || stage.search('stage_continue_date') != -1 ) {
          return false
         }
         if($.inArray( parseInt(stage), that.removeStage ) !== -1  ){
              return false
          }else{

          var index =  that.disableDate.indexOf($('#stage_end_date'+stage).val());
          if (index > -1) {
              // let stage = disableDate[index];
              that.disableDate.splice(index, 1);
              that.availableDate.push($('#stage_end_date'+stage).val())
              that.availableDate.splice(that.availableDate.indexOf($('#'+this.id).val()),1)
              // that.disableDate = disableDate
              // disableDate
          }
          that.disableDate.push( $('#'+this.id).val());
          $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);
          $('.datestage'+stage).val($('#'+this.id).val())
          }

      });
       setTimeout(function(){
          $('.ls-timepicker').not('.stage_start_time').attr('disabled','disabled');
       },1000)

       this.getAllPitches()
       // if(this.tournamentDays> 2) {
       //        for(let i=3;i<=this.tournamentDays;i++){
       //            this.stageRemove(i)
       //        }
       //    }
    $(document).ready(function(){
      let vm =that;
      $("body").on('click','.stage_break_chk',function(){
        let stageId = this.id
        let stage = stageId.replace('stage_break_chk_','')
        let curTime = '08:00';
        if(this.checked){
          if($('#stage_start_time'+stage).val()!=''){
            $('#stage_break_start'+stage);

            $('.stage_chk_active'+stage).removeAttr('disabled','disabled')
            $('#stage_end_time'+stage).val('');

            $('#stage_end_time'+stage).val('');

            curTime =  $('#stage_start_time'+stage).val();
          }else{
            $('.stage_chk_active'+stage).attr('disabled','disabled')
            $('#stage_end_time'+stage).attr('disabled','disabled');
          }

          $('.chk_disable_'+stage).removeClass('stageInvisible');
          that.breakEnable[stage] = true;
          let brk = that.breakEnable;
          that.breakEnable = [];
          that.breakEnable = brk


          let updatedTime =curTime.split(':');
          let hrs = parseInt(updatedTime[0])
          let min = updatedTime[1].split(' ')[0]  == '30' ? '30' : '00'
          let newTime = hrs+':'+min+':00'

          setTimeout(function(){
            $('.stage_chk_active'+stage).timepicker({
              minTime: newTime,
              maxTime: '23:00',
              timeFormat: 'H:i'
            });
            $('.datestage'+stage).val($('#stage_start_date'+stage).val())
          },500)
        }else{
          $('.stage_chk_active'+stage).val($('#stage_start_time'+stage).val())
          $('.stage_chk_active'+stage).attr('disabled','disabled')
          $('#stage_end_time'+stage).removeAttr('disabled','disabled')
          $('.chk_disable_'+stage).addClass('stageInvisible')
          that.breakEnable[stage] = false;
          let brk = that.breakEnable;
          that.breakEnable = [];
          that.breakEnable = brk
        }
        that.calculateStageTime(stage);
      })
    })
  },
  methods: {

      displayPitch() {
        this.$root.$emit('displayPitch',0)
      },
      getAllPitches() {
          this.$store.dispatch('SetPitches',this.tournamentId);
      },
      nextStage() {
        $('.nav-tabs a[href="#availability"]').tab('show');
      },
      addBreak(day) {
        // this.breakEnable[day] = false;
        let brk = this.breakEnable;
        let last_break = this.stage_break[day];

        if($('#break_start_time'+day+'-'+last_break).val() != '' && $('#stage_continue_time'+day+'-'+last_break).val() != '') {
          let curTime =  $('#stage_continue_time'+day+'-'+last_break).val();
          let updatedTime =curTime.split(':');
          // let curBreakNo = 0;
          let hrs = parseInt(updatedTime[0])
              let min = updatedTime[1].split(' ')[0]  == '30' ? '30' : '00'
             let newTime = hrs+':'+min+':00'
            this.breakEnable = [];
            let  curBreakNo = this.stage_break[day]  = parseInt(this.stage_break[day]) +1;
            this.breakEnable = brk;
            $('#stage_end_time'+day).val('');
            $('#stage_end_time'+day).attr('disabled','disabled');
        setTimeout(function(){
            $('#stage_break_start'+day+'-'+curBreakNo+', #stage_continue_time'+day+'-'+curBreakNo).timepicker({
              minTime: newTime,
              maxTime: '23:00',
              timeFormat: 'H:i'
          });
            $('.datestage'+day).val($('#stage_start_date'+day).val())
          },1000)
        } else {
          toastr['error']('Please add last break time ', 'Error')
        }


      },
      removeBreak(day,brkNo = 0) {
        // this.breakEnable[day] = false;
          if(brkNo!=0){
             let brk = this.breakEnable;
                this.breakEnable = [];
                this.stage_break[day] = parseInt(brkNo);
                this.breakEnable = brk;
                $('#stage_end_time'+day).removeAttr('disabled','disabled');
                this.setStageCapacity(day,parseInt(brkNo))
          }else{
            if(parseInt(this.stage_break[day]) > 1){
                let brk = this.breakEnable;
                this.breakEnable = [];
                this.stage_break[day] = parseInt(this.stage_break[day]) -1;
                this.breakEnable = brk;
                $('#stage_end_time'+day).removeAttr('disabled','disabled');
                this.setStageCapacity(day,parseInt(brkNo))

          }
        }


      },
      savePitchDetails () {
          this.$validator.validateAll().then(() => {
              var time = 0
              $( ".stage_capacity_all" ).each(function( index ) {
                time = time + parseInt($(this).val())
              });
              //  var minutes = time % 60;
              // var hours = (time - minutes) / 60;
              // var time_val = hours+ '.' +minutes

              let pitchData = $("#frmPitchDetail").serialize() +'&' + $("#frmPitchAvailable").serialize() + '&tournamentId='+this.tournamentId+'&stage='+this.tournamentDays+'&pitchCapacity='+time
                      // this.$store.dispatch('AddPitch',pitchData)
                      this.isSaveInProcess = true;
                      return axios.post('/api/pitch/create',pitchData).then(response =>  {
                          this.pitchId = response.data.pitchId
                          toastr['success']('Pitch detail has been added successfully.', 'Success');
                          this.displayPitch();
                          this.isSaveInProcess = false;
                          $('#addPitchModal').modal('hide')
                          $("#frmPitchDetail")[0].reset();

                      }).catch(error => {
                          if (error.response.status == 401) {
                              // toastr['error']('Invalid Credentials', 'Error');
                          } else {
                              //   happened in setting up the request that triggered an Error
                          }
                      });


          }).catch(() => {
            toastr['error']('Please complete all required fields on both tabs ', 'Error')
           });
          // let pitchData = {
          //     'pitchId' : this.pitchId,
          //     'number': '123',
          //     'type' : 'Grass',
          //     'location' : '1',
          //     'Size' : '5-a-side'
          //     }
               // let pitchData = new FormData($("#frmPitchDetail")[0]$("#frmPitchAvailable")[0]);


      },
      stageRemove (day) {
        this.removeStage.push(day)
          // this.disableDate;

          var index = this.disableDate.indexOf($('#stage_start_date'+day).val());
          if (index > -1) {


              this.disableDate.splice(index, 1);
              this.availableDate.push($('#stage_start_date'+day).val())
              $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
              $('.datestage'+day).datepicker('clearDates')
          }
          // this.stageShowday = false
          //


      },
      displayDay (day) {
          if($.inArray( day,this.removeStage) != -1 ) {
              return false

          }else {
              return true
          }
      },
      setStageCapacity(stage,breakno) {
        let vm =this;
        stage = parseInt(stage);
        breakno = parseInt(breakno);
        if( $('#stage_start_time'+stage).val() == '' || $('#stage_end_time'+stage).val() == '' || $('#stage_break_start'+stage+'-'+breakno).val() == '' || $('#stage_continue_time'+stage+'-'+breakno).val() == ''  ) {
              $('#stage_capacity_span'+stage).text('0.00 hrs');
              $('#stage_capacity'+stage).val('0.00');
          }else {
            this.calculateStageTime(stage);
          }
      },
      setDatepicker(tStartDate,tEndDate,disableDate,availableDate,stage) {
              // let availableDate = this.availableDate
              let that =this

             if(availableDate.length > 0) {
                  let availDate = availableDate[0];

                  that.disableDate.push(availableDate[0])
                  var index = availableDate.indexOf(availableDate[0]);
                  availableDate.splice(index, 1);
                  that.availableDate = availableDate
                   setTimeout(function() {
                  $('.datestage'+stage).val(availDate)

                  $('#stage_start_time'+stage).timepicker({
                      minTime:  '08:00',
                      maxTime: '23:00',
                      'timeFormat': 'H:i'
                  })
                  // $('.ls-timepicker').not('.stage_start_time').attr('disabled','disabled');
                  $('#stage_break_start'+stage+',#stage_continue_time'+stage+',#stage_end_time'+stage).attr('disabled','disabled');
                  // $('#stage_break_start'+stage).timepicker({
                  //     minTime:  '08:00:00',
                  //     maxTime: '19:00:00'
                  // })
                  // $('#stage_continue_time'+stage).timepicker({
                  //     minTime:  '08:00:00',
                  //     maxTime: '19:00:00'
                  // })
                  // $('#stage_end_time'+stage).timepicker({
                  //     minTime:  '08:00:00',
                  //     maxTime: '19:00:00'
                  // })

                  },1000)

              }



      },
      addStage () {
          let removeStageArr = this.removeStage
          let stageno = Math.min.apply( Math, removeStageArr)

          var index = removeStageArr.indexOf(Math.min.apply( Math, removeStageArr ));
          if (index > -1) {
              let stage = removeStageArr[index];
              removeStageArr.splice(index, 1);
              this.removeStage = removeStageArr
               var that = this
               that.setDatepicker(that.tournamentStartDate,that.tournamentEndDate,
                that.disableDate,that.availableDate,stage);
          }
          setTimeout(function(){
            $('.ls-datepicker').datepicker().on('changeDate',function(){
          var stage = this.id

             stage = stage.replace("stage_start_date", "");
             if (stage.search('stage_end_date') != -1 || stage.search('stage_continue_date') != -1 ) {
              return false
             }
             if($.inArray( parseInt(stage), that.removeStage ) !== -1  ){
                  return false
              }else{

              var index =  that.disableDate.indexOf($('#stage_end_date'+stage).val());
              if (index > -1) {
                  // let stage = disableDate[index];
                  that.disableDate.splice(index, 1);
                  that.availableDate.push($('#stage_end_date'+stage).val())
                  that.availableDate.splice(that.availableDate.indexOf($('#'+this.id).val()),1)
                  // that.disableDate = disableDate
                  // disableDate
              }
              that.disableDate.push( $('#'+this.id).val());
              $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);
              $('.datestage'+stage).val($('#'+this.id).val())
              }

          });
          },1000)
      },
      calculateStageTime(stage) {
        let vm = this;
        var stageTimeStart = new Date("01/01/2017 "+ $('#stage_start_time'+stage).val());
        var stageTimeEnd = new Date("01/01/2017 " + $('#stage_end_time'+stage).val());
        var stageBreakStart;
        var stageBreakEnd;
        var break_diff = (stageTimeEnd - stageTimeStart) / 60000;
        let totBreaks = vm.stage_break[stage];
        var curBreakDiff = 0;
        // var break_diff = diff;
        let breakDiff = 0;
        if($('#stage_break_chk_'+stage).is(':checked') ) {
          for(let i=1;i<=totBreaks;i++) {
             stageBreakStart = new Date("01/01/2017 " + $('#stage_break_start'+stage+'-'+i).val());
             stageBreakEnd = new Date("01/01/2017 " + $('#stage_continue_time'+stage+'-'+i).val());
             curBreakDiff = parseInt((stageBreakEnd - stageBreakStart) / 60000);
             breakDiff = parseInt(breakDiff + curBreakDiff);
          }
        }
        let totBreakDiff = break_diff - breakDiff;

        if(totBreakDiff > 0){
          var minutes = totBreakDiff % 60;
          var hours = parseInt(totBreakDiff - minutes) / 60;
          var time_val = hours+ '.' +minutes
            minutes = (minutes == '0') ? '00' : minutes
          var time = hours+ ':' +minutes +' hrs'
        }else {
            var time_val = '0.0'
            var time = '00:00 hrs'
        }
        $('#stage_capacity'+stage).val(time_val);
        $('#stage_capacity_min'+stage).val(totBreakDiff);
        $('#stage_capacity1_span'+stage).text(time);
      },
  }
}

</script>
