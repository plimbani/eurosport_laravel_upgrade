@extends('layouts.admin_theme')
@section('page-title', 'Tournament')
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
              Tournament Details
            </div>
          </div>
        <div class="portlet-body" id="tournamentadd" v-cloak>
            <form role="form" class="form-horizontal" id="frmTournamentForm">
                 <div class="form-body">
                      <div class="form-group">
                          <label class="col-md-2 control-label">Tournament Name
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="name" 
                              id="name" 
                              v-bind="tournamentdata.name"  class="form-control" 
                              placeholder="Enter the name of your tournament">
                          </div>

                          <label class="col-md-2 control-label">Email<span class="required">*
                          </span></label>
                          <div class="col-md-4">
                                <input type="text"   name="email_address" 
                                id="email_address"   v-bind="tournamentdata.email_address" 
                                class="form-control" placeholder="Enter Email Address">
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-2 control-label">Venue
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="venue" 
                              id="venue" 
                              v-bind="tournamentdata.venue"  class="form-control" placeholder="Enter venue">
                          </div>

                          <label class="col-md-2 control-label">Website<span class="required">*</span></label>
                          <div class="col-md-4">
                                <input type="text" name="website" id="website" v-bind="tournamentdata.website" class="form-control" placeholder="Enter venue">
                         </div>
                      </div>

                       <div class="form-group">
                          <label class="col-md-2 control-label">Address1
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="address1" 
                              id="address1" 
                              v-bind="tournamentdata.address1"  class="form-control" placeholder="Enter Address1">
                          </div>

                          <label class="col-md-2 control-label">Facebook<span class="required">*</span></label>
                          <div class="col-md-4">
                                <input type="text" name="facebook" id="facebook" v-bind="tournamentdata.facebook" class="form-control" placeholder="Enter facebook">
                         </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">Address2
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="address2" 
                              id="address2" 
                              v-bind="tournamentdata.address2"  class="form-control" placeholder="Enter Address2">
                          </div>

                          <label class="col-md-2 control-label">Twitter<span class="required">*</span></label>
                          <div class="col-md-4">
                                <input type="text" name="twitter" id="twitter" v-bind="tournamentdata.twitter" class="form-control" placeholder="Enter Twitter">
                         </div>
                      </div>

                       <div class="form-group">
                          <label class="col-md-2 control-label">Address3
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="address3" 
                              id="address3" 
                              v-bind="tournamentdata.address3"  class="form-control" placeholder="Enter Address3">
                          </div>

                          <label class="col-md-2 control-label">Logo<span class="required">*</span></label>
                           <div class="col-md-4">
                                    <span class="btn default btn-file">
                                    <span class="fileinput-new">
                                    Select Logo </span>
                                    <!--<span class="fileinput-exists">
                                    Change </span>-->
                                    <input type="file" name="file" data-imgW='172' data-imgH='111'
                                     id="file_img">
                                    </span>
                                </div>

                         
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">Town/City
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text"
                              name="start_date" 
                              id="start_date" 
                              v-bind="tournamentdata.address2"  class="form-control" 
                              placeholder="Enter Start date">
                          </div>

                          <label class="col-md-2 control-label">Start Date<span class="required">*
                      </span>
                          </label>
                          <div class="col-md-4">
                           <div data-date-format="dd-mm-yyyy" class="input-group date date-picker ">
                                  <input type="text" readonly="" class="form-control" name="vista_ordered" id="vista_ordered">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn default">
                                          <i class="fa fa-calendar"></i>
                                      </button>
                                  </span>
                              </div>
                              </div>

                      </div>
                      <div class="form-group">
                          <label class="col-md-2 control-label">PostCode
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text"
                              name="postcode" 
                              id="postcode" 
                              v-bind="tournamentdata.postcode"  class="form-control" 
                              placeholder="Enter Post Code">
                          </div>

                          <label class="col-md-2 control-label">Tournament Days<span class="required">*
                      </span>
                          </label>
                          <div class="col-md-4">
                           <select class="form-control select2-allow-clear" 
                           name="tournament_days" id="tournament_days" data-placeholder="Select Tournament Days">
                                      <option value="1" selected>1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                  </select>
                              </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">State
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text"
                              name="state" 
                              id="state" 
                              v-bind="tournamentdata.state"  class="form-control" 
                              placeholder="Enter State">
                          </div>

                          <label class="col-md-2 control-label">Games Per Day<span class="required">*
                      </span>
                          </label>
                          <div class="col-md-4">
                           <select class="form-control select2-allow-clear" name="games_days" id="games_days" data-placeholder="Select Games Per Day">
                                      <option value="1" selected>1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                  </select>
                              </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">Country
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text"
                              name="country" 
                              id="country" 
                              v-bind="tournamentdata.country"  class="form-control" 
                              placeholder="Enter country">
                          </div>

                          <label class="col-md-2 control-label">Start Time<span class="required">*
                      </span>
                          </label>
                          <div class="col-md-4">
                           <select class="form-control select2-allow-clear" name="sport" id="sport" data-placeholder="Select Tournament Days">
                                      <option value="09:00" selected>9</option>
                                      <option value="10:00">10</option>
                                      <option value="11:00">11</option>
                                      <option value="12:00">12</option>
                                  </select>
                              </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">Telephone
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text"
                              name="contact_no" 
                              id="contact_no" 
                              v-bind="tournamentdata.contact_no"  class="form-control" 
                              placeholder="Enter Telephone">
                          </div>

                          <label class="col-md-2 control-label">End Time<span class="required">*
                      </span>
                          </label>
                          <div class="col-md-4">
                           <select class="form-control select2-allow-clear" name="end_time" id="end_time" data-placeholder="Select Tournament Days">
                                      <option value="09:00" selected>9</option>
                                      <option value="10:00">10</option>
                                      <option value="11:00">11</option>
                                      <option value="12:00">12</option>
                                  </select>
                              </div>
                      </div>
                      </div> <!-- end of form-body class> -->
                <div class="form-actions top">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                    <button type="button" id="submitTemplate" class="btn green margin-right-5" 
                                    v-on:click="saveTournamentData()">
                                    Save</button>                               
                                <button type="button" class="btn grey" @click="goBack()">Cancel</button>
                            </div>
                        </div>
                    </div>
                  </div>
            </form>
        </div>
    </div>
@endsection

@section('page-scripts')
  <script src="{{ asset('js/tournament.js') }}"> </script>
@endsection