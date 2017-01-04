<div id="change_password_modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="clearForm('frmChangePassword')"></button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
                
            <div class="portlet-body form">
           <!-- BEGIN FORM-->
            <form  class="form-horizontal" id="frmChangePassword">
                {!! csrf_field() !!}
                <div class="form-body">
                    
                    <div class="form-group adminModal">
                        <label class="col-md-3 control-label paddingleftzero">Current password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" placeholder="Current password" name="password" id="currpassword">
                        </div>
                    </div>

                    <div class="form-group adminModal">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" placeholder="Password" name="new_password" id="password">
                        </div>
                    </div>
                    
                    <div class="form-group adminModal">
                        <label class="col-md-3 control-label paddingleftzero">Confirm password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" id="password_confirmation">
                        </div>
                    </div>

                    <div class="row">
                          <div class="col-md-offset-3 col-md-9">
                              <button type="submit" id="submitAdmin" class="btn green" v-on:click="changePassword()">Submit</button>
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