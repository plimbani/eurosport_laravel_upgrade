<template>
  <div>
    <div class="modal" id="duplicatePitch" tabindex="-1" role="dialog" aria-labelledby="duplicatePitchLabel" style="display: none;" aria-hidden="true"  data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="duplicatePitchLabel">{{$lang.pitch_modal_details}}</h5>
            <button type="button" class="close" @click="closeModal()">
                <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs nav-justified col-md-12" role="tablist">
              <li class="nav-item col-md-6 padding0">
                  <a data-toggle="tab" href="#pitch" role="tab" class="nav-link active text-center"><div class="wrapper-tab">{{$lang.pitch_modal_details}}</div></a>
              </li>
              <li class="nav-item col-md-6 padding0">
                <a data-toggle="tab" href="#availability" role="tab" class="nav-link text-center"><div class="wrapper-tab">{{$lang.pitch_modal_availability}}</div></a>
              </li>
            </ul>
            <div class="tab-content">
              <div id="pitch" role="tabpanel" class="tab-pane active">
                <form method="post" name="frmDuplicatePitchDetails" id="frmDuplicatePitchDetails">
                  <div class="card mb-2">
                    <div class="card-block">
                      <div class="form-group row">
                        <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_location}}*</label>
                        <div class="col-sm-6">
                          <select name="location" id="location" class="form-control" v-validate="'required'" :class="{'is-danger': errors.has('location') }"  v-model = "pitchData.pitchdetail.venue_id" >
                              <option :value="venue.id"  v-model = "pitchData.pitchdetail.venue_id"   v-for="(venue,key) in venues">{{venue.name}}</option>
                          </select>
                          <span class="help is-danger" v-show="errors.has('location')">{{$lang.pitch_modal_details_location_required}}</span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_name}}*</label>
                        <div class="col-sm-6">
                          <input type="text" v-model = "pitchData.pitchdetail.pitch_number"  :class="{'is-danger': errors.has('pitch_number') }" v-validate="'required'"   name="pitch_number"  value="" class="form-control" placeholder="e.g. '1' or '1a'">
                          <i v-show="errors.has('pitch_number')" class="fas fa-warning"></i>
                          <span class="help is-danger" v-show="errors.has('pitch_number')">{{ errors.first('pitch_number') }}</span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_type}}*</label>
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
                      <div class="row">
                        <label class="col-sm-6 form-control-label">{{$lang.pitch_modal_details_size}}*</label>
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
                      </div>
                    </div>
                  </form>
                </div>
                <div id="availability" role="tabpanel" class="tab-pane row">
                  <div class="col-md-12 mb-2">
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
                                                <i class="fas fa-calendar"></i>
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
                                                <i v-show="errors.has('stage_start_time'+day)" class="fas fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Start time is required"></i>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-3">

                                      </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                      <div class="col-md-3">
                                        <div class="checkbox">
                                          <div class="c-input">
                                              <input type="checkbox" class="euro-checkbox stage_break_chk" :name="'stage_break_chk'+day" :id="'stage_break_chk_'+day">
                                              <label :for="'stage_break_chk_'+day">Add break</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div v-if="breakEnable[day]">
                                      <div v-for="n in stage_break[day]">
                                        <div class="row align-items-center mb-3">
                                          <div class="col-md-3">
                                            Break {{n}} start
                                          </div>
                                          <div class="col-md-3">
                                             <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <input type="text" :name="'stage_break_start'+day" :id="'stage_break_start'+day" disabled="disabled" readonly="" :class="['form-control ls-datepicker datestage'+ day]">
                                            </div>
                                          </div>
                                          <div class="col-md-3">
                                            <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                                <div   :class="'align-self-center w-100 chk_disable_'+day ">
                                                    <input type="text" :name="'stage_break_start'+day+'-'+n" v-validate="'required'" :class="[errors.has('stage_break_start'+day+'-'+n)?'is-danger': '', 'form-control ls-timepicker stage_break_start stage_chk_active'+day]"  :id="'stage_break_start'+day+'-'+n" >
                                                </div>
                                                <div class="align-self-center p-1">
                                                    <i v-show="errors.has('stage_break_start'+day+'-'+n)" class="fas fa-warning text-danger" data-placement="top" title="Break start time is required"></i>
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
                                                  <i class="fas fa-calendar"></i>
                                              </span>
                                              <input type="text" :name="'stage_break'+day" :id="'stage_continue_date'+day" disabled="disabled" readonly="" :class="['form-control ls-datepicker datestage'+ day]">
                                            </div>
                                          </div>
                                          <div class="col-md-3">
                                            <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                              <div :class="'align-self-center w-100  chk_disable_'+day ">
                                                  <input type="text" :name="'stage_continue_time'+day+'-'+n" v-validate="'required'" :class="[errors.has('stage_continue_time'+day+'-'+n)?'is-danger': '', 'form-control ls-timepicker stage_continue_time stage_chk_active'+day]"  :id="'stage_continue_time'+day+'-'+n">
                                              </div>
                                              <div class="align-self-center p-1">
                                                  <i v-show="errors.has('stage_continue_time'+day+'-'+n)" class="fas fa-warning text-danger" data-placement="top" title="Continue time is required"></i>
                                              </div>
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
                                                  <i class="fas fa-calendar"></i>
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
                                                <i v-show="errors.has('stage_end_time'+day)" class="fas fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Day end time is required"></i>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                          <span :id="'stage_capacity_span'+day"  class="badge badge-pill pitch-badge-info">0.00</span>
                                          <input type="hidden" :name="'stage_capacity'+day" :id="'stage_capacity'+day" value="0.00">
                                          <input type="hidden" class="stage_capacity_all" :name="'stage_capacity_min'+day" :id="'stage_capacity_min'+day" value="0">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card-footer text-right py-1">
                                      <a href="#" class="text-danger delete-day-link"  @click="stageRemove(day)">{{$lang.pitch_detail_delete_stage}}</a>
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
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="closeModal()">{{$lang.pitch_modal_edit_pitch_cancel_button}}</button>
            <button type="button" class="btn button btn-primary" @click="savePitchDetails()" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.pitch_modal_availability_button_save}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Pitch from '../../../api/pitch.js'
