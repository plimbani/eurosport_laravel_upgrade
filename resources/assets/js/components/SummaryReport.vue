<template>
	<div class="tab-content summary-report-content">
		<h6><strong>{{$lang.summary_reports}}</strong></h6>
		<div class="row">
			<div class="col-md-6">
				<span>{{$lang.summary_message}}</span>
			</div>
			<div class="col-md-6 text-right">
				<button type="button" class="btn btn-primary" @click='exportReport()'>{{$lang.summary_button_download}}</button>
                <button type="submit" class="btn btn-primary">{{$lang.summary_button_print}}</button>
			</div>
		</div>
		<div class="block-bg mt-4">
			<form name="frmReport" id="frmReport" class="col-md-12 report_form">
				<div class="form-group row text-left">
					<div class="col-md-7 row">
						<div class="col-md-4 mb-7">
							<label><strong>{{$lang.summary_age_category}}</strong></label>
							<div class="form-group">
	                            <select name="sel_ageCategory" id="sel_ageCategory" class="form-control ls-select2">
	                            	<option value="">Select</option>
		                            <option v-for="(competation, index) in competationList" :value="competation.id">{{competation.group_name}}</option>
		                            <!-- <option value="">-----------</option> -->
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>{{$lang.summary_club}}</strong></label>
							<div class="form-group">
	                            <select class="form-control ls-select2">
		                            <option value="">Select</option>
		                            <option value="">-----------</option>
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>{{$lang.summary_team}}</strong></label>
							<div class="form-group">
	                            <select name="sel_teams" id="sel_teams" class="form-control ls-select2">
	                            	<option value="">Select</option>
		                        	<option v-for="(team, index) in teams" :value="team.id">{{team.name}}</option>
		                                                      
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="col-md-5 row">
						<div class="col-md-6 mb-5">
							<label><strong>{{$lang.summary_from}}</strong></label>
							<div class="form-group">
							 <input type="text" 
							 name="start_date" id="start_date" value="" class="form-control ls-datepicker " >
		                    </div>
						</div>
						<div class="col-md-6 mb-5">
							<label><strong>{{$lang.summary_to}}</strong></label>
							<div class="form-group">
	                             <input type="text" 
							 name="end_date" id="end_date" value="" class="form-control ls-datepicker " >
		                    </div>
						</div>
					</div>
					<div class="col-md-7 row">
						<div class="col-md-4 mb-7">
							<label><strong>{{$lang.summary_location}}</strong></label>
							<div class="form-group">
	                           <select name="sel_venues" id="sel_venues"  class="form-control ls-select2">
	                           		<option value="">Select</option>
		                        	<option v-for="(venue, index) in venues" :value="venue.id">{{venue.name}}</option>
		                                                      
		                        </select>
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>{{$lang.summary_pitch}}</strong></label>
							<div class="form-group">
							<select name="sel_pitches" id="sel_pitches" class="form-control ls-select2">
								<option value="">Select</option>
		                        <option v-for="(pitch, index) in pitches" :value="pitch.id">{{pitch.pitch_number}}</option>
		                    </select>
	                            
		                    </div>
						</div>
						<div class="col-md-4 mb-7">
							<label><strong>{{$lang.summary_referee}}</strong></label>
							<div class="form-group">
	                           	<select name="sel_referees" id="sel_referees" class="form-control ls-select2">
	                           		<option value="">Select</option>
		                        	<option v-for="(referee, index) in referees" :value="referee.id">{{referee.first_name}}</option>
		                        </select>
		                    </div>
						</div>
					</div>
					<div class="col-md-5 text-right">
						<button type="button" name="clearButton" id="clearButton" class="btn btn-primary" @click="clearForm()">{{$lang.summary_button_clear}}</button>
                		<button type="button" name="generateReport" id="generateReport" class="btn btn-primary" @click="generateReport()">{{$lang.summary_button_generate}}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-12 row mt-4">
			<table class="table">
				<thead>
                    <tr>
                        <th>{{$lang.summary_reports_date}}</th>
                        <th>{{$lang.summary_reports_age_catrgory}}</th>
                        <th>{{$lang.summary_reports_location}}</th>
                        <th>{{$lang.summary_reports_pitch}}</th>
                        <th>{{$lang.summary_reports_referee}}</th>
                        <th>{{$lang.summary_reports_game}}</th>
                    </tr>
                </thead>
                <tbody>
                	<tr v-for="(report,index) in reports">
                		<td>{{report.match_datetime}}</td>
                		<td>{{report.group_name}}</td>
                		<td>{{report.venue_name}}</td>
                		<td>{{report.pitch_number}}</td>
                		<td>{{report.referee_name}}</td>
                		<td>{{report.full_game}}</td>
                	</tr>
                	<tr v-if="reports.length==0">
                		<td colspan="6">No Record Found</td>
                	</tr>
                </tbody>
			</table>
		</div>
	</div>
</template>

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
       	reports: {},
        currentView:'summaryTab',
        reportQuery:{}

       	}
    },	
    mounted() {
    	this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
    	this.displayTournamentCompetationList()
    	this.getTeams()
    	this.getLocation()
    	this.getPitches()
    	this.getReferees()
    	$('.ls-datepicker').datepicker()
    	$('#start_date').datepicker().on('changeDate',function(){
            $('#end_date').datepicker('setStartDate', $('#start_date').val())
        });
        // $('#end_date').datepicker().on('changeDate',function(){
        //     $('#start_date').datepicker('setEndDate', $('#end_date').val())
        // });
    },
    methods: {
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
		      let TournamentData = {'tournament_id': this.TournamentId}
		      Tournament.getTeams(this.TournamentId).then(
		      (response) => {          
		        this.teams = response.data.data         
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
    	clearForm() {
    		$('#frmReport')[0].reset()
    	},
    	generateReport($export='') {
    		if (!isNaN(this.TournamentId)) {
		      // here we add data for 
		      let ReportData = {'tournament_id': this.TournamentId,'age_category': $('#sel_ageCategory').val(),'team': $('#sel_teams').val(),'start_date': $('#start_date').val(),'end_date': $('#end_date').val(),'location': $('#sel_venues').val(),'pitch': $('#sel_pitches').val(),'referee': $('#sel_referees').val(),'report_download':''}
		      this.reportQuery = ReportData
		      Tournament.getAllReportsData(ReportData).then(
		      (response) => {  
		      	this.reports = response.data.data 
		       },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }	
    	},
    	exportReport() {
    		let ReportData = this.reportQuery 
    		ReportData.report_download = 'yes'
    		Tournament.getAllReportsData(ReportData).then(
		      (response) => {  
		      	
		        // this.referees = response.data.referees         
		        // console.log(this.competationList);
		      },
		      (error) => {
		         console.log('Error occured during Tournament api ', error)
		      }
		      )
    		// this.generateReport('yes')

    	}


    }  
}
	
</script>