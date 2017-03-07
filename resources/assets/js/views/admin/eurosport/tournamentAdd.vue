<template> 
	<div class="tab-content" id="tournament_details">
		<div>
			<div class="card">
                <div class="col-md-12">
                    <h6><strong>Tournament information</strong></h6>
                </div>
                <div class="card-block">
                    <form>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Tournament name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter the name of your tournament">
                            </div>
                        </div>
                        <div class="form-group row">
                        	<label class="col-sm-2 form-control-label">Tournament start date *</label>

                            <div class="col-sm-4">
                                <div class="input-group">
		                            <span class="input-group-addon">
		                                <i class="fa fa-calendar"></i>
		                            </span>
		                            <input type="text" class="form-control ls-datepicker" value="09:30">
		                        </div>
                            </div>
                            <label class="col-sm-2 form-control-label">Tournament end date *</label>

                            <div class="col-sm-4">
                                <div class="input-group">
		                            <span class="input-group-addon">
		                                <i class="fa fa-calendar"></i>
		                            </span>
		                            <input type="text" class="form-control ls-datepicker" value="09:30">
		                        </div>
                            </div>
                        </div>
                        <div class="panel-group optional_details" id="accordion" role="tablist" aria-multiselectable="true">
                        	<div class="panel panel-default">
                        		<div class="panel-heading" role="tab" id="headingOne">
		                            <a class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
		                               aria-expanded="true" aria-controls="collapseOne">
		                                <i class="fa fa-plus"></i> Show Optional Details
		                            </a>
		                        </div>
		                        <div id="collapseOne" class="panel-collapse collapse panel-content in" role="tabpanel"
		                            aria-labelledby="headingOne">
		                            <div class="form-inline">
		                            	<div class="form-group col-md-12 padding0">
				                            <div class="col-md-6 padding0">
				                            	<div class="form-group row">
					                                <label class="col-md-4 control-label">Website</label>
					                                <input type="text" class="col-md-7 form-control" placeholder="">
					                            </div>
					                            <div class="form-group row">
					                                <label class="col-md-4 control-label">Facebook</label>
					                                <input type="text" class="col-md-7 form-control" placeholder="">
					                            </div>
					                            <div class="form-group row">
					                                <label class="col-md-4 control-label">Twitter</label>
					                                <input type="text" class="col-md-7 form-control" placeholder="">
					                            </div>
				                            </div>
				                            <div class="col-md-6 padding0">
				                            	<div class="form-group row">
					                                <label class="col-md-4 control-label">Tournament logo</label>
					                                <input type="text" class="col-md-7 form-control" placeholder="">
					                            </div>
					                            <div class="form-group row">
					                                <label class="col-md-4 control-label">Sponsor banner</label>
					                                <input type="text" class="col-md-7 form-control" placeholder="">
					                            </div>
				                            </div>
				                        </div>
		                            </div>
		                        </div>
                        	</div>
                        </div>
                        <div class="">
	                        <h6><strong>Euro-Sportring</strong></h6>
	                    </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">First name</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Last name</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Telephone</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="">
	                        <h6><strong>Location</strong></h6>
	                    </div>
	                    <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Venue</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Address</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Town / city</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Postcode</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">State</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Country</label>

                            <div class="col-sm-4">
                                <div class="form-group">
		                            <select class="form-control ls-select2">
			                            <option value="">Please select</option>
			                            <option value="">Andorra</option>
			                            <option value="">Belgium</option>
			                            <option value="">France </option>
			                            <option value="">Germany</option>
			                            <option value="">Italy	</option>
			                            <option value="">Spain</option>
			                            <option value="">United Kingdom</option>
			                            <option value="">Etc...</option>
			                        </select>
			                    </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Organiser</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        <!-- <button class="btn btn-success">Save</button> -->
                    </form>
                </div>
            </div>
            <!-- <div class="row">
            	<div class="col-md-12">
            		<div class="pull-left">
            			<button class="btn btn-outline-secondary"><i class="fa fa-angle-double-left" aria-hidden="true"></i>  Home</button>
            		</div>
            		<div class="pull-right">
            			<button class="btn btn-outline-secondary"><i class="fa fa-angle-double-right" aria-hidden="true"></i>  Next</button>
            		</div>
            	</div>
            </div> -->
		</div>		
	</div>
</template>

<script type="text/babel">
export default {

    mounted(){
        Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles'])
    }

}
</script>