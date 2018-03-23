@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.tournament') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-12 club_content macth_table" id="matches_list">
	                <hr class="hr m-0">
	                <div class="table-responsive">
	                    <table class="table">
	                        <thead>
	                            <tr>
	                                <th scope="col">Category</th>
	                                <th scope="col">Born on or after</th>
	                                <th scope="col">Number of teams</th>
	                                <th scope="col">Game length</th>
	                                <th scope="col">Players per team</th>
	                                <th scope="col">Number of matches</th>
	                                <th scope="col">Substitues allowed</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            <tr>
	                                <td>U19</td>
	                                <td>01.01.99</td>
	                                <td><a href="#">16</a></td>
	                                <td>2 * 15</td>
	                                <td>11</td>
	                                <td>5</td>
	                                <td>5</td>
	                            </tr>
	                            <tr>
	                                <td>U17</td>
	                                <td>01.01.01</td>
	                                <td>24</td>
	                                <td>2 * 15</td>
	                                <td>11</td>
	                                <td>5</td>
	                                <td>5</td>
	                            </tr>
	                            <tr>
	                                <td>U16</td>
	                                <td>01.01.02</td>
	                                <td>16</td>
	                                <td>2 * 15</td>
	                                <td>11</td>
	                                <td>5</td>
	                                <td>5</td>
	                            </tr>
	                            <tr>
	                                <td>U15</td>
	                                <td>01.01.03</td>
	                                <td>12</td>
	                                <td>2 * 15</td>
	                                <td>11</td>
	                                <td>5</td>
	                                <td>5</td>
	                            </tr>
	                            <tr>
	                                <td>U14</td>
	                                <td>01.01.04</td>
	                                <td>16</td>
	                                <td>2 * 15</td>
	                                <td>11</td>
	                                <td>5</td>
	                                <td>5</td>
	                            </tr>
	                            <tr>
	                                <td>U13</td>
	                                <td>01.01.05</td>
	                                <td>32</td>
	                                <td>2 * 15</td>
	                                <td>11</td>
	                                <td>5</td>
	                                <td>5</td>
	                            </tr>	                            
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>

	            <div class="col-lg-3">
	            </div>
	            <div class="col-lg-8 club_content">
	                {!! $tournamentContent->content !!}
	            </div>	        
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection
