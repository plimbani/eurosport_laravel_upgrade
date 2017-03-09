<template> 
	<div class="tab-content">
		<div class="card">
            <div class="card-block">
                <h6 class="mt4"><strong>Age categories</strong></h6>
            	<!-- <div class="competition_list row">
            		<div class="col-md-2">
            			<span>Age category</span>
            		</div>
            		<div class="col-md-2">
            			<span>Competition format</span>
            		</div>
            		<div class="col-md-2">
            			<span>Total matches</span>
            		</div>
            		<div class="col-md-2">
            			<span>Total time</span>
            		</div>
            		<div class="col-md-2">
            			<span>Total time</span>
            		</div>
            		<div class="col-md-2">
            			<span>Edit</span>
            		</div>
            	</div> -->
                <div class="row">
                    <div class="col-md-12">
                        <table class="table add-category-table">
                            <thead>
                                <tr>
                                    <th>Age category</th>
                                    <th>Competition format</th>
                                    <th>Total matches</th>
                                    <th>Total time</th>
                                    <th>Match schedule</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr colspan="6">&nbsp;</tr>
                                <tr>
                                    <td>U11</td>
                                    <td class="table-success">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="exampleRadios" id="exampleRadios1" value="option1"
                                                       checked>
                                                6 teams, RR1, EM
                                            </label>
                                        </div>
                                    </td>
                                    <td class="table-success">20</td>
                                    <td class="table-success">13 hours 40 minutes</td>
                                    <td class="table-success">
                                        <a href="#">Veiw</a>
                                    </td>
                                    <td>
                                        <a href="#">Edit</a> &nbsp;&nbsp;&nbsp;
                                        <a href="#">Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                                                6 teams, RR1, PM/F
                                            </label>
                                        </div>
                                    </td>
                                    <td>18</td>
                                    <td>12 hours 50 minutes</td>
                                    <td>
                                        <a href="">View</a>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            	<div class="row">
                    <div class="col-md-12">
            		     <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add Age Category</button>
                    </div>
            	</div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="add-text">Etc...</p>
                    </div>
                </div>
            	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Age category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Age category *</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" placeholder="e.g. U11, U16-A">
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Game duration RR/PM/EM *</label>
			                            <div class="col-sm-6">
			                            	<span class="col-sm-2 pull-left multi-number padding0">2 <small>X</small></span>
			                                <select class="form-control ls-select2 col-sm-4 pull-left">
			                                	<option value="">10</option>
			                                	<option value="">15</option>
			                                	<option value="">20</option>
			                                	<option value="">Other</option>
			                                </select>
			                                <span class="col-md-2 minutes-div">minutes</span>
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Game duration Final *</label>
			                            <div class="col-sm-6">
			                            	<span class="col-sm-2 pull-left multi-number padding0">2 <small>X</small></span>
			                                <select class="form-control ls-select2 col-sm-4 pull-left">
			                                	<option value="">10</option>
			                                	<option value="">15</option>
			                                	<option value="">20</option>
			                                	<option value="">Other</option>
			                                </select>
			                                <span class="col-md-2 minutes-div">minutes</span>
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Half-time break RR/PM/EM *</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" placeholder="">
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Half-time break Final *</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" placeholder="">
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Match interval RR/PM/EM *</label>
			                            <div class="col-sm-6">
			                                <select class="form-control ls-select2 col-sm-4 pull-left">
			                                	<option value="">5</option>
			                                	<option value="">10</option>
			                                	<option value="">Other</option>
			                                </select>
			                                <span class="col-md-2 minutes-div">minutes</span>
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Match interval Final *</label>
			                            <div class="col-sm-6">
			                                <select class="form-control ls-select2 col-sm-4 pull-left">
			                                	<option value="">5</option>
			                                	<option value="">10</option>
			                                	<option value="">Other</option>
			                                </select>
			                                <span class="col-md-2 minutes-div">minutes</span>
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Number of teams *</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" placeholder="">
			                            </div>
                                	</div>
                                	<div class="form-group row">
                                		<label class="col-sm-5 form-control-label">Minimum matches *</label>
			                            <div class="col-sm-6">
			                                <select class="form-control ls-select2">
			                                	<option value="">Please select</option>
			                                	<option value="">4</option>
			                                	<option value="">5</option>
			                                	<option value="">6</option>
			                                </select>
			                            </div>
                                	</div>
                                </form>
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
}
</script>