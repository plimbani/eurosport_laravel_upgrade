<template> 
	<div class="tab-content">
		<div class="card">
            <div class="card-block">
                <h6 class="mt-4"><strong>Pitch Capacity</strong></h6>

                <div class="row">
                    <div class="col-md-1 pitch-capaciry">
                        <p><strong>Pitch 1</strong></p>
                        <img src="/assets/img/pitch.png">
                        <p>
                            <span><a href="#">Edit</a></span>
                            <span><a href="#">Remove</a></span>
                        </p>
                    </div>
                    <div class="col-md-1 pitch-capaciry disable-div">
                        <p><strong>Pitch 2</strong></p>
                        <img src="/assets/img/pitch-disable.png">
                        <p>
                            <span><a href="#">Edit</a></span>
                            <span><a href="#">Remove</a></span>
                        </p>
                    </div>
                    <div class="col-md-1 pitch-capaciry disable-div">
                        <p><strong>Pitch 3</strong></p>
                        <img src="/assets/img/pitch-disable.png">
                        <p>
                            <span><a href="#">Edit</a></span>
                            <span><a href="#">Remove</a></span>
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
                                                                    <input type="text" :name="'stage_start_date'+day" :id="'stage_start_date'+day" value="" class="form-control ls-datepicker">
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
                                                                 <input type="text" :name="'stage_break_start'+day"  :id="'stage_break_start'+day"  class="form-control ls-timepicker">
                                                                   
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
                                                                <input type="text" :name="'stage_continue_date'+day" :id="'stage_continue_date'+day" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-2">
                                                                <input type="text" :name="'stage_continue_time'+day" :id="'stage_continue_time'+day" class="form-control ls-timepicker">
                                                                
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
                                                                <input type="text" :name="'stage_end_date'+day" :id="'stage_end_date'+day" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-2">
                                                                 <input :name="'stage_end_time'+day" :id="'stage_end_time'+day" type="text" class="form-control ls-timepicker">
                                                               
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{stage_capacity}}
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
                'pitchId' : '',
                'tournamentDays': 3,
                'removeStage': [],
                'stage_capacity' : '5.30'
                 }
        },
        computed: {
           
        },	
        mounted(){
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
            $('.ls-timepicker').timepicker();


            return axios.post('/api/venues').then(response =>  {
                    
                
                }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                });
        },
        methods: {
            savePitchDetails () {
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
            addStage () {
                let removeStageArr = this.removeStage
                let stageno = Math.min.apply( Math, removeStageArr )
                var index = removeStageArr.indexOf(Math.min.apply( Math, removeStageArr ));
                if (index > -1) {
                    let stage = removeStageArr[index];
                    

                   removeStageArr.splice(index, 1);
                   this.removeStage = removeStageArr
                   setTimeout(setDatepicker,500);
                   
                    function setDatepicker(){
                      $('#stage_start_date'+stage).datepicker('setStartDate', '03/01/2017')
                      $('#stage_start_date'+stage).datepicker('setEndDate', '03/01/2017')
                      $('#stage_continue_date'+stage).datepicker('setStartDate', '03/03/2017')
                      $('#stage_continue_date'+stage).datepicker('setEndDate', '03/03/2017')
                      $('#stage_end_date'+stage).datepicker('setStartDate', '08/08/2017')
                      $('#stage_end_date'+stage).datepicker('setEndDate', '08/08/2017')

                      $('#stage_start_time'+stage).timepicker()
                      $('#stage_break_start'+stage).timepicker()
                      $('#stage_continue_time'+stage).timepicker()
                      $('#stage_end_time'+stage).timepicker()
                    }
                   

                }
                
                
                
            }

        }
    }
    
</script>
