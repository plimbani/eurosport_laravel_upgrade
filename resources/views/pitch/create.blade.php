@extends('layouts.admin_theme')
@section('page-title', 'EuroSport')
@section('page-css')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<!-- BEGIN PAGE LEVEL PLUGINS -->

    <!-- END PAGE LEVEL PLUGINS -->
@endsection
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">                 
      
    <div class="portlet light bordered">
       
        <div class="portlet-title">
            <div class="caption">
              Pitch Details
            </div>
          </div>
        <div class="portlet-body" id="pitchSet" v-cloak>
              <pitch-form :pitchdata="pitchdata" :pitchdata123="pitchdata123" ></pitch-form>  
                         
        </div>
      </div>
      </div>
   
@endsection

@section('plugin-scripts')
<script src="{{ asset('admin_theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>


@endsection
@section('page-scripts')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="{{ asset('js/pitch.js') }}"> </script>
@endsection