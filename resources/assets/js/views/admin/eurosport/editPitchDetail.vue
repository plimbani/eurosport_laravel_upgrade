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
                                        <label class="col-sm-5 form-control-label">Number*</label>
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
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">Location*</label>
                                        <div class="col-sm-6">
                                        <select name="location" id="location" class="form-control"  v-model = "pitchData.pitchdetail.venue_id" >
                                            <option :value="venue.id"  v-model = "pitchData.pitchdetail.venue_id"   v-for="(venue,key) in venues">{{venue.name}}</option>

                                        </select>

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
                                                <option value="10-a-side">{{$lang.pitch_modal_details_size_side_four}}</option>
                                                <option value="Handball">{{$lang.pitch_modal_details_size_side_handball}}</option>
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
                                    <div class="col-md-4">
                                        <span>Stage</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>Date</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Time</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Capacity</span>
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
                                                                Stage {{day}}
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                    <input type="text" :name="'stage_start_date'+day" :id="'stage_start_date'+day" value="" :class="[ errors.has('stage_start_date'+day)?'is-danger':'','form-control ls-datepicker datestage'+day] " >
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
                                                            <div class="col-md-3">
                                                                Break Start
                                                            </div>
                                                            <div class="col-md-3">
                                                            <input type="checkbox" :name="'stage_break_chk'+day" class="mr-1 stage_break_chk"  :id="'stage_break_chk_'+day" >Check to add a break
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                                                    <div :class="'align-self-center w-100  stageInvisible chk_disable_'+day ">
                                                                        <input type="text" :name="'stage_break_start'+day" v-validate="'required'" :class="[errors.has('stage_break_start'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day]" :id="'stage_break_start'+day" >
                                                                    </div>
                                                                    <div class="align-self-center p-1">
                                                                        <i v-show="errors.has('stage_break_start'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Break start time is required"></i>
                                                                    </div>
                                                                    <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">

                                                            </div>
                                                        </div>
                                                        <div
                                                        :class="'row align-items-center mb-3   stageInvisible chk_disable_'+day "
                                                        >
                                                            <div class="col-md-3">
                                                                Stage {{day}} continued
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                    <input type="text" :name="'stage_continue_date'+day" :id="'stage_continue_date'+day" disabled="disabled" readonly="" :class="['form-control sdate ls-datepicker datestage'+ day]">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                                                    <div :class="'align-self-center w-100  stageInvisible chk_disable_'+day ">
                                                                        <input type="text" :name="'stage_continue_time'+day" v-validate="'required'" :class="[errors.has('stage_continue_time'+day)?'is-danger': '', 'form-control ls-timepicker stage_chk_active'+day]"  :id="'stage_continue_time'+day">
                                                                    </div>
                                                                    <div class="align-self-center p-1">
                                                                        <i v-show="errors.has('stage_continue_time'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Continue time is required"></i>
                                                                    </div>
                                                                    <!-- <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span> -->
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">

                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-3">
                                                            <div class="col-md-3">
                                                                Stage {{day}} end
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                    <input type="text" :name="'stage_end_date'+day" :id="'stage_end_date'+day" disabled="disabled" readonly="" :class="['form-control  ls-datepicker datestage'+ day]">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                                                    <div class="align-self-center w-100">
                                                                        <input :name="'stage_end_time'+day" :id="'stage_end_time'+day" type="text"  v-validate="'required'" :class="[errors.has('stage_end_time'+day)?'is-danger': '', 'form-control ls-timepicker']">
                                                                    </div>
                                                                    <div class="align-self-center p-1">
                                                                        <i v-show="errors.has('stage_end_time'+day)" class="fa fa-warning text-danger" data-toggle="tooltip" data-placement="top" title="Stage end time is required"></i>
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
                                                        <a href="#" class="btn btn-danger"  @click="stageRemove(day)">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="add_stage" @click="addStage()" :disabled="removeStage.length==0" class="btn btn-primary">Add Stage</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="displayPitch(0)">Cancel </button>
                    <button type="button" class="btn btn-primary" @click="savePitchDetails()">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
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
                'availableDate': []
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
            setTimeout( function() {

            $('.ls-datepicker').datepicker('setStartDate', this1.tournamentStartDate);

            $('.ls-datepicker').datepicker('setEndDate', this1.tournamentEndDate);
            for(let i=1;i<=this1.tournamentDays;i++){
                capacity['day'+i]= '0.00'

                $('.datestage'+i).datepicker('setDate', moment(startDate, 'MM/DD/YYYY').format('DD/MM/YYYY'))

                 this1.availableDate.push($('.datestage'+i).val())
                 this1.removeStage.push(i)
                    _.find(this1.pitchData.pitchAvailable, function (pitchAvailable) {

                    if(pitchAvailable.stage_no == i){
                        if(pitchAvailable.break_enable == 1){
                          $('#stage_break_chk_'+i).attr('checked','checked')
                        }else{
                           $('.stage_chk_active'+i).attr('disabled','disabled')
                        }
                        $('#stage_start_time'+pitchAvailable.stage_no).val(pitchAvailable.stage_start_time)
                        $('#stage_break_start'+pitchAvailable.stage_no).val(pitchAvailable.break_start_time)
                        $('#stage_continue_time'+pitchAvailable.stage_no).val(pitchAvailable.break_end_time)
                        $('#stage_end_time'+pitchAvailable.stage_no).val(pitchAvailable.stage_end_time)
                        this1.disableDate.push(pitchAvailable.stage_start_date)
                        // var index =  this1.availableDate.indexOf(pitchAvailable.stage_start_date);
                        // this1.availableDate.splice(index, 1);
                        $('.datestage'+i).datepicker('setDate', pitchAvailable.stage_start_date)
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
                          $('.chk_disable_'+i).addClass('stageInvisible')
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
                    maxTime: '19:00',
                    timeFormat: 'H:i'
                });
             },500)

            $('#frmPitchAvailable').on("change",'.ls-timepicker',function(){
               // this.stageCapacityCalc(1)
               let curId = $(this)[0].id
               let stage = $(this)[0].id;
               let curTime = ''

               // console.log(stage_id)
               stage = stage.replace('stage_start_time','')
               stage = stage.replace('stage_break_start','')
               stage = stage.replace('stage_continue_time','')
               stage = stage.replace('stage_end_time','')

                if( curId.indexOf('stage_start_time') >= 0){
                  curTime = $('#stage_start_time'+stage).val()
                  if($('#stage_break_chk_'+stage).is(':checked')){
                    $('#stage_break_start'+stage).removeAttr('disabled')
                    $('#stage_continue_time'+stage).attr('disabled','disabled')
                    $('#stage_end_time'+stage).attr('disabled','disabled')
                  }else{
                    setTimeout(function(){
                      $('.stage_chk_active'+stage).val($('#stage_start_time'+stage).val())
                    },100)
                    $('#stage_end_time'+stage).removeAttr('disabled')
                  }




                }else if(curId.indexOf('stage_break_start') >= 0) {
                  if($('#stage_break_chk_'+stage).is(':checked')){
                    $('#stage_continue_time'+stage).removeAttr('disabled')
                    $('#stage_end_time'+stage).attr('disabled','disabled')
                    curTime = $('#stage_break_start'+stage).val()
                  }
                }else if(curId.indexOf('stage_continue_time') >= 0) {
                  if($('#stage_break_chk_'+stage).is(':checked')){
                    $('#stage_end_time'+stage).removeAttr('disabled')
                    curTime = $('#stage_continue_time'+stage).val()
                  }
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

                    $('#stage_break_start'+stage).timepicker({
                        minTime:  newTime,
                        maxTime: '19:00',
                        timeFormat: 'H:i'
                    });
                    $('#stage_end_time'+stage).timepicker({
                        minTime:  newTime,
                        maxTime: '19:00',
                        timeFormat: 'H:i'
                    });
                    $('#stage_break_start'+stage).val('')


                    $('#stage_continue_time'+stage).val('')


                    // $('#stage_end_time'+stage).val('')
                }
                if(curId.indexOf('stage_break_start') >= 0){

                   $('#stage_continue_time'+stage).timepicker({
                        minTime: newTime,
                        maxTime: '19:00',
                        timeFormat: 'H:i'
                    });
                    $('#stage_continue_time'+stage).val('')

                    $('#stage_end_time'+stage).val('')

                }
                if(curId.indexOf('stage_continue_time') >= 0 ){

                    $('#stage_end_time'+stage).timepicker({
                        minTime:  newTime,
                        maxTime: '19:00',
                        'timeFormat': 'H:i'
                    });
                    $('#stage_end_time'+stage).val('')
                }

                if( $('#stage_start_time'+stage).val() == '' || $('#stage_end_time'+stage).val() == '' || $('#stage_break_start'+stage).val() == '' || $('#stage_continue_time'+stage).val() == ''  ) {
                    $('#stage_capacity_span'+stage).text('0.00 hrs');
                    $('#stage_capacity'+stage).val('0.00');
                }else {
                 var stageTimeStart = new Date("01/01/2017 "+ $('#stage_start_time'+stage).val());
                var stageTimeEnd = new Date("01/01/2017 " + $('#stage_end_time'+stage).val());
                var stageBreakStart = new Date("01/01/2017 " + $('#stage_break_start'+stage).val());
                var stageBreakEnd = new Date("01/01/2017 " + $('#stage_continue_time'+stage).val());

                    var diff1 = (stageBreakStart - stageTimeStart) / 60000; //dividing by seconds and milliseconds
                    var diff2 = (stageTimeEnd - stageBreakEnd) / 60000; //dividing by seconds and milliseconds
                    var diff = diff1 + diff2
                    if(diff > 0){
                      var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
                    var time_val = hours+ '.' +minutes

                    minutes = (minutes == '0') ? '00' : minutes
                    var time = hours+ ':' +minutes +' hrs'
                }else {
                    var time_val = '0.00'
                    var time = '00:00 hrs'
                }
                $('#stage_capacity'+stage).val(time_val);
                $('#stage_capacity_min'+stage).val(diff);
                $('#stage_capacity_span'+stage).text(time);

                }

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
            },1500)
            let this5 = this
            $("#editPitch").on('hidden.bs.modal', function () {
                 this5.$store.dispatch('SetPitchId',0);
            });
            $(document).ready(function(){
              $("body").on('click','.stage_break_chk',function(){

                let stageId = this.id
                let stage = stageId.replace('stage_break_chk_','')
                if(this.checked){
                  if($('#stage_start_time'+stage).val()!=''){
                    $('#stage_break_start'+stage)
                    $('.stage_chk_active'+stage).removeAttr('disabled','disabled')
                  }
                  $('.chk_disable_'+stage).removeClass('stageInvisible')
                }else{

                  $('.stage_chk_active'+stage).val($('#stage_start_time'+stage).val())
                  $('.stage_chk_active'+stage).attr('disabled','disabled')
                  $('#stage_end_time'+stage).removeAttr('disabled','disabled')
                  $('.chk_disable_'+stage).addClass('stageInvisible')
                  // $('.stage_chk_active'+this.id).hide()

                }
              })
            })
            // $("#addPitchModal").on('hidden.bs.modal', function () {
            //     console.log('msg')
            //     $('#frmPitchDetail')[0].reset()
            //     $('#frmPitchAvailable')[0].reset()
            //     this.getAllPitches()

            // });

             // $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
             // $('.sdate').datepicker('setDatesDisabled', this.disableDate);

             // this.getAllPitches()
        },
        methods: {
            displayPitch() {
              this.$root.$emit('displayPitch',0)
            },
            getAllPitches() {
                // this.$store.dispatch('SetPitches',this.tournamentId);

            },
            nextStage() {
                $('.nav-tabs a[href="#availability"]').tab('show');


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
                    return axios.post('/api/pitch/edit/'+this.pitchId,pitchData).then(response =>  {
                        toastr['success']('Pitch detail has been updated successfully.', 'Success');
                        this.displayPitch()
                        $('#editPitch').modal('hide')
                    }).catch(error => {
                        if (error.response.status == 401) {
                            // toastr['error']('Invalid Credentials', 'Error');
                        } else {
                            //   happened in setting up the request that triggered an Error
                            console.log('Error', error.message);
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
                    $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
                    $('.datestage'+day).datepicker('clearDates')
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
            setStageCapacity(stage) {

                let stage_start_date = $('#stage_start_date'+stage).val();
                let stage_start_time = $('#stage_start_time'+stage).val();
                let stage_end_date = $('#stage_end_date'+stage).val();
                let stage_end_time = $('#stage_end_time'+stage).val();
                var timeStart = new Date("01/01/2017 " + stage_start_time);
                var timeEnd = new Date("01/01/2017 " + stage_end_time);
                if(timeStart && timeEnd) {
                    var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds
                    var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
                    minutes = (minutes == '0') ? '00' : minutes
                   this.stage_capacity['day'+stage] = hours+ ':' +minutes
                }
                // return hours+ ':' +minutes
                // return 10.30 *stage
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
                        $('.datestage'+stage).datepicker();
                        $('.datestage'+stage).datepicker('setStartDate', tStartDate)
                        $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                        $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                        $('.datestage'+stage).datepicker('setDatesDisabled', disableDate);
                         $('.datestage'+stage).datepicker('setDate', availDate)
                        $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);



                        $('#stage_start_time'+stage).timepicker({
                            minTime: '08:00',
                            maxTime: '19:00',
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

                }

            },



        }
    }

</script>
