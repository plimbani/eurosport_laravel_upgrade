@extends('layouts.admin_content')
@section('page-title', 'Tournament')
@section('page-css')
  <!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{ asset('admin_theme/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_theme/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
 @endsection

@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption">


    </div>
  </div>

  <div class="portlet-body" id="tournamentadd" v-cloak>
<!-- Add Page Wise css and Js File -->

<!--<div class="v-slot" style="height: 100%;" id="userslist"  v-cloak>
</div>-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
  <div class="row">
    <div class="col-md-12">
      <form action="#" name="frmTournamentSaveData" id="frmTournamentSaveData"
       enctype="multipart/form-data" class="form-horizontal">
        <div class="portlet box green" id="collapse_1">
          <div class="portlet-title">
              <div class="caption">
                  Tournament Details </div>
              <div class="tools">
                  <a href="javascript:;" class="custom-bar collapse" id="customCollapse1"> </a>
                  <a href="javascript:;" class="reload" @click="reloadData('collapse_1')"> </a>
              </div>
          </div>
          <div class="portlet-body form">
              <!-- BEGIN FORM-->
             
                  <div class="form-body">
                      <div class="form-group">
                          <label class="col-md-2 control-label">Tournament Name
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="name" 
                              id="name" 
                              value="@{{clubdata.name}}"  class="form-control" 
                              placeholder="Enter the name of your tournament">
                          </div>

                          <label class="col-md-2 control-label">Email<span class="required">*
                          </span></label>
                          <div class="col-md-4">
                                <input type="text"   name="email_address" 
                                id="email_address"   value="@{{clubdata.email_address}}" 
                                class="form-control" placeholder="Enter Email Address">
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-2 control-label">Venue
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="venue" 
                              id="venue" 
                              value="@{{clubdata.venue}}"  class="form-control" placeholder="Enter venue">
                          </div>

                          <label class="col-md-2 control-label">Website<span class="required">*</span></label>
                          <div class="col-md-4">
                                <input type="text" name="website" id="website" value="@{{clubdata.website}}" class="form-control" placeholder="Enter venue">
                         </div>
                      </div>

                       <div class="form-group">
                          <label class="col-md-2 control-label">Address1
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="address1" 
                              id="address1" 
                              value="@{{clubdata.address1}}"  class="form-control" placeholder="Enter Address1">
                          </div>

                          <label class="col-md-2 control-label">Facebook<span class="required">*</span></label>
                          <div class="col-md-4">
                                <input type="text" name="facebook" id="facebook" value="@{{clubdata.facebook}}" class="form-control" placeholder="Enter facebook">
                         </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">Address2
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="address2" 
                              id="address2" 
                              value="@{{clubdata.address2}}"  class="form-control" placeholder="Enter Address2">
                          </div>

                          <label class="col-md-2 control-label">Twitter<span class="required">*</span></label>
                          <div class="col-md-4">
                                <input type="text" name="twitter" id="twitter" value="@{{clubdata.twitter}}" class="form-control" placeholder="Enter Twitter">
                         </div>
                      </div>

                       <div class="form-group">
                          <label class="col-md-2 control-label">Address3
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text" name="address3" 
                              id="address3" 
                              value="@{{clubdata.address3}}"  class="form-control" placeholder="Enter Address3">
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
                                    <!--<a href="javascript:;" class="btn red fileinput-exists removeImage  margin-left-5" data-dismiss="fileinput">
                                    Remove </a>-->
                                </div>

                          <!--<div class="col-md-4">
                                <input type="text" name="twitter" id="twitter" value="@{{clubdata.twitter}}" class="form-control" placeholder="Enter Twitter">
                         </div>-->
                      </div>

                      <div class="form-group">
                          <label class="col-md-2 control-label">Town/City
                          <span class="required">*</span></label>
                          <div class="col-md-4">
                              <input type="text"
                              name="start_date" 
                              id="start_date" 
                              value="@{{clubdata.address2}}"  class="form-control" 
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
                              value="@{{clubdata.postcode}}"  class="form-control" 
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
                              value="@{{clubdata.state}}"  class="form-control" 
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
                              value="@{{clubdata.country}}"  class="form-control" 
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
                              value="@{{clubdata.contact_no}}"  class="form-control" 
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

{{--

                      <div class="form-group">
                          <label class="col-md-3 control-label">Email<span class="required">*</span></label>
                          <div class="col-md-7">
                              <input type="text" name="contact_email" id="contact_email" value="@{{clubdata.contact_email}}" class="form-control" placeholder="Enter email">

                          </div>
                      </div>
                       <div class="form-group">
                          <label class="col-md-3 control-label">Phone<span class="required">*</span></label>
                          <div class="col-md-7">
                                <input type="text" name="phone" id="contact_phone" value="@{{clubdata.contact_phone}}" class="form-control" placeholder="Enter phone">
                         </div>
                      </div>
                       <div class="form-group">
                          <label class="col-md-3 control-label">Your personal address</label>
                          <div class="col-md-7">

                            <div class="input-group">
                              <input type="text" name="address" id="address" class="form-control" placeholder="Start typing your postcode or address"  @keyup="findAddress">
                              <span class="input-group-addon">
                                  <a ><i class="fa fa-search"></i></a>
                              </span>
                            </div>

                              <div class="input-group select2-bootstrap-append hide addressdrp">
                                <select class="form-control" id="addressdrp">
                                  <option value=""></option>
                                </select>
                              </div>

                              <!-- <div class="input-group select2-bootstrap-append">
                                <select class="form-control" id="address" name="address">
                                </select>
                              </div>   -->

                              <!-- <div class="input-group input-group-sm select2-bootstrap-prepend">
                                  <select id="address" class="form-control" data-placeholder="Find by Postcode">
                                      <option value="2126244" selected="selected">Find by Postcode</option>
                                  </select>
                              </div> -->

                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Address line 1</label>
                          <div class="col-md-7">
                                <input type="text" name="p_address1" id="p_address1" value="@{{clubdata.contact_address_line1}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Address line 2</label>
                          <div class="col-md-7">
                                <input type="text" name="p_address2" id="p_address2" value="@{{clubdata.contact_address_line2}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Address line 3</label>
                          <div class="col-md-7">
                                <input type="text" name="p_address3" id="p_address3" value="@{{clubdata.contact_address_line3}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Town</label>
                          <div class="col-md-7">
                                <input type="text" name="p_town" id="p_town" value="@{{clubdata.contact_town}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">County</label>
                          <div class="col-md-7">
                                <input type="text" name="p_county" id="p_county" value="@{{clubdata.contact_county}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Postcode</label>
                          <div class="col-md-7">
                                <input type="text" name="p_postcode" id="p_postcode" value="@{{clubdata.contact_postcode}}" class="form-control uppercase" >
                         </div>
                      </div>


                  </div>

              <!-- END FORM-->
          </div>
        </div>
        <div class="portlet box green" id="collapse_2">
          <div class="portlet-title">
              <div class="caption">
                  Club Information </div>
              <div class="tools">
                  <a href="javascript:;" class="custom-bar expand" id="customCollapse2"> </a>
                  <a href="javascript:;" class="reload" @click="reloadData('collapse_2')"> </a>
              </div>
          </div>
          <div class="portlet-body form" style="display: none;">
              <!-- BEGIN FORM-->


                  <div class="form-body">
                      <div class="form-group">
                          <label class="col-md-3 control-label">Club name<span class="required">*</span></label>
                          <div class="col-md-7">
                              <input type="text" name="club_name" id="club_name" value="@{{clubdata.club_name}}" @change="setClubName()" class="form-control" placeholder="Enter club/team name">

                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Site type<span class="required">*</span></label>
                          <div class="col-md-7">

                            <!-- <div class="input-group select2-bootstrap-append"> -->
                                <select class="form-control select2-allow-clear slctclass" id="type" name="type" data-placeholder="Select type">
                                    <option value="" selected></option>
                                    <option value="club">Club</option>
                                    <option value="pub">Pub</option>
                                    <option value="charity">Charity</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="0">All</option>
                                </select>
                            <!-- </div> -->
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Upload</label>
                          <!-- <div class="col-md-3">
                              <input type="file" name="file" class="form-control" >
                          </div>
                          <div class="col-md-4 club-image">
                              <img src="@{{clubdata.thumb}}" alt="Club logo">
                          </div> -->
                          <div class="col-md-9">
                            <div class="fileinput fileinput-new" data-provides="fileinput" id="fileinput2">
                                <div style="width: 200px; height: 150px;" class="fileinput-new thumbnail">
                                    <img alt="" class="fileImage" v-bind:src="clubdata.thumb ? clubdata.thumb : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image'">
                                    <input type="hidden" name="upload_image" id="upload_image" value="@{{clubdata.club_name}}">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px; background-color:#eee;"></div>
                                <div>
                                    <span class="btn default btn-file">
                                    <span class="fileinput-new">
                                    Select image </span>
                                    <span class="fileinput-exists">
                                    Change </span>
                                    <input type="file" name="file" data-imgW='172' data-imgH='111' id="file_img">
                                    </span>
                                    <a href="javascript:;" class="btn red fileinput-exists removeImage  margin-left-5" data-dismiss="fileinput">
                                    Remove </a>
                                </div>
                            </div>
                          </div>

                      </div>


                      <div class="form-group">
                          <label class="col-md-3 control-label">Sport<span class="required">*</span></label>
                          <div class="col-md-7">
                              <!-- <div class="input-group select2-bootstrap-append"> -->
                                  <select class="form-control select2-allow-clear" name="sport" id="sport" data-placeholder="Select sport">
                                      <option value="" selected></option>
                                      <option value="Football">Football</option>
                                      <option value="Rugby League">Rugby League</option>
                                      <option value="Rugby Union">Rugby Union</option>
                                      <option value="Cricket">Cricket</option>
                                      <option value="Golf">Golf</option>
                                      <option value="Tennis">Tennis</option>
                                      <option value="Basketball">Basketball</option>
                                      <option value="Hockey">Hockey</option>
                                      <option value="Darts">Darts</option>
                                      <option value="Other">Other</option>
                                  </select>
                              <!-- </div>                             -->
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Enter your league</label>
                          <div class="col-md-7">
                              <input type="text" name="league_name" id="league_name" value="@{{clubdata.league}}" class="form-control" >

                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Proficiency<span class="required">*</span></label>
                          <div class="col-md-7">
                              <!-- <div class="input-group select2-bootstrap-append"> -->
                                  <select class="form-control select2-allow-clear" name="proficiency" id="proficiency" data-placeholder="Select proficiency">
                                      <option value="" selected></option>
                                      <option value="AMATEUR">AMATEUR</option>
                                      <option value="SEMI-PROFESSIONAL">SEMI-PROFESSIONAL</option>
                                      <option value="PROFESSIONAL">PROFESSIONAL</option>

                                  </select>
                              <!-- </div>                             -->
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Website</label>
                          <div class="col-md-7">
                              <input type="text" name="website" id="website" value="@{{clubdata.website}}" class="form-control" >

                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Website type</label>
                          <div class="col-md-7">
                              <!-- <div class="input-group select2-bootstrap-append"> -->
                                  <select class="form-control select2-allow-clear" id="website_type" name="website_type" data-placeholder="Select website type">
                                      <option value="" selected></option>
                                      <option value="Club website">Club website</option>
                                      <option value="Pitchero">Pitchero</option>
                                      <option value="Other">Other</option>
                                  </select>
                              <!-- </div>     -->

                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Delivery address</label>
                          <div class="col-md-7">
                            <div class="input-group">
                              <input type="text" name="delivery_address" id="delivery_address" class="form-control" placeholder="Start typing your postcode or address" @keyup="findDeliveryAddress">
                              <span class="input-group-addon">
                                    <a  ><i class="fa fa-search"></i></a>
                              </span>
                            </div>

                              <div class="input-group select2-bootstrap-append hide deladdressdrp">
                                <select class="form-control" id="deladdressdrp">
                                </select>
                              </div>
                         </div>
                      </div>

                      <!-- <div class="form-group">
                          <label class="col-md-3 control-label">Delivery Address</label>
                          <div class="col-md-7">
                              <input type="text" name="delivery_address" id="delivery_address" class="form-control" >
                          </div>
                      </div> -->

                      <div class="form-group">
                          <label class="col-md-3 control-label">Address line 1</label>
                          <div class="col-md-7">
                                <input type="text" name="c_address1" id="c_address1" value="@{{clubdata.delivery_address_line1}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Address line 2</label>
                          <div class="col-md-7">
                                <input type="text" name="c_address2" id="c_address2" value="@{{clubdata.delivery_address_line2}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Address line 3</label>
                          <div class="col-md-7">
                                <input type="text" name="c_address3" id="c_address3" value="@{{clubdata.delivery_address_line3}}" class="form-control" >
                         </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Town</label>
                          <div class="col-md-7">
                                <input type="text" name="c_town" id="c_town" value="@{{clubdata.delivery_town}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">County
                            <span class="required">*</span>
                          </label>
                          <div class="col-md-7">
                                <input type="text" name="c_county" id="c_county" value="@{{clubdata.delivery_county}}" class="form-control" >
                         </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Postcode</label>
                          <div class="col-md-7">
                                <input type="text" name="c_postcode" id="c_postcode" value="@{{clubdata.delivery_postcode}}" class="form-control uppercase" >
                         </div>
                      </div>

                      <div class="form-group">
                        <span class="col-md-3 text-right">Posters</span>
                        <div class="col-md-7">
                          <input type="checkbox" name="posters" id="posters" class="pull-left">
                          <!-- <div class="md-checkbox">
                              <input type="checkbox" name="posters" id="posters" class="md-check">
                              <label for="posters">
                              <span></span>
                              <span class="check"></span>
                              <span class="box"></span>
                              </label>
                          </div> -->
                        </div>
                      </div>

                      <div class="form-group">
                      <span class="col-md-3 text-right">Beermats</span>
                         <div class="col-md-7">
                          <input type="checkbox" name="beermats" id="beermats" class="pull-left">
                          <!-- <div class="md-checkbox">
                              <input type="checkbox" name="beermats" id="beermats" class="md-check">
                              <label for="beermats">
                              <span></span>
                              <span class="check"></span>
                              <span class="box"></span>
                              </label>
                          </div> -->
                        </div>

                      </div>

                      <div class="form-group">
                        <span class="col-md-3 text-right">Business cards</span>
                         <div class="col-md-7">
                          <input type="checkbox" name="business_cards" id="business_cards" class="pull-left">
                          <!-- <div class="md-checkbox">
                              <input type="checkbox" name="business_cards" id="business_cards" class="md-check">
                              <label for="business_cards">
                              <span></span>
                              <span class="check"></span>
                              <span class="box"></span>
                              </label>
                          </div> -->
                        </div>

                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Vista ordered</label>

                           <div class="col-md-7">
                              <!-- <input class="form-control  date-picker"  type="text" name="vista_ordered" id="vista_ordered" value="" /> -->

                              <div data-date-format="dd-mm-yyyy" class="input-group date date-picker">
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
                          <label class="col-md-3 control-label">Vista dispatched</label>

                           <div class="col-md-7">
                              <!-- <input class="form-control  date-picker"  type="text" name="vista_dispatched" id="vista_dispatched" value="" /> -->
                              <div data-date-format="dd-mm-yyyy" class="input-group date date-picker">
                                  <input type="text" readonly="" class="form-control" name="vista_dispatched" id="vista_dispatched">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn default">
                                          <i class="fa fa-calendar"></i>
                                      </button>
                                  </span>
                              </div>
                          </div>

                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">POS dispatched</label>

                           <div class="col-md-7">
                              <!-- <input class="form-control  date-picker"  type="text" name="pos_dispatched" id="pos_dispatched" value="" /> -->
                              <div data-date-format="dd-mm-yyyy" class="input-group date date-picker">
                                  <input type="text" readonly="" class="form-control" name="pos_dispatched" id="pos_dispatched">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn default">
                                          <i class="fa fa-calendar"></i>
                                      </button>
                                  </span>
                              </div>
                          </div>

                      </div>

                  </div>

              <!-- END FORM-->
          </div>
        </div>
        <div class="portlet box green" id="collapse_3">
          <div class="portlet-title">
              <div class="caption">
                  Site Settings</div>
              <div class="tools">
                  <a href="javascript:;" class="custom-bar expand" id="customCollapse3"> </a>
                 <!-- <a href="javascript:;" class="reload" @click="reloadData('collapse_3')"> </a>-->
              </div>
          </div>
          <div class="portlet-body form" style="display: none;">
              <!-- BEGIN FORM-->


                  <div class="form-body">
                      <div class="form-group">
                          <label class="col-md-3 control-label">Desired URL<span class="required">*</span></label>
                          <div class="col-md-7" >
                            <input type="text" name="desired_url" id="desired_url" value="@{{clubdata.desired_url}}" class="form-control desired_url" placeholder="Desired URL">
                            <input type="text" class="form-control desired_url" name="desired_url2" id="desired_url2" value=".myclubbetting.co.uk" readonly="">
                          </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-offset-3 col-md-8">
                        <div class="help-block">URL should contain letters and numbers only with a maximum of one hyphen </div>
                        </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Pick site colour</label>
                          <div class="col-md-1">
                              <input class="form-control" type="hidden" name="sitecolor" id="color_code">
                              <div id="sitecolor" onclick="$('#selectcolor').slideToggle();" style="background-color: @{{clubdata.primary_colour}}"></div>
                           </div>
                      </div>

                      <div class="form-group" id="selectcolor" style="display: none;">
                          <div class="col-md-offset-3 col-md-7">
                              <ul class="colorlist">
                                <li><a><div data-id="6a2382" style="background-color: #6a2382;"></div></a></li>
                                <li><a><div data-id="ee7401" style="background-color: #ee7401;"></div></a></li>
                                <li><a><div data-id="a3cc84" style="background-color: #a3cc84;"></div></a></li>
                                <li><a><div data-id="005f20" style="background-color: #005f20;"></div></a></li>
                                <li><a><div data-id="a0c9ec" style="background-color: #a0c9ec;"></div></a></li>
                                <li><a><div data-id="004288" style="background-color: #004288;"></div></a></li>
                                <li><a><div data-id="e94589" style="background-color: #e94589;"></div></a></li>
                                <li><a><div data-id="000000" style="background-color: #000000;"></div></a></li>
                                <li><a><div data-id="e31a13" style="background-color: #e31a13;"></div></a></li>
                                <li><a><div data-id="5a3d12" style="background-color: #5a3d12;"></div></a></li>
                                <li><a><div data-id="ffeb00" style="background-color: #ffeb00;"></div></a></li>
                              </ul>

                           </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Twitter</label>
                          <div class="col-md-7">
                             <input type="text" name="twitter" id="twitter" value="@{{clubdata.twitter}}" class="form-control" placeholder="Twitter handle">
                           </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Facebook</label>
                          <div class="col-md-7">
                             <input type="text" name="facebook" id="facebook" value="@{{clubdata.facebook}}" class="form-control" placeholder="Facebook URL">
                           </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Super affiliate<span class="required">*</span>
                          </label>
                          <div class="col-md-7">
                              <!-- <div class="input-group select2-bootstrap-append"> -->
                                  <select class="form-control select2-allow-clear" name="super_affiliate" id="super_affiliate" data-placeholder="Select super affiliate">
                                      <option value="" selected></option>
                                      <option value="CWTransfer">CWTransfer</option>
                                      <option value="MyClubBetting">MyClubBetting</option>
                                      <option value="ClubWebsite">ClubWebsite</option>
                                      <option value="MillerSport">MillerSport</option>
                                      <option value="MyPubBetting">MyPubBetting</option>
                                      <option value="EagleGolf">EagleGolf</option>
                                      <option value="GolfCode">GolfCode</option>
                                      <option value="NorthernIreland">NorthernIreland</option>

                                  </select>
                              <!-- </div> -->
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-md-3 control-label">Representative</label>
                          <div class="col-md-7">
                              <input type="text" class="form-control" name="representative" value="@{{clubdata.representative}}" id="representative">
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 control-label">Promo code</label>
                          <div class="col-md-7">
                            <input type="text" class="form-control" name="promocode" value="@{{clubdata.promo_code}}" id="promocode">
                          </div>
                      </div>

                       <div class="form-group">
                          <label class="col-md-3 control-label">Notes</label>
                          <div class="col-md-7">
                            <textarea name="notes" id="notes"class="form-control" rows="5">@{{clubdata.comment}}</textarea>
                          </div>
                      </div>
                    
                      <div class="form-group">
                          <span class="col-md-3 text-right">Hide from view</span>
                        <div class="col-md-7">
                      <input type="checkbox" name="hide_view" id="hide_view" class="pull-left">
                        <!-- <div class="md-checkbox">
                              <input type="checkbox" name="hide_view" id="hide_view" class="md-check">
                              <label for="hide_view">
                              <span></span>
                              <span class="check"></span>
                              <span class="box"></span>
                              </label>
                          </div> -->
                        </div>

                      </div>
                    

                      <div class="form-group">
                        <span class="col-md-3 text-right">Test club</span>
                         <div class="col-md-7">
                          <input type="checkbox" name="test_club" id="test_club" class="pull-left">
                          <!-- <div class="md-checkbox">
                              <input type="checkbox" name="test_club" id="test_club" class="md-check">
                              <label for="test_club">
                              <span></span>
                              <span class="check"></span>
                              <span class="box"></span>
                              </label>
                          </div> -->
                        </div>

                      </div>
                       <div class="form-group">
                        <span class="col-md-3 text-right"> Rev share (%)</span>
                         <div class="col-md-7">
                          <input type="text" name="rev_share" id="rev_share" class="form-control"  value="@{{clubdata.rev_share}}">

                        </div>

                      </div>


                    </div>

              <!-- END FORM-->
          </div>
        </div>
        <span v-show="clubdata['logsInfo'].length > 0">
        <div class="portlet box green" id="collapse_4" v-if="status =='edit'">

          <div class="portlet-title">
              <div class="caption">
                  Modifications</div>
              <div class="tools">
                  <a href="javascript:;" class="custom-bar expand" id="customCollapse4"> </a>
                  <a href="javascript:;" class="reload" @click="reloadData('collapse_4')"> </a>
              </div>
          </div>

          <div class="portlet-body form"  style="display: none;" >
              <!-- BEGIN FORM-->
             <!--<h4 class="block col-md-12">Log Section</h4>-->

                  <div class="form-body">
                      <div class="form-group" v-for="(index, item)  in clubdata['logsInfo']">

                          <div class="col-md-7">

                           <span v-if="item.state == 'new'">Club  Created:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@{{date(item.created_at)}}
                           </span>
                           <span v-if="item.state == 'active'">Club  Activated:&nbsp;&nbsp;@{{date(item.created_at)}}
                           </span>
                           <span v-if="item.state == 'suspended'">Club  Suspended:&nbsp;&nbsp;@{{date(item.created_at)}}
                           </span>
                            <span v-if="item.state == 'deleted'">Club  Deleted:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@{{date(item.created_at)}}
                           </span>
                           </div>


                      </div>
                  </div>
              <!-- END FORM-->
          </div>
        </div>
        </span>

        --}}
          <div class="form-actions top">
            <div class="row pull-right">
                <div class="col-md-12">
                  <button type="button" id="submitClub" class="btn green margin-right-5" @click="saveClub()">Save</button>
                  <button type="button" class="btn default margin-right-5" @click="reloadData('frmClubSaveData')">Clear</button>
                  <button type="button" class="btn grey" @click="goBack()">Cancel</button>
                    <!-- <button type="submit" class="btn green">Submit</button> -->
                    <!-- <button type="button" class="btn default">Cancel</button> -->
                </div>
            </div>
          </div>
      </form>

    </div>
  </div>
</div>
</div>
</div>
@endsection

@section('plugin-scripts')
<script src="{{ asset('admin_theme/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin_theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin_theme/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>

@endsection

@section('page-scripts')
  <script src="{{ asset('js/tournament.js') }}"></script>
  <script src="{{ asset('js/jscolor.min.js') }}"></script>
@endsection