import _ from 'lodash'
var moment = require('moment');
export default {
  data() {
    return  {
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
      // return this.$store.state.Pitch.pitchId
      return this.$store.getters.curPitchId
    },
    pitches: function() {
      return _.cloneDeep(this.$store.state.Pitch.pitches)
    },
    venues: function() {
      return this.$store.state.Tournament.venues
    },
    pitchData: function () {
      var pitchData = _.cloneDeep(this.$store.state.Pitch.pitchData);
      pitchData.pitchdetail.pitch_number = '';
      return pitchData
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
    this.loadPitchData();
  },
  methods: {
    loadPitchData() {
      Plugin.initPlugins(['Select2','TimePickers','MultiSelect','DatePicker', 'addstage'])
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
                    $('.datestage'+i).val(pitchAvailable.stage_start_date);
                    $('#stage_capacity'+i).val(pitchAvailable.stage_capacity)
                    $('#stage_capacity_min'+i).val(pitchAvailable.stage_capacity)
                    var pitchCapacity = pitchAvailable.stage_capacity
                    pitchCapacity = pitchCapacity.toString()
                    var pitchTimeArr = pitchCapacity.split('.');
                    var minutes = pitchCapacity % 60;
                    var hours = (pitchCapacity - minutes) / 60;
                     minutes = (minutes == '0') ? '00' : minutes
                    var time_val = hours+ '.' +minutes

                    $('#stage_capacity_span'+i).text(time_val+ ' hrs')

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
           let curId = $(this)[0].id
           let stage = $(this)[0].id;
           let curTime = ''

           stage = stage.replace('stage_start_time','')
           stage = stage.replace('stage_break_start','')
           stage = stage.replace('stage_continue_time','')
           stage = stage.replace('stage_end_time','')

           let stageArr = 0;
           let breakno=1;
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
                that.disableDate.splice(index, 1);
                that.availableDate.push($('#stage_end_date'+stage).val())
                that.availableDate.splice(that.availableDate.indexOf($('#'+this.id).val()),1)
            }
            that.disableDate.push( $('#'+this.id).val());
            $('.datestage'+stage).val($('#'+this.id).val())
            }
        });
      },1500)
      let this5 = this;
      let vm = this;
      $("#duplicatePitch").on('hidden.bs.modal', function () {
          this5.$root.$emit('pitchrefresh');
          this5.$store.dispatch('SetPitchId',0);
          this5.$root.$emit('getPitchSizeWiseSummary');
          this5.$root.$emit('getLocationWiseSummary');
      });
      $(document).ready(function(){
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
    },
    closeModal() {
      $('#duplicatePitch').modal('hide');
      // this.resetForm();
    },
    displayDay (day) {
      if($.inArray( day,this.removeStage) != -1 ) {
          return false

      }else {
          return true
      }
    },
    savePitchDetails () {
      this.$validator.validateAll().then(() => {
        var time = 0
        $( ".stage_capacity_all" ).each(function( index ) {
          time = time + parseInt($(this).val())
        });
        let pitchData = $("#frmDuplicatePitchDetails").serialize() +'&' + $("#frmPitchAvailable").serialize() + '&tournamentId='+this.tournamentId+'&stage='+this.tournamentDays+'&pitchCapacity='+time
        this.isSaveInProcess = true;
        return axios.post('/api/pitch/create',pitchData).then(response =>  {
            console.log(response.data);
            this.pitchId = response.data.pitchId
            toastr['success']('Pitch has been copied successfully.', 'Success');
            // this.displayPitch();
            this.isSaveInProcess = false;
            $('#duplicatePitch').modal('hide')
            $("#frmDuplicatePitchDetails")[0].reset();
        }).catch(error => {
          if (error.response.status == 401) {
            toastr['error']('Something went wrong', 'Error');
          }
        });
      }).catch(() => {
        toastr['error']('Please complete all required fields on both tabs ', 'Error')
      });
    },
  }
}
</script>