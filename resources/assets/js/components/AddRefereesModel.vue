<template>
<div class="modal fade" id="refreesModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Referee details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">First name *</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Last name *</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Telephone</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Email</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Age categories *</label>
              <div class="col-sm-6">
                <select class="form-control ls-select2">
                      <option value="">Please Select</option>
                      <option value="">U11</option>
                      <option value="">U15</option>
                      <option value="">U19</option>
                  </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5 form-control-label">Availability *</label>
              <div class="col-sm-6">
                <textarea class="form-control" placeholder="e.g. Day 1 all day. Day 2 from 11 onwords"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Save</button>
        </div>
    </div>
  </div>
</div>
</template>
<script type="text/babel">

</script>