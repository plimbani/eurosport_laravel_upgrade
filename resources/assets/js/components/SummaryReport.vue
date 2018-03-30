<template>
	<div class="tab-content summary-report-content">
	   <div class="row align-items-center">
			<div class="col-md-6">
				<span>{{$lang.summary_message}}</span>
			</div>
			<div class="col-md-6">
				<div class="d-flex align-items-center justify-content-end">
					<button class="btn btn-primary mr-1" @click='exportReport()'>{{$lang.summary_button_download}}</button>
					<button class="btn btn-primary"  @click="exportPrint()">{{$lang.summary_button_print}}</button>
				</div>
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
										 <input placeholder="All" type="text" name="start_date" id="start_date" value="" class="form-control ls-datepicker">
						                 <span style="color:red;" id="start_date_validation"></span>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_to}}</strong></label>
									<div class="">
			            				 <input placeholder="All" type="text" name="end_date" id="end_date" value="" class="form-control ls-datepicker" >
				                    	<span style="color:red;" id="end_date_validation"></span>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_age_category}}</strong></label>
									<div class="">
                   						 <select name="sel_ageCategory" id="sel_ageCategory"
			                                v-on:change="onSelectAgeCategory()"
			                                v-model="age_category_id" class="form-control ls-select2">
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
				                    	<select class="form-control ls-select2" v-on:change="onSelectClub()"
				                    	  name="sel_clubs" id="sel_clubs" v-model="club">
					                      <option value="">{{$lang.summary_club_select}}</option>
					                      <option v-for="(club, index) in clubs"
					                      :value="club.id">{{club.name}}</option>
					                    </select>
								 	</div>
								</div>
								<div class="col-md-6">
									<label><strong>{{$lang.summary_team}}</strong></label>
									<div class="">
					                    <select name="sel_teams" id="sel_teams" v-model="team" class="form-control ls-select2" v-on:change="onSelectTeam()">
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
											<option value="">All</option>
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
											<option value="19:30">19:30</option>
											<option value="23:00">23:00</option>
										</select>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_to_time}}</strong></label>
									<div>
										<select name="end_time" id="end_time"  class="form-control ls-select2">
											<option value="">All</option>
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
											<option value="19:30">19:30</option>
											<option value="23:00">23:00</option>
										</select>
				                    </div>
								</div>
								<div class="col-md-4">
									<label><strong>{{$lang.summary_location}}</strong></label>
									<div class="">
				                     	<select name="sel_venues" id="sel_venues"  class="form-control ls-select2">
				                     		<option value="">All</option>
				                    		<option v-for="(venue, index) in venues" :value="venue.id">{{venue.name}}</option>
				                    	</select>
								    </div>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="row">
								<div class="col-md-6">
									<label><strong>{{$lang.summary_pitch_select}}</strong></label>
									<div class="">
	  									<select name="sel_pitches" id="sel_pitches" class="form-control ls-select2">
	  										<option value="">All</option>
	  				           				<option v-for="(pitch, index) in pitches" :value="pitch.id">{{pitch.pitch_number}}</option>
	  				        			</select>
				         			</div>
								</div>
								<div class="col-md-6">
									<label><strong>{{$lang.summary_referee_select}}</strong></label>
									<div class="">
					                   	<select name="sel_referees" id="sel_referees" class="form-control ls-select2">
					                   		<option value="">All</option>
					                  		<option v-for="(referee, index) in referees" :value="referee.id">{{referee.last_name}}, {{referee.first_name}}</option>
					                    </select>
							        </div>
							    </div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row align-items-center mt-4">
			<div class="col-md-12">
				<div class="d-flex align-items-center justify-content-end">
					<button type="button" name="clearButton" id="clearButton" class="btn btn-primary mr-1" @click="clearForm()">{{$lang.summary_button_clear}}</button>
					<button type="button" name="generateReport" id="generateReport" class="btn btn-primary" @click="generateReport()">{{$lang.summary_button_generate}}</button>
				</div>
			</div>
		</div>	
		<div class="row mt-4" id="summary_report_table">
			<div class="col-md-12">
				<div id="report_logo" style="display:none;">
                    <img src="/assets/img/logo-desk.svg"  alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="200px">
                    <h2>Reports</h2>
                </div>
				<table class="table table-hover table-bordered table-responsive report-table" v-bind:class="{ 'display_table' : reports.length == 0, 'display_block' : reports.length > 0 }" id="report_print" border="1" cellpadding="0" cellspacing="0" width="100%">
					<thead>
	                    <tr>
							<th class="text-center" @click="sortReport('match_datetime')">{{$lang.summary_reports_date_time}}&nbsp;<i class="fa fa-sort"></i></th>
	                        <th class="text-center" @click="sortReport('group_name')">{{$lang.summary_reports_age_catrgory}}&nbsp;<i class="fa fa-sort"></i></th>
	                        <th class="text-center" @click="sortReport('venue_name')">{{$lang.summary_reports_location}}&nbsp;<i class="fa fa-sort"></i></th>
	                        <th class="text-center" @click="sortReport('pitch_number')">{{$lang.summary_reports_pitch}}&nbsp;<i class="fa fa-sort"></i></th>
	                        <th class="text-center" @click="sortReport('referee')">{{$lang.summary_reports_referee}}&nbsp;<i class="fa fa-sort"></i></th>
	                        <th class="text-center" @click="sortReport('displayMatchNumber')">{{$lang.summary_reports_match_code}}&nbsp;<i class="fa fa-sort"></i></th>
                            <th class="text-center" @click="sortReport('HomeTeam')">{{$lang.summary_schedule_matches_team}}&nbsp;<i class="fa fa-sort"></i></th>
                            <th class="text-center" @click="sortReport('AwayTeam')">{{$lang.summary_schedule_matches_team}}&nbsp;<i class="fa fa-sort"></i></th>
                            <th class="text-center" @click="sortReport('position')">{{$lang.summary_schedule_matches_placing}}&nbsp;<i class="fa fa-sort"></i></th>
                    	</tr>
	                </thead>
	                <tbody>
	                	<tr v-for="report in reports">
	                		<td>{{report.match_datetime | formatDate }}</td>
	                		<td>{{report.group_name}}</td>
	                		<td>{{report.venue_name}}</td>
	                		<td>{{report.pitch_number}}</td>
	                		<td v-if="report.referee_last_name && report.referee_first_name">{{report.referee_last_name}}, {{report.referee_first_name}}</td>
  		             		<td v-else></td>
	                		<td>{{displayMatch(report.displayMatchNumber,report.displayHomeTeamPlaceholder,report.displayAwayTeamPlaceholder)}}</td>
							<td align="right">
								<span class="text-center" v-if="(report.homeTeam == '0' )">{{ getHoldingName(report.competition_actual_name, report.displayHomeTeamPlaceholder,report.displayMatchNumber) }}</span>
								<span class="text-center" v-else>{{ report.HomeTeam }}</span>
								<span :class="'flag-icon flag-icon-'+report.HomeCountryFlag"></span>
							</td>
							<td align="left">
								<span :class="'flag-icon flag-icon-'+report.AwayCountryFlag"></span>
								<span class="text-center" v-if="(report.awayTeam == '0')">{{ getHoldingName(report.competition_actual_name, report.displayAwayTeamPlaceholder,report.displayMatchNumber) }}</span>
								<span class="text-center" v-else>{{ report.AwayTeam }}</span>
							</td>
							<td align="center">{{ (report.position != null) ? report.position : 'N/A' }}</td>
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
<script >
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
       	reports: [],
        currentView:'summaryTab',
        reportQuery:'',
        isValidate:false,
        age_category_id: '',
        sortKey: 'match_datetime',
        sortBy: 'asc',
        reverse: false,
       	}
    },
    computed: {
      Reports1() {
       return _.orderBy(this.reports, this.sortKey, this.sortBy)
      }
    },
    filters: {
    	formatDate: function(date) {
    		if(date != null) {
     			return moment(date).format("Do MMM YYYY HH:mm");
    		} else {
    			return '-';
    		}
   	   },
    },
    mounted() {
    	this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
    	this.displayTournamentCompetationList()
    	this.getTeams()
    	this.getLocation()
    	this.getPitches()
    	this.getReferees()
      this.getClubs()
      let stdate  = false
    	$('.ls-datepicker').datepicker({autoclose: true})
    	$('#start_date').datepicker().on('changeDate',function(){
            $('#end_date').datepicker('setStartDate', $('#start_date').val())
        });

		 $('#start_time,#start_date').change(function(){
		   if($('#start_date').val() == ''){
		      $("#start_date_validation").html("Please enter values");
		      stdate = true
		   } else {
		   	  $("#start_date_validation").html("");
		   }
		});
	    $('#end_time,#end_date').change(function(){
		   if($('#end_date').val() == ''){
		      $("#end_date_validation").html("Please enter values");
		          stdate = true
		   } else {
		   	  $("#end_date_validation").html("");
		   }
		});
		 this.isValidate = stdate
    },
    methods: {
      onSelectAgeCategory() {

        if(this.age_category_id == '') {
          this.getClubs()
          return this.getTeams()
        }
        let data = this.teams
        if(data.length == 0) {
          let vm =  this
          setTimeout(function(){
            vm.getClubs()
            vm.getTeams()
          },500)
          //this.getClubs()
          //var vm = this

          data = this.teams
          //data = this.teams
        }
        let age_category_id = this.age_category_id
        let ids = []
        let Cids = []
        //let ageCatId = this.
        var uniqueArray = data.filter(function(item, pos) {
            // Find that record which contains
            if(age_category_id == item['age_group_id']) {
              //ids.push(item)
              Cids.push(item['club_id'])
              ids.push(item['id'])
            }
        }, {});

        // First we have to check the clubs
       // console.log('Hello TeamClub')
        let arr = ''
        arr = Cids.join()
        let Teamarr = ''
        Teamarr = ids.join()

        if(arr == '' || Teamarr == '') {
          //console.log('trello')
          this.getClubs(0)
          //console.log('trello1234')
          this.getTeams(0)
        } else {
          this.getClubs(arr)
          this.getTeams(Teamarr)
        }


      },
      onSelectTeam() {
        if(this.team == '') {
          this.displayTournamentCompetationList()
          return this.getClubs()
        }
        let TournamentData={}
        let data = this.teams
        let ids = []
        let Cids = []
        let team =  this.team
        var uniqueArray = data.filter(function(item, pos) {
            // Find that record which contains
            if(team == item['id']) {
              ids.push(item['age_group_id'])
              Cids.push(item['club_id'])
            }
        }, {});
        this.displayTournamentCompetationList(ids.join())
        this.getClubs(Cids.join())
      },
    	onSelectClub() {

        let TournamentData={}
        if(this.club != '') {

         TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId,'clubId': this.club}
        } else {

          // if its blank no need to api Called call by default method for
           //TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId}
          this.displayTournamentCompetationList()
          return this.getTeams()
        }
				Tournament.getClubsTeams(TournamentData).then(
		          (response) => {
		           this.teams = response.data.data
               // here call for unique  age categoryList and calls it
               let ids = []
               var uniqueArray = response.data.data.filter(function(item, pos) {
                  if($.inArray(item['age_group_id'], ids) === -1) ids.push(item['age_group_id']);
                    //ids.push(item['age_group_id'])
               }, {});
               // Now here we pass the array as parameter
                this.displayTournamentCompetationList(ids.join())

		          },

		          (error) => {
		          }
		        )
    		},

    	displayTournamentCompetationList (cat_id='') {
      		// Only called if valid tournament id is Present
		    if (!isNaN(this.TournamentId)) {

            let TournamentData = {}
		      // here we add data for
          if(cat_id != '') {
           TournamentData = {'tournament_id': this.TournamentId, 'cat_id': cat_id}
        } else {
           TournamentData = {'tournament_id': this.TournamentId}
        }

		      Tournament.getCompetationFormat(TournamentData).then(
		      (response) => {
		        this.competationList = response.data.data
		        // console.log(this.competationList);
		      },
		      (error) => {
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	getTeams(team_id='') {

        var team_id = team_id.toString()

    		if (!isNaN(this.TournamentId)) {
		      // here we add data for

          let  TournamentData;
            if(team_id != '') {
              TournamentData = {'tournamentId': this.TournamentId,'team_id':team_id}
            } else {
              TournamentData = {'tournamentId': this.TournamentId}
            }
		      Tournament.getTeams(TournamentData).then(
		      (response) => {
		        this.teams = response.data.data
		        // console.log(this.competationList);
		      },
		      (error) => {
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
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},
    	getReferees() {
    		if (!isNaN(this.TournamentId)) {
		      // here we add data for
		      let TournamentData = {'tournamentId': this.TournamentId,'age_category':''}
		      Tournament.getReferees(TournamentData).then(
		      (response) => {
		        this.referees = response.data.referees
		        // console.log(this.competationList);
		      },
		      (error) => {
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},

	    getClubs(club_id='') {
          var club_id = club_id.toString()
	        if (!isNaN(this.TournamentId)) {
	          // here we add data for
            let  TournamentData;
            if(club_id != '') {
              TournamentData = {'tournament_id': this.TournamentId,'club_id':club_id}
            } else {
              TournamentData = {'tournament_id': this.TournamentId}
            }

	          Tournament.getAllClubs(TournamentData).then(
	          (response) => {
	            this.clubs = response.data.data
	          },
	          (error) => {
	          }
	          )
	        } else {
	          this.TournamentId = 0;
	        }
	    },
	    clearForm() {
         $('#frmReport')[0].reset()
          this.reports = []
          this.club = ''
          this.teams = {}
          this.team = ''
          $("#end_date_validation").html("");
          $("#start_date_validation").html("");
      },

      displayMatch(displayMatchNumber,displayHomeTeamPlaceholder,displayAwayTeamPlaceholder) {
      	return displayMatchNumber.replace('@HOME', displayHomeTeamPlaceholder).replace('@AWAY', displayAwayTeamPlaceholder)
      },

    	generateReport() {
    		let edata = $("#end_date_validation").html();
    		let sdata = $("#end_date_validation").html();

    		if(sdata !='' || edata != ''){
    			return false
    		}
    		if (!isNaN(this.TournamentId)) {
		      let ReportData = 'tournament_id='+this.TournamentId+'&'+$('#frmReport').serialize()+'&sort_by='+this.sortKey+'&sort_order='+this.sortBy
		     // let ReportData =  $('#frmReport').serializeArray()

		      this.reportQuery = ReportData
		      Tournament.getAllReportsData(ReportData).then(
		      (response) => {
		    //   	this.reports = _.remove(response.data.data, function(res) {
						//   if(res.competation_round_no == 'Round 1' && (res.HomeTeam == 0 || res.awayTeam == 0))  {
						//   	return false;
						//   } else {
						//   	return true;
						//   }
						// });
		      	this.reports = response.data.data

		      	this.reports.map(function(value, key) {
			              // if(value.actual_round == 'Elimination') {
		      		// console.log(value,value.displayHomeTeamPlaceholder);
		      				let dispTxt = '';
			              	if(value.displayHomeTeamPlaceholder.indexOf("#") == -1){
			              		
			              		if(value.displayMatchNumber.indexOf("wrs") > -1){
			              			dispTxt = 'wrs.' ;
			              		} else if(value.displayMatchNumber.indexOf("lrs") > -1) {
			              			dispTxt = 'lrs.' ;

			              		}value.displayHomeTeamPlaceholder = dispTxt+value.displayHomeTeamPlaceholder
			              	}
			              	
			                if(value.displayAwayTeamPlaceholder.indexOf("#") == -1){
			              		
			              		if(value.displayMatchNumber.indexOf("wrs") > -1){
			              			dispTxt = 'wrs.' ;
			              		} else if(value.displayMatchNumber.indexOf("lrs") > -1) {
			              			dispTxt = 'lrs.' ;

			              		}

			              		value.displayAwayTeamPlaceholder = dispTxt+value.displayAwayTeamPlaceholder
			              	}
			              	return value;
			              // }
			            })
		       },

		      (error) => {
		      }
		      )
		    } else {
		      this.TournamentId = 0;
		    }
    	},

    	exportReport() {
    		let ReportData = this.reportQuery
    		if(ReportData!=''){
				ReportData += '&report_download=yes'
    			window.location.href = "/tournament/report/reportExport?"+ReportData;
    		}else{
    			toastr['error']('Records not available', 'Error');
    		}
		},

    sortReport(filter) {
      if(this.reports && this.reports.length > 0) {
      let ReportData = this.reportQuery
      if (!isNaN(this.TournamentId)) {
          this.reverse = (this.sortKey == filter) ? ! this.reverse : false;
          this.sortKey = filter
          if(this.reverse == false) {
                this.sortBy = 'asc'
             } else {
                this.sortBy = 'desc'
             }
          let ReportData = 'tournament_id='+this.TournamentId+'&'+$('#frmReport').serialize()+'&sort_by='+filter+'&sort_order='+this.sortBy
         // let ReportData =  $('#frmReport').serializeArray()

          this.reportQuery = ReportData
          Tournament.getAllReportsData(ReportData).then(
          (response) => {
            this.reports = response.data.data
           },

          (error) => {
          }
          )
        }
      }
    },

	exportPrint() {
		let ReportData = this.reportQuery
		if(ReportData!=''){
			var win = window.open("/api/tournament/report/print?"+ReportData, '_blank');
      win.focus();
		}else{
			toastr['error']('Records not available', 'Error');
		}
	},
	getHoldingName(competitionActualName, placeholder,displayMatchNumber) {
      if(competitionActualName.indexOf('Group') !== -1){
      	let dispTxt = '';
          // if(placeholder.indexOf("#") == -1){
            
          //   if(displayMatchNumber.indexOf("wrs") > -1){
          //     dispTxt = 'wrs.' ;
          //   } else if(displayMatchNumber.indexOf("lrs") > -1) {
          //     dispTxt = 'lrs.' ;

          //   }
          //   placeholder = dispTxt+placeholder
          // }
                  
        return placeholder;
      } else if(competitionActualName.indexOf('Pos') !== -1){
        return 'Pos-' + placeholder;
      }
    }
  }
}

</script>
