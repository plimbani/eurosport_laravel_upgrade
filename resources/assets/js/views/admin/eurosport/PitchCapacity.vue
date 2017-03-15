<template> 
	<div class="tab-content">
		<div class="card">
            <div class="card-block">
                <h6 class=""><strong>Pitch Capacity</strong></h6>

                <div class="row">

                    <div class="col-md-1 pitch-capaciry" v-for="pitch in pitches">
                        <p><strong>{{pitch.pitch_number}}</strong></p>
                        <img src="/assets/img/pitch.png">
                        <p>
                            <span><a href="#">Edit</a></span>
                            <span><a href="javascript:void(0)" @click="removePitch(pitch.id)">Remove</a></span>
                        </p>
                    </div>

                </div>
            	<div class="mt-4">
            		<button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add Pitch</button>
            	</div>
            	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="tabs tabs-primary">
                                <div class="modal-header">
                                    <ul class="nav nav-tabs col-md-12" role="tablist">
                                        <li class="nav-item col-md-6 padding0">
                                            <a data-toggle="tab" href="#pitch" role="tab" class="nav-link active">Pitch Details</a>
                                        </li>
                                        <li class="nav-item col-md-6 padding0">
                                            <a data-toggle="tab" href="#availability" role="tab" class="nav-link">Availability</a>
                                        </li>                   
                                    </ul>
                                </div>
                                <div class="modal-body">
                                    <div class="tab-content">
                                        <div id="pitch" role="tabpanel" class="tab-pane active">
                                            <form method="post" name="frmPitchDetail" id="frmPitchDetail">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Number  *</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="pitch_number" value="" class="form-control" placeholder="e.g. '1' or '1a'">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Type  *</label>
                                                    <div class="col-sm-6">
                                                        <select name="pitch_type" class="form-control ls-select2">
                                                            <option value="Grass" selected="">Grass</option>
                                                            <option value="Artificial">Artificial</option>
                                                            <option value="Indoor">Indoor</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Location *</label>
                                                    <div class="col-sm-6">
                                                        <select name="location" class="form-control ls-select2">
                                                            <option value="1" selected="">Location 1</option>
                                                            <option value="2">Location 2</option>
                                                            <option value="3">Location 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Size *</label>
                                                    <div class="col-sm-6">
                                                        <select name="pitch_size" id="pitch_size" class="form-control ls-select2 col-sm-4 pull-left">
                                                            <option value="5-a-side" selected="">5-a-side</option>
                                                            <option value="7-a-side">7-a-side</option>
                                                            <option value="8-a-side">8-a-side</option>
                                                            <option value="9-a-side">9-a-side</option>
                                                            <option value="11-a-side">11-a-side</option>
                                                            <option value="Handball">Handball</option>
                                                        </select>
                                                    </div>
                                                </div>
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

                                            <form method="post" name="frmPitchAvailable" id="frmPitchAvailable" class="form-inline">
                                                <div v-for="day in tournamentDays">
                                                    <div class="stage" :id="'stage'+day" v-if="displayDay(day)">
                                                        <div class="col-md-12 padding0">
                                                            <div class="form-group">
                                                                <label for="nameInput" class="control-label col-md-4">stage {{day}} start</label>
                                                                <div class="input-group col-md-4">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                    <input type="text" :name="'stage_start_date'+day" :id="'stage_start_date'+day" value="" :class="[ errors.has('stage_start_date'+day)?'is-danger':'','form-control ls-datepicker datestage'+day] " >
                                                                         <!-- <i v-show="errors.has('stage_start_date'+day)" class="fa fa-warning"></i>
                                                                         <span class="help is-danger" v-show="errors.has('stage_start_date'+day)">{{ errors.first('stage_start_date'+day) }}</span> -->
                                                                    <!-- <input v-model="formValues.name" v-validate="'required|alpha'" :class="{'is-danger': errors.has('name') }" name="name" type="text" class="form-control" placeholder="Your name"> -->
                                                                </div>
                                                                <div class="input-group col-md-2">
                                                                <input :name="'stage_start_time'+day" :id="'stage_start_time'+day"  type="text" class="form-control ls-timepicker">
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                &nbsp;
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 padding0">
                                                            <div class="form-group">
                                                                <label for="nameInput" class="control-label col-md-4">Break start</label>
                                                                <div class="input-group col-md-4">
                                                                    &nbsp;
                                                                </div>
                                                                <div class="input-group col-md-2">
                                                                 <input type="text" :name="'stage_break_start'+day" :id="'stage_break_start'+day"  class="form-control ls-timepicker">
                                                                   
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                &nbsp;
                                                            </div>
                                                        </div>
                                                    
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">

                                                            <label for="nameInput" class="control-label col-md-4">stage {{day}} continued</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" :name="'stage_continue_date'+day" :id="'stage_continue_date'+day" disabled="disabled" readonly="" :class="['form-control sdate ls-datepicker datestage'+ day]">
                                                            </div>
                                                            <div class="input-group col-md-2">
                                                                <input type="text" :name="'stage_continue_time'+day" :id="'stage_continue_time'+day" class="form-control ls-timepicker ">
                                                                
                                                            </div>
                                                            <div class="col-md-2">
                                                                &nbsp;
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-4">Stage {{day}} end</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" :name="'stage_end_date'+day" :id="'stage_end_date'+day" disabled="disabled" readonly="" :class="['form-control  ls-datepicker datestage'+ day]">
                                                            </div>
                                                            <div class="input-group col-md-2">
                                                                 <input :name="'stage_end_time'+day" :id="'stage_end_time'+day" type="text" class="form-control ls-timepicker">
                                                               
                                                            </div>
                                                            <div class="col-md-1">
                                                                <span :id="'stage_capacity_span'+day" >0.00</span>
                                                                <input type="hidden" :name="'stage_capacity'+day" :id="'stage_capacity'+day" value="0.00">
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-12 padding0">
                                                        <span @click="stageRemove(day)">X Delete stage</span>
                                                    </div>
                                                </div>
                                                   
                                                </div>

                                                   <!--  </div>
                                                </div>
                                                </div> -->
                                                <div class="col-md-12">
                                                    <button type="button" id="add_stage" @click="addStage()" :disabled="removeStage.length==0" class="btn btn-outline-secondary">Add Stage</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" @click="savePitchDetails()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="result col-md-12">
                        <div class="dashbox">
                            <p>
                                <label class="col-md-3"><strong>Total time required:</strong></label>
                                <label class="col-md-5">13 hrs 40 mins</label>
                            </p>
                            <p>
                                <label class="col-md-3"><strong>Total pitch capacity:</strong></label>
                                <label class="col-md-5">12 hrs 10 mins</label>
                            </p>
                            <p>
                                <label class="col-md-3"><strong>Balance:</strong></label>
                                <label class="red col-md-5">- 1 hr 30 mins <a href="">(Help)</a></label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
		</div>	
	</div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                'tournamentId': 1,
                'pitches':'',
                'pitchId' : '',
                'tournamentDays': 3,
                'stage_date':[],
                'tournamentStartDate': '03/01/2017',
                'tournamentEndDate': '03/03/2017',
                'removeStage': [],
                'disableDate': [],
                'stage_capacity' : [],
                'availableDate': []
                }
        },
        computed: {

           
        },	
        mounted(){
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            let capacity={}
            let sDate = []
            var startDate = new Date(this.tournamentStartDate)
            var obj ={}
            
            $('.ls-datepicker').datepicker('setStartDate', this.tournamentStartDate);

            $('.ls-datepicker').datepicker('setEndDate', this.tournamentEndDate);
            for(let i=1;i<=this.tournamentDays;i++){
                capacity['day'+i]= '0.00'
                $('.datestage'+i).datepicker('setDate', startDate)
                this.disableDate.push( $('.datestage'+i).val());
                startDate.setDate(new Date(this.tournamentStartDate).getDate() + i)
                obj['date'+i] = $('.datestage'+i).val();
                
            }
            let disableDate = this.disableDate;
            console.log(disableDate)
            this.stage_date.push(obj)
            $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
            this.stage_capacity.push(capacity)
            $('#frmPitchAvailable').on("change",'.ls-timepicker',function(){
               // this.stageCapacityCalc(1)
               // console.log($(this)[0].class)
               let stage = $(this)[0].id;
               // console.log(stage_id)
               stage = stage.replace('stage_start_time','')
               stage = stage.replace('stage_break_start','')
               stage = stage.replace('stage_continue_time','')
               stage = stage.replace('stage_end_time','')
               if( $('#stage_start_time'+stage).val() == '' || $('#stage_end_time'+stage).val() == '' || $('#stage_break_start'+stage).val() == '' || $('#stage_continue_time'+stage).val() == ''  ) {
                $('#stage_capacity_span'+stage).text('0.00 hrs');

                $('#stage_capacity'+stage).val('0.00');
               }else {
                 var stageTimeStart = new Date($('#stage_start_date'+stage).val() + " "+ $('#stage_start_time'+stage).val());
                var stageTimeEnd = new Date($('#stage_start_date'+stage).val() + " " + $('#stage_end_time'+stage).val());
                var stageBreakStart = new Date($('#stage_start_date'+stage).val() + " " + $('#stage_break_start'+stage).val());
                var stageBreakEnd = new Date($('#stage_start_date'+stage).val() + " " + $('#stage_continue_time'+stage).val());

                    var diff1 = (stageBreakStart - stageTimeStart) / 60000; //dividing by seconds and milliseconds
                    var diff2 = (stageTimeEnd - stageBreakEnd) / 60000; //dividing by seconds and milliseconds
                    var diff = diff1 + diff2

                    var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
                    var time = hours+ ':' +minutes +' hrs'
                $('#stage_capacity'+stage).val(time);
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
                
               console.log(that.disableDate,'tesr')
                var index =  disableDate.indexOf($('#stage_end_date'+stage).val());
                if (index > -1) {
                    // let stage = disableDate[index];
                    disableDate.splice(index, 1);
                    that.disableDate = disableDate
                    $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);

                    // disableDate
                }

                // that.disableDate.push( $('#'+this.id).val());
                
                $('.datestage'+stage).val($('#'+this.id).val())
            });
            
             // $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
             // $('.sdate').datepicker('setDatesDisabled', this.disableDate);

             this.getAllPitches()
        },
        methods: {
            getAllPitches() {

            return axios.get('/api/pitches/'+this.tournamentId).then(response =>  {
                   this.pitches =  response.data.pitches
                
                }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                });
            },
            // stageCapacityCalc(stage) {
            //     var timeStart = new Date($('#stage_start_date'+stage).val() + " "+ $('#stage_start_time'+stage).val());
            //     var timeEnd = new Date($('#stage_start_date'+stage).val() +" " + $('#stage_end_time'+stage).val());
            //     // var timeEnd = new Date("01/01/2007 " + "10:30 PM");
                

            // },
            savePitchDetails () {
                this.$validator.validateAll().then(() => {
                    axios.post("/api/user/create", this.formValues).then((response) => {
                        $("#user_form_modal").modal("hide");
                        this.$data.formValues = this.initialState();
                    });
                }).catch(() => {
                    alert('Hello')
                 });
                // let pitchData = { 
                //     'pitchId' : this.pitchId,
                //     'number': '123',
                //     'type' : 'Grass',
                //     'location' : '1',
                //     'Size' : '5-a-side'
                //     }
                     // let pitchData = new FormData($("#frmPitchDetail")[0]$("#frmPitchAvailable")[0]);
                    let pitchData = $("#frmPitchDetail").serialize() +'&' + $("#frmPitchAvailable").serialize() + '&tournamentId='+this.tournamentId+'&stage='+this.tournamentDays
                    // let pitchData1 = $.merge(pitchData,PitchAvailable,pitchData )
                     // pitchData += new FormData($("#frmPitchAvailable")[0])
                     // con
                     
                return axios.post('/api/pitch/create',pitchData).then(response =>  {
                    toastr['success']('Pitch detail has been added successfully', 'Success');
                }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                });
 
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
                    setTimeout(function() {
                    
                    $('.datestage'+stage).datepicker();
                    $('.datestage'+stage).datepicker('setStartDate', tStartDate)
                    $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                    $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                    $('.datestage'+stage).datepicker('setDatesDisabled', disableDate);
                    if(availableDate.length > 0) {
                         $('.datestage'+stage).datepicker('setDate', availableDate[0])
                        availableDate.splice(0, 1);
                    }
                   
                    $('#stage_start_time'+stage).timepicker()
                    $('#stage_break_start'+stage).timepicker()
                    $('#stage_continue_time'+stage).timepicker()
                    $('#stage_end_time'+stage).timepicker()
                    },1000)
                    
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
                 //    setTimeout( function(that) {
                 //        console.log(this.tournamentStartDate)
                 //     that.setDatepicker(that.tournamentStartDate,that.tournamentEndDate,that.disableDate,stage)
                           
                 // },1000);
                   
                    // function setDatepicker(tStartDate,tEndDate,disableDate){
                    //     $('.datestage3').datepicker()
                    //     var startDate = new Date(tStartDate)
                    //     console.log(stage)
                    //   $('.datestage'+stage).datepicker('setStartDate', tStartDate)
                    //   $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                    //   // $('.datestage'+stage).datepicker('setEndDate', '10/05/2017')
                      
                    //   // console.log(startDate)
                    //   // $('.datestage'+stage).datepicker('setDate', startDate)
                    //   // $('.datestage'+stage).datepicker('setDatesDisabled', disableDate);
                    //   $('#stage_start_time'+stage).timepicker()
                    //   $('#stage_break_start'+stage).timepicker()
                    //   $('#stage_continue_time'+stage).timepicker()
                    //   $('#stage_end_time'+stage).timepicker()

                    // }
                }

            },
            removePitch(pitchId) {
                return axios.post('/api/pitch/delete/'+pitchId).then(response =>  {
                    toastr['success']('Pitch Successfully removed', 'Success');
                    this.getAllPitches()
                    }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                });
            }

        }
    }
    
</script>
