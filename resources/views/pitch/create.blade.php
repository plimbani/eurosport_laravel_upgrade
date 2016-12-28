@extends('layouts.admin_theme')

@section('content')
  
  <!-- route outlet -->
  <!-- component matched by the route will render here -->
  <div class="page-content">
    <div class="flex-center position-ref full-height">
      <div class="container" id="pitchSet" v-cloak>
        <div class="content">
            <div class="text-center col-md-8 col-md-offset-2">
                <form name="frmPitchDetails" role="form" class="form-horizontal" id="frmPitchDetails">

                    <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#pitch_detail">Pitch</a></li>
                      <li><a data-toggle="tab" href="#avail">Availability</a></li>
                    </ul>
                    
                    <div class="tab-content">
                      <div id="pitch_detail" class="tab-pane fade in active">
                       <h3>Pitch Detail</h3>
                        <div class="form-group row">
                          <label for="location" class="col-md-4">Location</label>
                          <input type="text" class="col-md-3" id="location" name="location" value="{{ $location}}" readonly="">
                        </div>
                        <div class="form-group row">
                          <label for="pitch_name"  class="col-md-4">Pitch name/number:</label>
                          <input type="text" class="col-md-3" id="pitch_name" name="pitch_name">
                        </div>

                        <div class="form-group row">
                          <label for="pitch_type"  class="col-md-4">Pitch type:</label>
                            <select class="col-md-3" id="pitch_type" name="pitch_type">
                              <option value="artificial">Artificial</option>
                              <option value="grass">Grass</option>
                            </select>
                        </div>

                        <div class="form-group row">
                          <label for="time_slot"  class="col-md-4">Time slot:</label>
                            <select class="col-md-3" id="time_slot" name="time_slot">
                              <option value="30" selected="">30</option>
                              <option value="45">45</option>
                            </select>
                        </div>

                       <div class="form-group row">
                          <label for="comment" class="col-md-4">Comment:</label>
                          <textarea class="col-md-3" rows="5" id="comment"></textarea>
                        </div>

                         <button type="submit" class="btn btn-default" id="submitStep1"  @click="PitchDetail()">Next</button>
                      </div>
                       <div id="avail" class="tab-pane fade">
                        <h3>Availability</h3>
                           <table class="table .table-bordered" id="tbl_avail">
                            <thead>
                              <tr>
                               
                              </tr>
                            </thead>
                            <tbody>
                           
                              
                              
                            </tbody>
                          </table>
                      </div>
                    </div>

                   
                </form>
            </div>

         </div>
      </div>
    </div>
   </div> 
@endsection

@section('page-scripts')
   <script src="{{ elixir('js/pitch.js') }}" type="text/javascript"></script>
@endsection