<template> 
<div class="modal fade" id="addPitchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
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
                                                    <input type="text" v-validate="'required'" :class="{'is-danger': errors.has('pitch_number') }" name="pitch_number"   class="form-control" placeholder="e.g. '1' or '1a'">
                                                        <i v-show="errors.has('pitch_number')" class="fa fa-warning"></i>
                                <span class="help is-danger" v-show="errors.has('pitch_number')">{{ errors.first('pitch_number') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 form-control-label">Type  *</label>
                                                <div class="col-sm-6">
                                                    <select name="pitch_type" id="pitch_type" class="form-control ls-select2">
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
                                                    <select name="location" id="location" class="form-control" >
                                                        <option :value="venue.id" v-for="(venue,key) in venues">{{venue.name}}</option>
                                                        
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
                                                <div class="col-md-12">
                                                    <button type="button" id="add_stage" @click="nextStage()"  class="btn btn-primary">Next</button>
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
                                                            <input :name="'stage_start_time'+day" v-validate="'required'" :class="[errors.has('stage_start_time'+day)?'is-danger': '', 'form-control ls-timepicker']"  :id="'stage_start_time'+day"  type="text" >
                                                                <i v-show="errors.has('stage_start_time'+day)" class="fa fa-warning"></i>
                                                                <span class="help is-danger" v-show="errors.has('stage_start_time'+day)">"Start time is required"</span>
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
                                                             <input type="text" :name="'stage_break_start'+day" v-validate="'required'" :class="[errors.has('stage_break_start'+day)?'is-danger': '', 'form-control ls-timepicker']" :id="'stage_break_start'+day" >
                                                                <i v-show="errors.has('stage_break_start'+day)" class="fa fa-warning"></i>
                                                                <span class="help is-danger" v-show="errors.has('stage_break_start'+day)">Break start time is required</span>
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
                                                                <input type="text" :name="'stage_continue_time'+day" v-validate="'required'" :class="[errors.has('stage_continue_time'+day)?'is-danger': '', 'form-control ls-timepicker']"  :id="'stage_continue_time'+day">
                                                                <br>
                                                                    <i v-show="errors.has('stage_continue_time'+day)" class="fa fa-warning"></i>
                                                                    <span class="help is-danger" v-show="errors.has('stage_continue_time'+day)">Continue time is required</span>

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

                                                                 <input :name="'stage_end_time'+day" :id="'stage_end_time'+day" type="text"  v-validate="'required'" :class="[errors.has('stage_end_time'+day)?'is-danger': '', 'form-control ls-timepicker']">
                                                                 <br>
                                                                <i v-show="errors.has('stage_end_time'+day)" class="fa fa-warning"></i>
                                                                    <span class="help is-danger" v-show="errors.has('stage_end_time'+day)">Stage end time is required</span>

                                                            </div>
                                                            <div class="col-md-1">
                                                                <span :id="'stage_capacity_span'+day" >0.00</span>
                                                                <input type="hidden" :name="'stage_capacity'+day" :id="'stage_capacity'+day" value="0.00">

                                                                <input type="hidden" class="stage_capacity_all" :name="'stage_capacity_min'+day" :id="'stage_capacity_min'+day" value="0">
                                                                
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <span @click="stageRemove(day)">X Delete stage</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" id="add_stage" @click="addStage()" :disabled="removeStage.length==0" class="btn btn-primary">Add Stage</button>
                                            </div>

                                        </form>
                                         <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" @click="savePitchDetails()">Save</button>
                                        </div>
                                    </div>
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
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'tournamentDays': this.$store.state.Tournament.tournamentDay,
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
                    var time_val = hours+ '.' +minutes
                    var time = hours+ ':' +minutes +' hrs'
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
                    $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);

                    // disableDate
                }
                that.disableDate.push( $('#'+this.id).val());
                
                $('.datestage'+stage).val($('#'+this.id).val())
                }
                
            });
            
             // $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
             // $('.sdate').datepicker('setDatesDisabled', this.disableDate);

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
                     var minutes = time % 60;
                    var hours = (time - minutes) / 60;
                    var time_val = hours+ '.' +minutes
                   
                    let pitchData = $("#frmPitchDetail").serialize() +'&' + $("#frmPitchAvailable").serialize() + '&tournamentId='+this.tournamentId+'&stage='+this.tournamentDays+'&pitchCapacity='+time_val
                            // this.$store.dispatch('AddPitch',pitchData)
                            return axios.post('/api/pitch/create',pitchData).then(response =>  {
                                this.pitchId = response.data.pitchId
                                toastr['success']('Pitch detail has been added successfully', 'Success');
                                $('#addPitchModal').modal('hide')
                                $("#frmPitchDetail")[0].reset();

                            }).catch(error => {
                                if (error.response.status == 401) {
                                    toastr['error']('Invalid Credentials', 'Error');
                                } else {
                                    //   happened in setting up the request that triggered an Error
                                    console.log('Error', error.message);
                                }
                            });
                        
                       
                }).catch(() => {
                    // toastr['error']('Invalid Credentials', 'Error')
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
                            
                        
                        
                        $('#stage_start_time'+stage).timepicker()
                        $('#stage_break_start'+stage).timepicker()
                        $('#stage_continue_time'+stage).timepicker()
                        $('#stage_end_time'+stage).timepicker()
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
