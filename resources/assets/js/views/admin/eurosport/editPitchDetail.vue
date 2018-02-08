<template>
  <div class="modal" id="editPitch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="tabs tabs-primary">
        <div class="modal-header">
          <h5 class="modal-title">Pitch Details - {{pitchData.pitchdetail.pitch_number}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="displayPitch(0)">
              <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="modal-body">
          <ul class="nav nav-tabs col-md-12" role="tablist">
            <li class="nav-item col-md-6 padding0">
                <a data-toggle="tab" href="#pitch" role="tab" class="nav-link active text-center">{{$lang.pitch_modal_details}}</a>
            </li>
            <li class="nav-item col-md-6 padding0">
              <a data-toggle="tab" href="#availability" role="tab" class="nav-link text-center">{{$lang.pitch_modal_availability}}</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="pitch" role="tabpanel" class="tab-pane active">
              <form method="post" name="frmPitchDetail" id="frmPitchDetail">
                <div class="form-group row">
                    <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_location}}*</label>
                    <div class="col-sm-6">
                    <select name="location" id="location" class="form-control" v-validate="'required'" :class="{'is-danger': errors.has('location') }"  v-model = "pitchData.pitchdetail.venue_id" >
                        <option :value="venue.id"  v-model = "pitchData.pitchdetail.venue_id"   v-for="(venue,key) in venues">{{venue.name}}</option>
                    </select>
                     <span class="help is-danger" v-show="errors.has('location')">{{$lang.pitch_modal_details_location_required}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_name}}</label>
                    <div class="col-sm-6">
                        <input type="text" v-model = "pitchData.pitchdetail.pitch_number"  :class="{'is-danger': errors.has('pitch_number1') }" v-validate="'required'"   name="pitch_number1"  value="" class="form-control" placeholder="e.g. '1' or '1a'">
                          <i v-show="errors.has('pitch_number1')" class="fa fa-warning"></i>
                        <span class="help is-danger" v-show="errors.has('pitch_number1')">{{ errors.first('pitch_number1') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_type}}*</label>
                    <div class="col-sm-6">
                        <select name="pitch_type" v-model = "pitchData.pitchdetail.type" id="pitch_type" class="form-control" v-validate="'required'" :class="{'is-danger': errors.has('pitch_type') }">
                            <option value="grass" >Grass</option>
                            <option value="artificial">Artificial</option>
                            <option value="Indoor">Indoor</option>
                            <option value="Other">Other</option>
                        </select>
                        <span class="help is-danger" v-show="errors.has('pitch_type')">{{$lang.pitch_modal_details_type_required}}</span>
                    </div>
                </div>
                <div class="  row">
                    <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_size}}*</label>
                    <div class="col-sm-6">
                        <select name="pitch_size" id="pitch_size"  v-model = "pitchData.pitchdetail.size"  class="form-control pull-left" v-validate="'required'" :class="{'is-danger': errors.has('pitch_size') }">
                             <option value="5-a-side" >{{$lang.pitch_modal_details_size_side}}</option>
                            <option value="7-a-side">{{$lang.pitch_modal_details_size_side_one}}</option>
                            <option value="8-a-side">{{$lang.pitch_modal_details_size_side_two}}</option>
                            <option value="9-a-side">{{$lang.pitch_modal_details_size_side_three}}</option>
                            <option value="11-a-side">{{$lang.pitch_modal_details_size_side_four}}</option>
                        </select>
                        <span class="help is-danger" v-show="errors.has('pitch_size')">{{$lang.pitch_modal_details_size_required}}</span>
                    </div>
                </div>
                  <!--<div class="col-md-12">
                      <button type="button" id="add_stage" @click="nextStage()"  class="btn btn-primary">Next</button>
                  </div>-->
              </form>
            </div>
            <div id="availability" role="tabpanel" class="tab-pane">
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
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="hidden" :name="'totalBreaksForStage'+day" :id="'totalBreaksForStage'+day" v-model="stage_break[day]">
                                    <input type="text" :name="'stage_start_date'+day" :id="'stage_start_date'+day" value="" :class="[ errors.has('stage_start_date'+day)?'is-danger':'','form-control datestage'+day] " readonly="readonly">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                    <div class="align-self-center w-100">
                                        <input :name="'stage_start_time'+day" v-validate="'required'" :class="[errors.has('stage_start_time'+day)?'is-danger': '', 'form-control ls-timepicker stage_start_time']"  :id="'stage_start_time'+day"  type="text" >
                                    </div>
                                    <div class="align-self-center p-1">
                                        <i v-show="errors.has('stage_start_time'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Start time is required"></i>
                                    </div>
                                    <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                </div>
                              </div>
                              <div class="col-md-3">

                              </div>
                          </div>
                          <div class="row align-items-center mb-3">
                            <!-- <div class="col-md-3">
                                Break Start
                            </div> -->
                            <div class="col-md-3">
                            <input type="checkbox" :name="'stage_break_chk'+day" class="mr-1 stage_break_chk"  :id="'stage_break_chk_'+day" >Check to add a break
                            </div>
                            <!-- <div class="col-md-3"> -->
                                <!-- <div class="d-flex flex-nowrap justify-content-between align-items-center"> -->
                              <!--       <div :class="'align-self-center w-100  stageInvisible chk_disable_'+day ">
                                        <input type="text" :name="'stage_break_start'+day" v-validate="'required'" :class="[errors.has('stage_break_start'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day]" :id="'stage_break_start'+day" >
                                    </div>
                                    <div class="align-self-center p-1">
                                        <i v-show="errors.has('stage_break_start'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Break start time is required"></i>
                                    </div> -->
                                    <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                <!-- </div> -->
                          <!--   </div>
                              <div class="col-md-3">

                              </div> -->
                          </div>
                          <!-- <div :class="'row align-items-center mb-3 stageInvisible chk_disable_'+day "> -->
                            <!-- <div class="col-md-3">
                                Day {{day}} continued
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" :name="'stage_continue_date'+day" :id="'stage_continue_date'+day" disabled="disabled" readonly="" :class="['form-control sdate ls-datepicker datestage'+ day]">
                                </div>
                            </div> -->
                            <!-- <div class="col-md-3"> -->
                                <!-- <div class="d-flex flex-nowrap justify-content-between align-items-center"> -->
                                   <!--  <div :class="'align-self-center w-100 ' ">
                                        <input type="text" :name="'stage_continue_time'+day" v-validate="'required'" :class="[errors.has('stage_continue_time'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day]"  :id="'stage_continue_time'+day">
                                    </div>
                                    <div class="align-self-center p-1">
                                        <i v-show="errors.has('stage_continue_time'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Continue time is required"></i>
                                    </div> -->
                                    <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                               <!--  </div>
                            </div>
                            <div class="col-md-3">

                            </div>
                          </div>   -->
                             <div v-if="breakEnable[day]">
                                <div v-for="n in stage_break[day]">
                                  <div class="row align-items-center mb-3">
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
                                      Break {{n}} end
                                    </div>
                                    <div class="col-md-3">
                                      <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="jv-icon jv-calendar"></i>
                                        </span>
                                        <input type="text" :name="'stage_break'+day" :id="'stage_continue_date'+day" disabled="disabled" readonly="" :class="['form-control ls-datepicker datestage'+ day]">
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
                                    <a href="#" :class="'btn btn-danger  chk_disable_'+day " v-if="stage_break[day] > 1"  @click="removeBreak(day)">{{$lang.pitch_detail_break_remove}}</a>
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
                                        <i class="fa fa-calendar"></i>
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
                                        <i v-show="errors.has('stage_end_time'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Day end time is required"></i>
                                    </div>
                                    <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <span :id="'stage_capacity_span'+day"  class="badge badge-pill pitch-badge-info">0.00</span>
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
                <div class="col-md-12">
                    <button type="button" id="add_stage" @click="addStage()" :disabled="removeStage.length==0" class="btn btn-primary">{{$lang.pitch_modal_availability_button_add}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="displayPitch(0)">{{$lang.pitch_modal_edit_pitch_cancel_button}} </button>
            <button type="button" class="btn button btn-primary" @click="savePitchDetails()" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.pitch_modal_availability_button_save}}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script >
