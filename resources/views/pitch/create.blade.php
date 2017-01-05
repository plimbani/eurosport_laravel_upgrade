@extends('layouts.admin_theme')
@section('page-title', 'EuroSport')
@section('page-css')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link href="{{ asset('admin_theme/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_theme/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
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
          <!-- <multiselect :options="options"></multiselect> -->

              <pitch-form :pitchdata="pitchdata"  ></pitch-form>
              <!-- <pitch-allocation-form :pitchallocationdata="pitchallocationdata"   ></pitch-allocation-form>   -->
        </div>
      </div>
      </div>
   
@endsection

@section('plugin-scripts')
<script src="{{ asset('admin_theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>


@endsection
@section('page-scripts')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('admin_theme/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin_theme/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>
    <script src="https://npmcdn.com/vue-select@1.3.3"></script>
    
  <script src="{{ asset('js/pitch.js') }}"> </script>
@endsection