<template>
<div class="row">
	<div class="col-md-12" >
	<table id="matchSchedule" class="table table-hover table-bordered" v-if="matchData.length > 0">
		<thead>
			<th class="text-center">{{$lang.summary_schedule_date_time}}</th>
			<th class="text-center">{{$lang.summary_schedule_matches_draw}}</th>
			<th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
			<th class="text-center">{{$lang.summary_schedule_matches_score}}</th>
			<th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
			<th class="text-center" v-if="isHideLocation !=  false">{{$lang.summary_schedule_matches_location}}</th>
		</thead>
		<tbody>
			<tr v-for="match in matchData">
				<td class="text-center">{{match.match_datetime | formatDate}}</td>
				<td class="text-center">
					<a class="pull-left text-left text-primary" href=""
					@click.prevent="changeDrawDetails(match)"><u>{{match.competation_name}}</u></a>
				</td>
				<td align="right">
					<a  class="text-center text-primary" href="" @click.prevent="changeTeam(match.Home_id, match.HomeTeam)">
						<span><u>{{match.HomeTeam}}</u></span>
						<img :src="match.HomeFlagLogo" width="20">
					</a>
				</td>
				<td class="text-center">
        		  <input type="text" :name="'home_score['+match.fid+']'" :value="match.homeScore" style="width: 40px; text-align: center;"  v-if="isUserDataExist" @change="updateScore(match.fid)"><span v-else>{{match.homeScore}}</span> -	
        		  <input type="text" :name="'away_score['+match.fid+']'" :value="match.AwayScore" style="width: 40px; text-align: center;"  v-if="isUserDataExist" 
        		  @change="updateScore(match.fid)"><span v-else>{{match.AwayScore}}</span>
      		    </td>
				<td align="left">
					<a class="pull-left text-left text-primary"  href="" @click.prevent="changeTeam(match.Away_id, match.AwayTeam)">
						<img :src="match.AwayFlagLogo" width="20">
						<span><u>{{match.AwayTeam}}</u></span>
					</a>
				</td>

				<td v-if="isHideLocation !=  false"><a class="pull-left text-left text-primary" href="" @click.prevent="changeLocation(match)"
				><u>{{match.venue_name}} - {{match.pitch_number}}</u></a></td>
			</tr>
		</tbody>
	</table>
	<span v-else>No information available</span>
	</div>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
	props: ['matchData'],
	data() {
		return {
			dispLocation: true
		}
	},

  filters: {
    formatDate: function(date) {
     return moment(date).format("hh:mm ddd DD MMM YYYY");
    }
  },
	computed: {
		isHideLocation() {
			if(this.$store.state.currentScheduleView == 'locationList' ||
				this.$store.state.currentScheduleView == 'teamDetails'){
				this.dispLocation = false
				return this.dispLocation
			}
		},
		isUserDataExist() {
	      return this.$store.state.Users.userDetails.id
	    }
	},
	mounted() {
		$('body').on('keypress', 'input',function(e) {
		    var a = [];
		    var k = e.which;
		    var i;
		    for (i = 48; i < 58; i++)
		        a.push(i);
		    if (!(a.indexOf(k)>=0)) {
            e.preventDefault();
        }
        let val = e.target.value

        if(e.target.value.length > 2) {
          e.preventDefault();
        }

		});
	},
	methods: {
		changeLocation(matchData) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','locationList')
			this.$root.$emit('changeComp',matchData);
			//this.$store.dispatch('setCurrentScheduleView','locationList')
		},
		changeTeam(Id, Name) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			this.$root.$emit('changeComp', Id, Name);
		},
		changeDrawDetails(competition) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			let Id = competition.competitionId
			let Name = competition.group_name+'-'+competition.competation_name
			this.$root.$emit('changeComp', Id, Name);
			//this.$emit('changeComp',Id);
		},
		updateScore(matchId) {

      let matchData = {'matchId': matchId, 'home_score':$('input[name="home_score['+matchId+']"]').val(), 'away_score':$('input[name="away_score['+matchId+']"]').val()}
        Tournament.updateScore(matchData).then(
            (response) => {
              toastr.success('Score has been updated successfully', 'Score Updated', {timeOut: 5000});
        })
    },

	},

}
</script>
