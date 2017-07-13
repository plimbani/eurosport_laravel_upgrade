<template>
	<div class="tab-content summary-report-content">
		<h6>{{$lang.summary_reports}}</h6>
		<div class="row align-items-center">
			<div class="col-md-6">
				<span>{{$lang.summary_message}}</span>
			</div>
			<div class="col-md-6 text-right">
				<button type="button" class="btn btn-primary" @click='exportReport()'>{{$lang.summary_button_download}}</button>
                <!-- <button type="submit" class="btn btn-primary">{{$lang.summary_button_print}}</button> -->
                <button type="button" class="btn btn-primary mr-4" @click="printMatchDetails()">Print</button>
			</div>
		</div>
		<div class="block-bg mt-4">
			<form name="frmReport" id="frmReport" class="report_form">
				<div class="form-group text-left mb-0">
					<div class="row mb-4">
						<div class="col-md-7">
							<div class="row">
								<div class="col-md-4">
									<label><strong>{{$lang.summary_from}}</strong></label>
									<div class="">
										 <input type="text" name="start_date" id="start_date" value="" class="form-control ls-datepicker">
						                 <span style="color:red;" id="start_date_validation"></span>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_to}}</strong></label>
									<div class="">
			            				 <input type="text" name="end_date" id="end_date" value="" class="form-control ls-datepicker" >
				                    	<span style="color:red;" id="end_date_validation"></span>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_age_category}}</strong></label>
									<div class="">
                   						 <select name="sel_ageCategory" id="sel_ageCategory"
                   						 class="form-control ls-select2">
                    						<option value="">{{$lang.summary_age_category_select}}</option>
                      						<option v-for="(competation, index) in competationList"
                      						:value="competation.id">{{competation.group_name}}</option>
                     					</select>
				    				</div>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="row">
								<div class="col-md-6">
									<label><strong>{{$lang.summary_club}}</strong></label>
									<div class="">
				                    	<select class="form-control ls-select2" v-on:change="onSelectClub()" name="sel_clubs"
				                    	id="sel_clubs" v-model="club">
					                      <option value="">{{$lang.summary_club_select}}</option>
					                      <option v-for="(club, index) in clubs"
					                      :value="club.id">{{club.name}}</option>
					                    </select>
								 	</div>
								</div>
								<div class="col-md-6">
									<label><strong>{{$lang.summary_team}}</strong></label>
									<div class="">
					                    <select name="sel_teams" id="sel_teams" v-model="team" class="form-control ls-select2">
						                    <option value="">{{$lang.summary_team_select}}</option>
						                  	<option v-for="(team, index) in teams" :value="team.id">{{team.name}}</option>
					                    </select>
							        </div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mb-4">
						<div class="col-md-7">
							<div class="row">
								<div class="col-md-4">
									<label><strong>{{$lang.summary_from_time}}</strong></label>
									<div>
										<select name="start_time" id="start_time"  class="form-control ls-select2">
											<option value="">Select</option>
											<option value="08:00">08:00</option>
											<option value="08:30">08:30</option>
											<option value="09:00">09:00</option>
											<option value="09:30">09:30</option>
											<option value="10:00">10:00</option>
											<option value="10:30">10:30</option>
											<option value="11:00">11:00</option>
											<option value="11:30">11:30</option>
											<option value="12:00">12:00</option>
											<option value="12:30">12:30</option>
											<option value="13:00">13:00</option>
											<option value="13:30">13:30</option>
											<option value="14:00">14:00</option>
											<option value="14:30">14:30</option>
											<option value="15:00">15:00</option>
											<option value="15:30">15:30</option>
											<option value="16:00">16:00</option>
											<option value="16:30">16:30</option>
											<option value="17:00">17:00</option>
											<option value="17:30">17:30</option>
											<option value="18:00">18:00</option>
											<option value="18:30">18:30</option>
											<option value="19:00">19:00</option>
										</select>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_to_time}}</strong></label>
									<div>
										<select name="end_time" id="end_time"  class="form-control ls-select2">
											<option value="">Select</option>
											<option value="08:00">08:00</option>
											<option value="08:30">08:30</option>
											<option value="09:00">09:00</option>
											<option value="09:30">09:30</option>
											<option value="10:00">10:00</option>
											<option value="10:30">10:30</option>
											<option value="11:00">11:00</option>
											<option value="11:30">11:30</option>
											<option value="12:00">12:00</option>
											<option value="12:30">12:30</option>
											<option value="13:00">13:00</option>
											<option value="13:30">13:30</option>
											<option value="14:00">14:00</option>
											<option value="14:30">14:30</option>
											<option value="15:00">15:00</option>
											<option value="15:30">15:30</option>
											<option value="16:00">16:00</option>
											<option value="16:30">16:30</option>
											<option value="17:00">17:00</option>
											<option value="17:30">17:30</option>
											<option value="18:00">18:00</option>
											<option value="18:30">18:30</option>
											<option value="19:00">19:00</option>
										</select>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_location}}</strong></label>
									<div class="">
				                     	<select name="sel_venues" id="sel_venues"  class="form-control ls-select2">
				                     		<option value="">Select</option>
				                    		<option v-for="(venue, index) in venues" :value="venue.id">{{venue.name}}</option>
				                    	</select>
								    </div>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="row">
								<div class="col-md-6">
									<label><strong>{{$lang.summary_pitch}}</strong></label>
									<div class="">
	  									<select name="sel_pitches" id="sel_pitches" class="form-control ls-select2">
	  										<option value="">Select</option>
	  				           				<option v-for="(pitch, index) in pitches" :value="pitch.id">{{pitch.pitch_number}}</option>
	  				        			</select>
				         			</div>
								</div>
								<div class="col-md-6">
									<label><strong>{{$lang.summary_referee}}</strong></label>
									<div class="">
					                   	<select name="sel_referees" id="sel_referees" class="form-control ls-select2">
					                   		<option value="">Select</option>
					                  		<option v-for="(referee, index) in referees" :value="referee.id">{{referee.last_name}}, {{referee.first_name}}</option>
					                    </select>
							        </div>
							    </div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="row">
								<div class="col-sm-4">
									<button type="button" name="clearButton" id="clearButton" class="btn btn-primary w-100" @click="clearForm()">{{$lang.summary_button_clear}}</button>
								</div>
								<div class="col-sm-4">
									<button type="button" name="generateReport" id="generateReport" class="btn btn-primary w-100" @click="generateReport()">{{$lang.summary_button_generate}}</button>
								</div>
		                	</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row mt-4" id="summary_report_table">
			<div class="col-md-12">
				<table class="table table-hover table-bordered" border="1" cellpadding="0" cellspacing="0" width="100%">		
					<thead>
	                    <tr>
	                        <th class="text-center">{{$lang.summary_reports_date_time}}</th>
	                        <th class="text-center">{{$lang.summary_reports_age_catrgory}}</th>
	                        <th class="text-center">{{$lang.summary_reports_location}}</th>
	                        <th class="text-center">{{$lang.summary_reports_pitch}}</th>
	                        <th class="text-center">{{$lang.summary_reports_referee}}</th>
	                        <th class="text-center">{{$lang.summary_reports_game}}</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<tr v-for="(report,index) in reports">
	                		<td>{{report.match_datetime | formatDate }}</td>
	                		<td>{{report.group_name}}</td>
	                		<td>{{report.venue_name}}</td>
	                		<td>{{report.pitch_number}}</td>
	                		<td v-if="report.referee_last_name && report.referee_first_name">{{report.referee_last_name}}, {{report.referee_first_name}}</td>
		             		<td v-else></td>
	                		<td>{{report.full_game}}</td>
	                	</tr>
	                </tbody>
				</table>
				<span v-if="reports.length == 0">
	         		 No information available
	    		</span>
			</div>
		</div>
	</div>
