@extends('layouts.app')

@section('content')
	
  <!-- route outlet -->
  <!-- component matched by the route will render here -->
    <div class="flex-center position-ref full-height">
    		
        <div class="content">
            <div class="text-center">
                <h1>Euro Sportirng</h1>    
                  <router-link to="/top">Top</router-link>
                   <router-link to="/foo">Foo</router-link>
                  <router-link to="/bar">Bar</router-link>            
                <router-view></router-view>      
            </div>

         </div>
         
       
           
    </div>
@endsection

@section('pageStyle')

@endsection