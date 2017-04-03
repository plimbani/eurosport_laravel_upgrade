<template>
  <div class="modal fade" id="pitchModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Match Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <div>
              <span>Match number:  U19-RR1-01</span>
              <span>Team 1 Utd (U19-A-1) v Team 2 Rangers (U19-A-2)</span>
              <span>Date: 11.04.2017 (10:00)</span>
              <span>Pitch: 1 (Location 2)</span>
            </div>
            <p class="mt-4 refree_name">
              <strong>Referee:</strong> Paul Jones
              <a href="#">Remove</a>
            </p>
            <form class="mt-4">
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><Strong>Team 1 Utd</Strong></label>
                <div class="col-sm-2">
                  <input type="number" class="form-control" >
                </div>
                <label class="col-sm-4 form-control-label"><Strong>Team 2 Rangers</Strong></label>
                <div class="col-sm-2">
                  <input type="number" class="form-control" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><strong>Status</strong></label>
                <div class="col-sm-8">
                  <select class="form-control ls-select2">
                      <option value="">Full-time</option>
                      <option value="">Penalties</option>
                      <option value="">Walk-over</option>
                      <option value="">Abandoned</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><strong>Winner</strong></label>
                <div class="col-sm-8">
                  <select class="form-control ls-select2">
                      <option value="">Please Select</option>
                      <option value="">Team 1 Utd</option>
                      <option value="">Team 2 Rangers</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><strong>Comments</strong></label>
                <div class="col-sm-8">
                  <textarea class="form-control"></textarea>
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