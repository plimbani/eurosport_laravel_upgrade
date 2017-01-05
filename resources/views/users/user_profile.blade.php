<div id="admin_modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" v-on:click="clearForm('frmAdminSaveData')"></button>
                <h4 class="modal-title">My Profile</h4>
            </div>
            <div class="modal-body">
                
            <div class="portlet-body form">
           <!-- BEGIN FORM-->
            <form  class="form-horizontal" id="frmAdminSaveData">
                {!! csrf_field() !!}
                <div class="form-body">
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Email address<span class="required" aria-required="true">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" 
                               
                                placeholder="Email address" name="email"> </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">First name<span class="required" aria-required="true">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"  
                            
                            placeholder="Enter first name" name="first_name">
                         </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Last name<span class="required" aria-required="true">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                             placeholder="Enter last name" name="last_name"
                            
                            >
                        </div>
                    </div>
                    
                    <!-- <div class="form-group adminModal">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group adminModal">
                        <label class="col-md-3 control-label paddingrightzero">Confirm password</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" id="password_confirmation">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                      <label class="control-label col-md-3">Account type</label>
                      <div class="col-md-8">
                          <div class="mt-radio-inline" data-error-container="#form_2_membership_error">
                              <label class="mt-radio">
                                  <input type="radio" id="gwt-uid-25" name="admin" /> Staff
                                  <span></span>
                              </label>
                              <label class="mt-radio">
                                  <input type="radio" id="gwt-uid-26" name="admin" /> Administrator
                                  <span></span>
                              </label>
                          </div>
                      </div>
                  </div>
                    
                    <div class="row">
                          <div class="col-md-offset-3 col-md-9">
                              <button type="submit" id="submitAdmin" class="btn green" @click="saveUser()">Submit</button>
                              <!-- <button type="button" class="btn default" id="clearBtn" @click="clearForm('frmAdminSaveData')">Clear</button> -->
                          </div>
                    </div>
                       
                </div>
            </form>
           </div>
            </div>
           
        </div>
    </div>
</div>     