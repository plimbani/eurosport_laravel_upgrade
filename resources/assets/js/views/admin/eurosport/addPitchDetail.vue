<template>
    <div class="modal fade" id="addPitchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="tabs tabs-primary">
                    <div class="modal-header">
                        <h5 class="modal-title">Pitch Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                            <select name="location" id="location" class="form-control" >
                                                <option :value="venue.id" v-for="(venue,key) in venues">{{venue.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_number}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('pitch_number') }" name="pitch_number"   class="form-control">
                                                <i v-show="errors.has('pitch_number')" class="fa fa-warning"></i>
                                                <span class="help is-danger" v-show="errors.has('pitch_number')">{{ errors.first('pitch_number') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_type}}*</label>
                                        <div class="col-sm-6">
                                            <select name="pitch_type" id="pitch_type" class="form-control">
                                                <option value="Grass" selected="">{{$lang.pitch_modal_details_grass}}</option>
                                                <option value="Artificial">{{$lang.pitch_modal_details_artificial}}</option>
                                                <option value="Indoor">{{$lang.pitch_modal_details_indoor}}</option>
                                                <option value="Other">{{$lang.pitch_modal_details_other}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">{{$lang.pitch_modal_details_size}}*</label>
                                        <div class="col-sm-6">
                                            <select name="pitch_size" id="pitch_size" class="form-control pull-left">
                                                <option value="5-a-side" selected="">{{$lang.pitch_modal_details_size_side}}</option>
                                                <option value="7-a-side">{{$lang.pitch_modal_details_size_side_one}}</option>
                                                <option value="8-a-side">{{$lang.pitch_modal_details_size_side_two}}</option>
                                                <option value="9-a-side">{{$lang.pitch_modal_details_size_side_three}}</option>
                                                <option value="10-a-side">{{$lang.pitch_modal_details_size_side_four}}</option>
                                                <option value="Handball">{{$lang.pitch_modal_details_size_side_handball}}</option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-12">
                                            <button type="button" id="add_stage" @click="nextStage()"  class="btn btn-primary">{{$lang.pitch_modal_button_next}}</button>
                                        </div> -->
                                    </div>
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
                                                                Stage {{day}}
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                    <input type="text" :name="'stage_start_date'+day" :id="'stage_start_date'+day" value="" :class="[ errors.has('stage_start_date'+day)?'is-danger':'','form-control ls-datepicker datestage'+day] " >
                                                                         <!-- <i v-show="errors.has('stage_start_date'+day)" class="fa fa-warning"></i>
                                                                         <span class="help is-danger" v-show="errors.has('stage_start_date'+day)">{{ errors.first('stage_start_date'+day) }}</span> -->
                                                                    <!-- <input v-model="formValues.name" v-validate="'required|alpha'" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" placeholder="Your name"> -->
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                                                    <div class="align-self-center w-100">
                                                                        <input :name="'stage_start_time'+day" v-validate="'required'" :class="[errors.has('stage_start_time'+day)?'is-danger': '', 'form-control ls-timepicker']"  :id="'stage_start_time'+day"  type="text" >
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
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="d-flex flex-nowrap justify-content-between align-items-center">
                                                                    <div class="align-self-center w-100">
                                                                        <input type="text" :name="'stage_break_start'+day" v-validate="'required'" :class="[errors.has('stage_break_start'+day)?'is-danger': '', 'form-control ls-timepicker']" :id="'stage_break_start'+day" >
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
                                                        <div class="row align-items-center mb-3">
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
                                                                    <div class="align-self-center w-100">
                                                                        <input type="text" :name="'stage_continue_time'+day" v-validate="'required'" :class="[errors.has('stage_continue_time'+day)?'is-danger': '', 'form-control ls-timepicker']"  :id="'stage_continue_time'+day">
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
                                                                <span :id="'stage_capacity_span'+day"  lass="badge badge-pill badge-info">0.00</span>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.pitch_modal_availability_button_close}}</button>
                        <button type="button" class="btn btn-primary" @click="savePitchDetails()">{{$lang.pitch_modal_availability_button_save}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
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
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            // this.$store.dispatch('SetPitches',this.tournamentId);
            let capacity={}
            let sDate = []
            var startDate = new Date(moment(this.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY'))
            var obj ={}

            $('[data-toggle="tooltip"]').tooltip();

            $('.ls-datepicker').datepicker('setStartDate', this.tournamentStartDate);

            $('.ls-datepicker').datepicker('setEndDate', this.tournamentEndDate);
            for(let i=1;i<=this.tournamentDays;i++){
                capacity['day'+i]= '0.00'
                $('.datestage'+i).datepicker('setDate', moment(startDate, 'MM/DD/YYYY').format('DD/MM/YYYY'))
                this.disableDate.push( $('.datestage'+i).val());
                startDate.setDate(new Date(moment(this.tournamentStartDate, 'DD/MM/YYYY').format('MM/DD/YYYY')).getDate() + i)
                obj['date'+i] = $('.datestage'+i).val();
            }
            let disableDate = this.disableDate;
            this.stage_date.push(obj)
            $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
            this.stage_capacity.push(capacity)
            $('#frmPitchAvailable').on("change",'.ls-timepicker',function(){
               // this.stageCapacityCalc(1)
               // console.log($(this)[0].class)
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
                }else if(curId.indexOf('stage_break_start') >= 0) {
                    curTime = $('#stage_break_start'+stage).val()
                }else if(curId.indexOf('stage_continue_time') >= 0) {
                    curTime = $('#stage_continue_time'+stage).val()
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
                        maxTime: '18:00:00'
                    });
                    $('#stage_break_start'+stage).val('')


                    $('#stage_continue_time'+stage).val('')


                    $('#stage_end_time'+stage).val('')
                }
                if(curId.indexOf('stage_break_start') >= 0){

                   $('#stage_continue_time'+stage).timepicker({
                        minTime: newTime,
                        maxTime: '18:00:00'
                    });
                    $('#stage_continue_time'+stage).val('')

                    $('#stage_end_time'+stage).val('')

                }
                if(curId.indexOf('stage_continue_time') >= 0 ){

                    $('#stage_end_time'+stage).timepicker({
                        minTime:  newTime,
                        maxTime: '18:00:00'
                    });
                    $('#stage_end_time'+stage).val('')
                }

                if( $('#stage_start_time'+stage).val() == '' || $('#stage_end_time'+stage).val() == '' || $('#stage_break_start'+stage).val() == '' || $('#stage_continue_time'+stage).val() == ''  ) {
                    $('#stage_capacity_span'+stage).text('0.00 hrs');
                    $('#stage_capacity'+stage).val('0.00');
                }else {
                 var stageTimeStart = new Date(moment($('#stage_start_date'+stage).val(),'DD/MM/YYYY').format('MM/DD/YYYY') + " "+ $('#stage_start_time'+stage).val());
                var stageTimeEnd = new Date(moment($('#stage_start_date'+stage).val(),'DD/MM/YYYY').format('MM/DD/YYYY') + " " + $('#stage_end_time'+stage).val());
                var stageBreakStart = new Date(moment($('#stage_start_date'+stage).val(),'DD/MM/YYYY').format('MM/DD/YYYY') + " " + $('#stage_break_start'+stage).val());
                var stageBreakEnd = new Date(moment($('#stage_start_date'+stage).val(),'DD/MM/YYYY').format('MM/DD/YYYY') + " " + $('#stage_continue_time'+stage).val());

                    var diff1 = (stageBreakStart - stageTimeStart) / 60000; //dividing by seconds and milliseconds
                    var diff2 = (stageTimeEnd - stageBreakEnd) / 60000; //dividing by seconds and milliseconds
                    var diff = diff1 + diff2
                    if(diff > 0){
                      var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
                    var time_val = hours+ '.' +minutes
                    var time = hours+ ':' +minutes +' hrs'
                }else {
                    var time_val = '0.0'
                    var time = '00:00 hrs'
                }
                $('#stage_capacity'+stage).val(time_val);
                $('#stage_capacity_min'+stage).val(diff);
                $('#stage_capacity_span'+stage).text(time);

                }

            })

            // $(".ls-datepicker").on("change", function(e) {
            //         console.log('msg')
            // });
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

             // $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
             // $('.sdate').datepicker('setDatesDisabled', this.disableDate);



                $('.ls-timepicker').timepicker({
                    minTime: '08:00:00',
                    maxTime: '18:00:00'
                });
             this.getAllPitches()
        },
        methods: {
            getAllPitches() {
                this.$store.dispatch('SetPitches',this.tournamentId);
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
                    //  var minutes = time % 60;
                    // var hours = (time - minutes) / 60;
                    // var time_val = hours+ '.' +minutes

                    let pitchData = $("#frmPitchDetail").serialize() +'&' + $("#frmPitchAvailable").serialize() + '&tournamentId='+this.tournamentId+'&stage='+this.tournamentDays+'&pitchCapacity='+time
                            // this.$store.dispatch('AddPitch',pitchData)
                            return axios.post('/api/pitch/create',pitchData).then(response =>  {
                                this.pitchId = response.data.pitchId
                                toastr['success']('Pitch detail has been added successfully', 'Success');
                                $('#addPitchModal').modal('hide')
                                $("#frmPitchDetail")[0].reset();

                            }).catch(error => {
                                if (error.response.status == 401) {
                                    // toastr['error']('Invalid Credentials', 'Error');
                                } else {
                                    //   happened in setting up the request that triggered an Error
                                    console.log('Error', error.message);
                                }
                            });


                }).catch(() => {
                    toastr['error']('Please fill all required fields ', 'Error')
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
                // console.log(this.stageShow+day)

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
                var timeStart = new Date(stage_start_date + stage_start_time);
                var timeEnd = new Date(stage_end_date + stage_end_time);
                if(timeStart && timeEnd) {
                    var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds
                    var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
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
                         // console.log(that.disableDate)
                         $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);
                        //
                        // $('.ls-timepicker').timepicker({ 'setTime': 300})

                        $('#stage_start_time'+stage).timepicker({
                            minTime:  '08:00:00',
                            maxTime: '18:00:00'
                        })
                        $('#stage_break_start'+stage).timepicker({
                            minTime:  '08:00:00',
                            maxTime: '18:00:00'
                        })
                        $('#stage_continue_time'+stage).timepicker({
                            minTime:  '08:00:00',
                            maxTime: '18:00:00'
                        })
                        $('#stage_end_time'+stage).timepicker({
                            minTime:  '08:00:00',
                            maxTime: '18:00:00'
                        })

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