import _ from 'lodash'
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
                isSaveInProcess: false,
                }
        },
        computed: {
            tournamentTime: function() {
                return this.$store.state.Tournament.currentTotalTime
            },
            pitchId : function() {
                return this.$store.state.Pitch.pitchId
            },
            pitches: function() {
                return _.cloneDeep(this.$store.state.Pitch.pitches)
            },
            venues: function() {
                return this.$store.state.Tournament.venues
            },
            pitchData: function () {
                return _.cloneDeep(this.$store.state.Pitch.pitchData)
            },
            stageAvailable: function () {
                return _.cloneDeep(this.$store.getters.availableStage)
            },
            pitchCapacity: function() {
                return  _.cloneDeep(this.$store.state.Pitch.pitchCapacity)
            },
            pitchAvailableBalance : function() {
                let pitchavailableBalance = []
                let tournamentAvailableTime =  this.tournamentTime
                let pitchCapacityTime =this.pitchCapacity
                let availableTime = tournamentAvailableTime - pitchCapacityTime
                if(availableTime < 0) {
                    availableTime = 0
                }
                var minutes = availableTime % 60;
                var hours = (availableTime - minutes) / 60;
                pitchavailableBalance.push (hours,minutes)
                return pitchavailableBalance

            }

        },
        mounted(){
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            let capacity={}
            let sDate = []
            var startDate = new Date(moment(this.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY'))
            var obj ={}
            let this1= this
            var stageBreak = '{"stageBreak":[]}';
            var sBreak = JSON.parse(stageBreak);
            let arr = [];
            let brk = [];
            setTimeout( function() {

            $('[data-toggle="tooltip"]').tooltip();
            for(let i=1;i<=this1.tournamentDays;i++){
                capacity['day'+i]= '0.00'
                $('.datestage'+i).val(moment(startDate, 'MM/DD/YYYY').format('DD/MM/YYYY'));
                 this1.availableDate.push($('.datestage'+i).val())
                 this1.removeStage.push(i);
                  _.find(this1.pitchData.pitchAvailable, function (pitchAvailable) {


                    // => Logs `1` then `2`.
                    if(pitchAvailable.stage_no == i){

                       sBreak['stageBreak'].push({"day":i,"break":pitchAvailable.pitchbreaks.length})
                        _.map( sBreak['stageBreak'], function(s) {
                          arr[s.day] = s.break;
                           if(s.break > 0){

                            brk[s.day] = true;
                           }else{
                            brk[s.day] = false;
                           }
                        });
                        this1.stage_break= arr;
                        this1.breakEnable= brk;
                        $('#stage_start_time'+pitchAvailable.stage_no).val(pitchAvailable.stage_start_time)
                        $('#stage_end_time'+pitchAvailable.stage_no).val(pitchAvailable.stage_end_time)
                        if(pitchAvailable.break_enable == 1){

                        $('#stage_break_chk_'+i).attr('checked','checked')
                        $('.chk_disable_'+i).removeClass('stageInvisible')
                        //Pitch break code start
                        // return false;

                        let bCnt = 1;

                          _.forEach(pitchAvailable.pitchbreaks, function(pitchBreak,bCnt)  {

                            let breakCnt = parseInt(bCnt)+1;
                            setTimeout(function(){
                              $('#stage_break_start'+pitchAvailable.stage_no+'-'+breakCnt).val(pitchBreak.break_start);
                              $('#stage_continue_time'+pitchAvailable.stage_no+'-'+breakCnt).val(pitchBreak.break_end);
                              $('.datestage'+i).val($('#stage_start_date'+i).val())
                            },2000)


                          });

                        }else{

                           $('.stage_chk_active'+i).attr('disabled','disabled')
                        }

                        this1.disableDate.push(pitchAvailable.stage_start_date);
                        // var index =  this1.availableDate.indexOf(pitchAvailable.stage_start_date);
                        // this1.availableDate.splice(index, 1);
                        $('.datestage'+i).val(pitchAvailable.stage_start_date);
                        $('#stage_capacity'+i).val(pitchAvailable.stage_capacity)
                        $('#stage_capacity_min'+i).val(pitchAvailable.stage_capacity)
                        // $('#stage_capacity_span'+i).text(pitchAvailable.stage_capacity+ ' hrs')
                        var pitchCapacity = pitchAvailable.stage_capacity
                        // var pitchCapacity = '7.3'
                        pitchCapacity = pitchCapacity.toString()
                        var pitchTimeArr = pitchCapacity.split('.');
                        var minutes = pitchCapacity % 60;
                        var hours = (pitchCapacity - minutes) / 60;
                         minutes = (minutes == '0') ? '00' : minutes
                        var time_val = hours+ '.' +minutes


                        // var pitchTime = parseInt(pitchTimeArr[0]*60)+parseInt(pitchTimeArr[1])
                        $('#stage_capacity_span'+i).text(time_val+ ' hrs')

                        // $('#stage_capacity_min'+i).val(pitchTime)

                        this1.removeStage.splice(this1.removeStage.indexOf(i), 1);

                        if(!pitchAvailable.break_enable){
                         // $('.chk_disable_'+i).addClass('stageInvisible')
                        }

                    }
                obj['date'+i] = $('.datestage'+i).val();
                capacity['day'+i]= pitchAvailable.stage_start_date
                });
                startDate.setDate(new Date(moment(this1.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY')).getDate() + i)

            }

              // this.stage_capacity.push(capacity)

            this1.availableDate = _.difference(this1.availableDate, this1.disableDate);
            let disableDate = this1.disableDate;
            this1.stage_date.push(obj)
            $('.ls-datepicker').datepicker('setDatesDisabled', this1.disableDate);
            this1.stage_capacity.push(capacity)
            setTimeout(function(){
                $('.ls-timepicker').timepicker({
                    minTime: '08:00',
                    maxTime: '23:00',
                    timeFormat: 'H:i'
                });
             },500)

            $('#frmPitchAvailable').on("change",'.ls-timepicker',function(){
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
               // let updatedTime = '00:00';
               if(curId.indexOf('stage_break_start') >= 0 || curId.indexOf('stage_continue_time') >= 0) {
                  stageArr = stage.split('-');
                  stage = stageArr[0];
                  breakno =   stageArr[1];
                }


                if( curId.indexOf('stage_start_time') >= 0){
                  curTime = $('#stage_start_time'+stage).val()
                  if($('#stage_break_chk_'+stage).is(':checked')){
                    $('#stage_break_start'+stage+'-'+breakno).removeAttr('disabled')
                    // $('.stage_chk_active'+stage).attr('disabled','disabled')
                    $('#stage_end_time'+stage).attr('disabled','disabled');

                  }else{
                    setTimeout(function(){
                      $('.stage_chk_active'+stage).val($('#stage_start_time'+stage).val())
                    },100)
                    $('#stage_end_time'+stage).removeAttr('disabled')
                  }

                }else if(curId.indexOf('stage_break_start') >= 0) {
                  if($('#stage_break_chk_'+stage).is(':checked')){
                    $('#stage_continue_time'+stage+'-'+breakno).removeAttr('disabled');
                    $('#stage_end_time'+stage).attr('disabled','disabled')
                    curTime = $('#stage_break_start'+stage+'-'+breakno).val()
                    vm.removeBreak(stage,breakno);
                  }
                }else if(curId.indexOf('stage_continue_time') >= 0) {
                  if($('#stage_break_chk_'+stage).is(':checked')){
                    $('#stage_end_time'+stage).removeAttr('disabled')
                    curTime = $('#stage_continue_time'+stage+'-'+breakno).val()
                  }
                  vm.removeBreak(stage,breakno);
                }else if(curId.indexOf('stage_end_time') >= 0) {
                    curTime = $('#stage_end_time'+stage).val()
                }
                let newTime = ''
                 let updatedTime =  curTime.split(':')
                if(curTime.indexOf('pm') >= 0 && (updatedTime[0]!= '12')) {
                    updatedTime
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

                    $('#stage_break_start'+stage+'-'+breakno).timepicker({
                        minTime:  newTime,
                        maxTime: '23:00',
                        timeFormat: 'H:i'
                    });

                     $('#stage_break_start'+stage+'-'+breakno).timepicker('option', 'minTime', newTime);
                    $('#stage_end_time'+stage).timepicker({
                        minTime:  newTime,
                        maxTime: '23:00',
                        timeFormat: 'H:i'
                    });
                     $('#stage_end_time'+stage).timepicker('option', 'minTime', newTime);

                    $('#stage_break_start'+stage+'-'+breakno).val('')

                    $('#stage_continue_time'+stage+'-'+breakno).val('')

                    $('#stage_end_time'+stage).val('')
                }
                if(curId.indexOf('stage_break_start') >= 0){

                   $('#stage_continue_time'+stage+'-'+breakno).timepicker({
                        minTime: newTime,
                        maxTime: '23:00',
                        timeFormat: 'H:i'
                    });
                     $('#stage_continue_time'+stage+'-'+breakno).timepicker('option', 'minTime', newTime);

                    $('#stage_continue_time'+stage+'-'+breakno).val('')

                    $('#stage_end_time'+stage).val('')

                }
                if(curId.indexOf('stage_continue_time') >= 0 ){

                    $('#stage_end_time'+stage).timepicker({
                        minTime:  newTime,
                        maxTime: '23:00',
                        'timeFormat': 'H:i'
                    });

                    $('#stage_end_time'+stage).val('')
                }
                this1.setStageCapacity(stage,breakno);

            })

            var that = this1
                 $('.ls-datepicker').datepicker().on('changeDate',function(){
                // $('#frmPitchAvailable').on("change",'.ls-datepicker',function(){
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
                    $('.datestage'+stage).val($('#'+this.id).val())
                    }

                });
            },1500)
            let this5 = this;
            let vm = this;
            $("#editPitch").on('hidden.bs.modal', function () {
                this5.$root.$emit('pitchrefresh');
                this5.$store.dispatch('SetPitchId',0);
                this5.$root.$emit('getPitchSizeWiseSummary');
            });
            $(document).ready(function(){
              // let vm1 = this;
              $("body").on('click','.stage_break_chk',function(){
              let stageId = this.id
              let stage = stageId.replace('stage_break_chk_','')
              let curTime = '08:00';
              if(this.checked){
                if($('#stage_start_time'+stage).val()!=''){
                  $('#stage_break_start'+stage)
                  $('.stage_chk_active'+stage).removeAttr('disabled','disabled')
                  $('#stage_end_time'+stage).val('');

                  curTime =  $('#stage_start_time'+stage).val();
                }else{
                  $('.stage_chk_active'+stage).attr('disabled','disabled')
                  $('#stage_end_time'+stage).attr('disabled','disabled');
                }
                $('.chk_disable_'+stage).removeClass('stageInvisible')
                vm.breakEnable[stage] = true;
                let brk = vm.breakEnable;
                vm.breakEnable = [];
                vm.breakEnable = brk
                vm.stage_break[stage] = 1;

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
                   $('#stage_end_time'+stage).timepicker('option', 'minTime', newTime);
                   $('.datestage'+stage).val($('#stage_start_date'+stage).val())
                },500)
              }else{
                  $('.stage_chk_active'+stage).val($('#stage_start_time'+stage).val())
                  $('.stage_chk_active'+stage).attr('disabled','disabled')
                  $('#stage_end_time'+stage).removeAttr('disabled','disabled')
                  $('.chk_disable_'+stage).addClass('stageInvisible');
                  vm.breakEnable[stage] = false;
                  let brk = vm.breakEnable;
                  vm.breakEnable = [];
                  vm.breakEnable = brk
              }
              vm.calculateStageTime(stage);
              })
            })
            // $("#addPitchModal").on('hidden.bs.modal', function () {
            //     console.log('msg')
            //     $('#frmPitchDetail')[0].reset()
            //     $('#frmPitchAvailable')[0].reset()
            //     this.getAllPitches()

            // });


             // this.getAllPitches()
        },
        methods: {
            displayPitch() {
              this.$root.$emit('displayPitch',0)
            },
            getAllPitches() {
                // this.$store.dispatch('SetPitches',this.tournamentId);

            },
            setStageCapacity(stage,breakno) {
              let vm =this;
              if( $('#stage_start_time'+stage).val() == '' || $('#stage_end_time'+stage).val() == '' || $('#stage_break_start'+stage+'-'+breakno).val() == '' || $('#stage_continue_time'+stage+'-'+breakno).val() == ''  ) {
                    $('#stage_capacity_span'+stage).text('0.00 hrs');
                    $('#stage_capacity'+stage).val('0.00');
                } else {
                  vm.calculateStageTime(stage);
                }
            },
            nextStage() {
                $('.nav-tabs a[href="#availability"]').tab('show');
            },

            addBreak(day) {
              let this1 = this;
                // this.breakEnable[day] = false;
                let brk = this.breakEnable;
                let last_break = this.stage_break[day];

                if($('#break_start_time'+day+'-'+last_break).val() != '' && $('#stage_continue_time'+day+'-'+last_break).val() != '') {
                  let curTime =  $('#stage_continue_time'+day+'-'+last_break).val();

                  let updatedTime =curTime.split(':');

                  let hrs = parseInt(updatedTime[0])
                  let min = updatedTime[1].split(' ')[0]  == '30' ? '30' : '00'
                  let newTime = hrs+':'+min+':00'
                  this.breakEnable = [];
                  let  curBreakNo =   this.stage_break[day] = parseInt(this.stage_break[day]) +1;
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
                     $('#stage_end_time'+day).timepicker('option', 'minTime', newTime);
                  },1000)
                } else {
                  toastr['error']('Please add last break time ', 'Error')
                }


              },

            removeBreak(day,brkNo = 0) {
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
                  this.setStageCapacity(day,this.stage_break[day]);
                }
              }

            },

            savePitchDetails () {
                this.$validator.validateAll().then(() => {
                    var time = 0
                    $( ".stage_capacity_all" ).each(function( index ) {
                      time = time + parseInt($(this).val())

                    });
                    if(time < 0) {
                        time = 0
                    }
                    // var minutes = time % 60;
                    // var hours = (time - minutes) / 60;
                    // var time_val = hours+ '.' +minutes

                    // $("#frmPitchAvailable").serialize()
                    let pitchData = $("#frmPitchDetail").serialize() +'&' + $("#frmPitchAvailable").serialize() + '&tournamentId='+this.tournamentId+'&stage='+this.tournamentDays+'&pitchCapacity='+time

                    this.isSaveInProcess = true;
                    return axios.post('/api/pitch/edit/'+this.pitchId,pitchData).then(response =>  {
                        toastr['success']('Pitch detail has been updated successfully.', 'Success');
                        this.displayPitch();
                        this.isSaveInProcess = false;
                        //setTimeout(Plugin.reloadPage, 1000);
                        $('#editPitch').modal('hide')
                    }).catch(error => {
                        this.isSaveInProcess = false;
                        if (error.response.status == 401) {
                            // toastr['error']('Invalid Credentials', 'Error');
                        } else {
                            //   happened in setting up the request that triggered an Error
                        }
                    });


                }).catch(() => {
                    toastr['error']('Please fill all required fields', 'Error')
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
                }
                // this.stageShowday = false

            },
            displayDay (day) {
                if($.inArray( day,this.removeStage) != -1 ) {
                    return false

                }else {
                    return true
                }
            },
            // setStageCapacity(stage) {

            //     let stage_start_date = $('#stage_start_date'+stage).val();
            //     let stage_start_time = $('#stage_start_time'+stage).val();
            //     let stage_end_date = $('#stage_end_date'+stage).val();
            //     let stage_end_time = $('#stage_end_time'+stage).val();
            //     var timeStart = new Date("01/01/2017 " + stage_start_time);
            //     var timeEnd = new Date("01/01/2017 " + stage_end_time);
            //     if(timeStart && timeEnd) {
            //         var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds
            //         var minutes = diff % 60;
            //         var hours = (diff - minutes) / 60;
            //         minutes = (minutes == '0') ? '00' : minutes
            //        this.stage_capacity['day'+stage] = hours+ ':' +minutes
            //     }
            //     // return hours+ ':' +minutes
            //     // return 10.30 *stage
            // },
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
                            minTime: '08:00',
                            maxTime: '23:00',
                            timeFormat: 'H:i'
                        })
                        $('#stage_break_start'+stage+',#stage_continue_time'+stage+',#stage_end_time'+stage).attr('disabled','disabled');
                        // $('#stage_break_start'+stage).timepicker({
                        //     minTime: '08:00:00',
                        //     maxTime: '19:00:00'
                        // })
                        // $('#stage_continue_time'+stage).timepicker({
                        //     minTime: '08:00:00',
                        //     maxTime: '19:00:00'
                        // })
                        // $('#stage_end_time'+stage).timepicker({
                        //     minTime: '08:00:00',
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
                     that.setDatepicker(that.tournamentStartDate,that.tournamentEndDate,that.disableDate,that.availableDate,stage);
                     $('.ls-datepicker').datepicker('setStartDate', that.tournamentStartDate);
                }
              setTimeout(function(){
               $('.ls-datepicker').datepicker().on('changeDate',function(){
                              // $('#frmPitchAvailable').on("change",'.ls-datepicker',function(){
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
                    $('.datestage'+stage).val($('#'+this.id).val())
                    }

                });
              },1000)
            },
            calculateStageTime(stage) {
              let vm =this;
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
              $('#stage_capacity_span'+stage).text(time);
            },
        }
    }

</script>