</template>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script> -->
<script type="text/babel">
	import Tournament from '../api/tournament.js'
	import Pitch from '../api/pitch.js'

export default {
    data() {
       return {
       	competationList : {}, TournamentId: 0, competation_id: '',setTime:'',
       	teams:{},
       	pitches: {},
       	venues: {},
       	referees: {},
        clubs: {},
        club:'',
        team:'',
       	reports: {},
        currentView:'summaryTab',
        reportQuery:''

       	}
    },
    filters: {
    	formatDate: function(date) {
     	return moment(date).format("HH:mm ddd DD MMM YYYY");
   	   },
    },
    mounted() {
    	this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
    	this.displayTournamentCompetationList()
    	//this.getTeams()
    	this.getLocation()
    	this.getPitches()
    	this.getReferees()
        this.getClubs()
    	$('.ls-datepicker').datepicker()
    	$('#start_date').datepicker().on('changeDate',function(){
            $('#end_date').datepicker('setStartDate', $('#start_date').val())
        });

		 $('#start_time,#start_date').change(function(){
		   if($('#start_date').val() == ''){
		      $("#start_date_validation").html("Please enter values");
		   } else {
		   	  $("#start_date_validation").html("");
		   }
		});

	    $('#end_time,#end_date').change(function(){
		   if($('#end_date').val() == ''){
		      $("#end_date_validation").html("Please enter values");
		   } else {
		   	  $("#end_date_validation").html("");
		   }
		});


    },
    methods: {
    	onSelectClub() {
    		let clubData = this.club
    		let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId,'clubId': this.club}
				Tournament.getClubsTeams(TournamentData).then(
		          (response) => {
		           this.teams = response.data.data
		          },
		          (error) => {
		             console.log('Error occured during Tournament api ', error)
		          }
		        )
    		},

    	displayTournamentCompetationList () {
      		// Only called if valid tournament id is Present
		    if (!isNaN(this.TournamentId)) {
		      // here we add data for
		      let TournamentData = {'tournament_id': this.TournamentId}
		      Tournament.getCompetationFormat(TournamentData).then(
		      (response) => {
		        this.competationList = response.data.data
		        // console.log(this.competationList);
		      },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	getTeams() {
    		if (!isNaN(this.TournamentId)) {
		      // here we add data for
		      let TournamentData = {'tournamentId': this.TournamentId}
		      Tournament.getTeams(TournamentData).then(
		      (response) => {
		       // this.teams = response.data.data
		        // console.log(this.competationList);
		      },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	getLocation() {
    		if (!isNaN(this.TournamentId)) {
		      // here we add data for
		      let TournamentData = {'tournament_id': this.TournamentId}
		      Tournament.getAllVenues(this.TournamentId).then(
		      (response) => {
		        this.venues = response.data.data
		        // console.log(this.competationList);
		      },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	getPitches() {
    		if (!isNaN(this.TournamentId)) {
		      // here we add data for
		      let TournamentData = {'tournament_id': this.TournamentId}
		      Pitch.getAllPitches(this.TournamentId).then(
		      (response) => {
		        this.pitches = response.data.pitches
		        // console.log(this.competationList);
		      },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	getReferees() {
    		if (!isNaN(this.TournamentId)) {
		      // here we add data for
		      let TournamentData = {'tournament_id': this.TournamentId}
		      Tournament.getReferees(this.TournamentId).then(
		      (response) => {
		        this.referees = response.data.referees
		        // console.log(this.competationList);
		      },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	clearForm1() {
    		$('#frmReport')[0].reset()

    	},

	    getClubs() {
	        if (!isNaN(this.TournamentId)) {
	          // here we add data for
	          let TournamentData = {'tournament_id': this.TournamentId}
	          Tournament.getAllClubs(this.TournamentId).then(
	          (response) => {
	            this.clubs = response.data.data
	          },
	          (error) => {
	             console.log('Error occured during Tournament api ', error)
	          }
	          )
	        } else {
	          this.TournamentId = 0;
	        }
	    },
	    clearForm() {
	      $('#frmReport')[0].reset()
          this.reports = null
	    },
    	generateReport() {
    		if (!isNaN(this.TournamentId)) {
		      let ReportData = 'tournament_id='+this.TournamentId+'&'+$('#frmReport').serialize()
		     // let ReportData =  $('#frmReport').serializeArray()

		      this.reportQuery = ReportData
		      Tournament.getAllReportsData(ReportData).then(
		      (response) => {
		      	// console.log(response.data.data)
		      // console.log(response.data.data,'hi')
		      	this.reports = response.data.data
		       },

		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		      // toastr['error']('Invalid Credentials', 'Error');
		    }
    	},
    	printMatchDetails() {
	     var printContents = document.getElementById('summary_report_table').innerHTML;
	      let w = window.open();
	      w.document.write($(printContents).html());
	      w.print();
	      w.close();
	    }, 

    	exportReport() {

    		let ReportData = this.reportQuery
    		// console.log(ReportData)
    		// let newdata = $.parseHTML( ReportData )
    		// let newdata =  $(ReportData).parse();
    		// let newdata = $('#frmReport').serialize()
    		if(ReportData!=''){
				ReportData += '&report_download=yes'
    			window.location = "/tournament/report/reportExport?"+ReportData;
    		}else{
    			toastr['error']('Records not available', 'Error');
    		}


    	}


    }
}

</script>