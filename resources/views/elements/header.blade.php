<div class="v-slot" style="margin-top: -55px;" id="page-header" v-cloak>
   <div class="v-horizontallayout v-layout v-horizontal v-widget v-has-width" style="width: 100%; height: 55px;">
      <div class="v-expand" style="padding-left: 677px;">
         <div class="v-slot v-align-middle" style="margin-left: -677px;">
            <div class="v-link v-widget"><a href="http://myclubbetting.com/" target="_blank"><span>MyClubBetting.com</span></a></div>
         </div>
         <div class="v-spacing"></div>
         <div class="v-slot v-slot-h2 v-align-center v-align-middle">
            <div class="v-label v-widget v-has-width h2 v-label-h2" style="width: 300px;">User management</div>
         </div>
         <div class="v-spacing"></div>
         <div class="v-slot v-slot-link v-align-center v-align-middle">
            <div tabindex="0" role="button" class="v-button v-widget link v-button-link" @click="openUpdateAdminModal({{ Auth::user()->id }})">
               <span class="v-button-wrap">
                  <span class="v-button-caption">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} (Affiliate admin)</span>
               </span>
            </div>
         </div>
         <div class="v-spacing"></div>
         <div class="v-slot v-align-right v-align-middle" style="width: 98.4%;">
            <div class="v-horizontallayout v-layout v-horizontal v-widget">
               <div class="v-slot">
                  <div role="combobox" class="v-filterselect v-widget v-has-width" style="width: 15em;">
                     <!-- <input type="text" class="v-filterselect-input" tabindex="0" dir="" style="width: 100%;">
                     <div class="v-filterselect-button" aria-hidden="true" role="button"></div> -->
                      <?php
                      $columnSizes = [
                        'md' => [3, 8],
                      ]; ?>
                  

                     <div class="form-group">
                        <div class="col-md-12">
                           <select class="form-control v-filterselect-input" id="affiliates" name="affiliates" @change="changeAffiliate()">
                              @foreach($user_affiliates as $key=>$val)
                                 <option value="{{$key}}">{{$val}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>

                  </div>
               </div>

               <div class="v-spacing"></div>
               <div class="v-slot v-slot-primary">
                  <div tabindex="0" role="button" class="v-button v-widget primary v-button-primary"><span class="v-button-wrap"><span class="v-icon FontAwesome"></span><span class="v-button-caption">Dashboard</span></span></div>
               </div>
               <div class="v-spacing"></div>
               <div class="v-slot v-slot-primary">
                  <div tabindex="0" role="button" class="v-button v-widget primary v-button-primary" @click="gotoUsers()">
                     <span class="v-button-wrap"><span class="v-icon FontAwesome"></span>
                     <span class="v-button-caption">Manage Users</span></span>
                  </div>
               </div>
               <div class="v-spacing"></div>
               <div class="v-slot v-slot-primary">
                  <div tabindex="0" role="button" class="v-button v-widget primary v-button-primary" id="logout">

                  <span class="v-button-wrap">

                     <span class="v-icon FontAwesome"></span>
                     <span class="v-button-caption">Logout</span>
                     
                  </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>