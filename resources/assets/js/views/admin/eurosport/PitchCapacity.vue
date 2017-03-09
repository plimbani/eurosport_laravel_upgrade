<template> 
	<div class="tab-content">
		<div class="card">
			<div class="col-md-12">
                <h6><strong>Pitch Capacity</strong></h6>
            </div>
            <div class="card-block">
            	<div class="row">
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
                                                        <input type="text" name="pitch_number" class="form-control" placeholder="e.g. '1' or '1a'">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Type  *</label>
                                                    <div class="col-sm-6">
                                                        <select name="pitch_type" class="form-control ls-select2">
                                                            <option value="">Grass</option>
                                                            <option value="">Artificial</option>
                                                            <option value="">Indoor</option>
                                                            <option value="">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Location *</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control ls-select2">
                                                            <option value="">Location 1</option>
                                                            <option value="">Location 2</option>
                                                            <option value="">Location 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Size *</label>
                                                    <div class="col-sm-6">
                                                        <select name="pitch_size" class="form-control ls-select2 col-sm-4 pull-left">
                                                            <option value="">5-a-side</option>
                                                            <option value="">7-a-side</option>
                                                            <option value="">8-a-side</option>
                                                            <option value="">9-a-side</option>
                                                            <option value="">11-a-side</option>
                                                            <option value="">Handball</option>
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
                                                                    <input type="text" :name="'stage_'+day+'_start_date'" class="form-control ls-datepicker">
                                                                </div>
                                                                <div class="input-group col-md-2">
                                                                <input :name="'stage_'+day+'_start_time'" type="text" class="form-control ls-timepicker">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 padding0">
                                                            <div class="form-group">
                                                                <label for="nameInput" class="control-label col-md-4">Break start</label>
                                                                <div class="input-group col-md-4">
                                                                    &nbsp;
                                                                </div>
                                                                <div class="input-group col-md-2">
                                                                 <input type="text" :name="'stage_'+day+'_break_start'" class="form-control ls-timepicker">
                                                                   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-4">stage {{day}} continued</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" :name="'stage_'+day+'_continue_date'" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-2">
                                                                <input type="text" :name="'stage_'+day+'continue_time'" class="form-control ls-timepicker">
                                                                
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
                                                                <input type="text" :name="'stage_'+day+'_end_date'" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-2">
                                                                 <input :name="'stage_'+day+'_end_time'" type="text" class="form-control ls-timepicker">
                                                               
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
            </div>
		</div>	
	</div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                'pitchId' : '',
                'tournamentDays': 3,
                'removeStage': []

                 }
        },
        computed: {
           
        },	
        mounted(){
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
            $('.ls-timepicker').timepicker();


            return axios.post('/api/venues').then(response =>  {
                    console.log(response)
                
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
                console.log('msg')
                // let pitchData = { 
                //     'pitchId' : this.pitchId,
                //     'number': '123',
                //     'type' : 'Grass',
                //     'location' : '1',
                //     'Size' : '5-a-side'
                //     }
                     var pitchData = new FormData($("#frmPitchDetail")[0]);
                     // console.log(pitchData);
                return axios.post('/api/pitch/create',pitchData).then(response =>  {
                    console.log(response)
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
                console.log(this.removeStage)
                if($.inArray( day,this.removeStage) != -1 ) {
                    return false
                }else {
                    return true
                }
            },
            addStage () {
                alert('hi');
            }

        }
    }
    
</script>
