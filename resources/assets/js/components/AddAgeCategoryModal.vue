<template>
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
                                <label class="col-sm-5 form-control-label">Select templates *</label>
                                <div class="col-sm-6">
                                    <select class="form-control ls-select2">
                                        <option value="">Select templates</option>
                                    </select>
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
                                    <input type="number" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label">Half-time break Final *</label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control" placeholder="">
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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
</template>
