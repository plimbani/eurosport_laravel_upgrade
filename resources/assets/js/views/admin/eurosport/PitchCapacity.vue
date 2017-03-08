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
                                            <form>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Number  *</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="e.g. '1' or '1a'">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-5 form-control-label">Type  *</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control ls-select2">
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
                                                        <select class="form-control ls-select2 col-sm-4 pull-left">
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
                                                <div class="col-md-3">
                                                    <span>Stage</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <span>Date</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <span>Time</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <span>Capacity</span>
                                                </div>
                                            </div>
                                            <form class="form-inline">
                                                <div class="stage" id="stage1">
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 1 start</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Break start</label>
                                                            <div class="input-group col-md-4">
                                                                &nbsp;
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 1 continued</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 1 end</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                5hr 30m
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <span>X Delete stage</span>
                                                    </div>
                                                </div>
                                                <div class="stage" id="stage2">
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 2 start</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                5hr 30m
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Break start</label>
                                                            <div class="input-group col-md-4">
                                                                &nbsp;
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 2 continued</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 2 end</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                5hr 30m
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <span>X Delete stage</span>
                                                    </div>
                                                </div>
                                                <div class="stage" id="stage3" style="display: none;">
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 3 start</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                5hr 30m
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Break start</label>
                                                            <div class="input-group col-md-4">
                                                                &nbsp;
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 3 continued</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <div class="form-group">
                                                            <label for="nameInput" class="control-label col-md-3">Stage 3 end</label>
                                                            <div class="input-group col-md-4">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" class="form-control ls-datepicker">
                                                            </div>
                                                            <div class="input-group col-md-4">
                                                                <select class="form-control ls-select2">
                                                                    <option value="">7:00</option>
                                                                    <option value="">7:30</option>
                                                                    <option value="">8:00</option>
                                                                    <option value="">8:30 </option>
                                                                    <option value="">9:00</option>
                                                                    <option value="">9:30  </option>
                                                                    <option value="">10:00</option>
                                                                    <option value="">10:30</option>
                                                                    <option value="">Etc...</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                5hr 30m
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padding0">
                                                        <span>X Delete stage</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="button" id="add_stage" class="btn btn-outline-secondary">Add Stage</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary">Save</button>
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
        mounted(){
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
        }
    }
    
</script>
