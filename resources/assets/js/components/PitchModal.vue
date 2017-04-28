<template>
  <div class="modal fade" id="matchScheduleModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Match Details</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <div>
              <p>Match number: {{matchFixture.title}}</p>
              <p>Team 1 {{matchDetail.home_team}} v Team 2 {{matchDetail.away_team}} </p>
              <p>Date: {{matchDetail.matchTime}} </p>
              <p>Pitch: {{matchFixture.resourceId}} (Location 2)</p>
            </div>
            <p class="mt-4 refree_name">
            <div v-if="matchDetail.referee">
              <div class="form-group row">
                  <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                    <strong>Referee:</strong>
                  </label>
                  <div class="col-sm-6 align-self-center">
                      <input class="form-control" type="text" v-model="matchDetail.referee.first_name" readonly>
                  </div>
                  <div class="col-sm-3 align-self-center">
                      <a class="btn btn-danger btn-sm w-100" href="javascript:void(0)" @click="removeReferee()">Remove</a>
                  </div>
              </div>
            </div>
            <div class="row" v-else>
            <label class="col-sm-3 form-control-label"><strong>Referee:</strong></label>
              <div class="col-sm-9">
                <select  v-model="matchDetail.referee_id" class="form-control ls-select2" name="selReferee">
                  <option value="0">Select referee</option>
                  <option :value="referee.id" v-for="referee in referees">{{referee.first_name}}</option>
                </select>
              </div>
            </div>
              
            </p>
            <form class="mt-4">
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><Strong>Team 1 {{matchDetail.home_team}}</Strong></label>
                <div class="col-sm-2">
                  <input type="number" name="home_team_score" :value="matchDetail.hometeam_score" id="home_team_score" class="form-control" >
                </div>
                <label class="col-sm-4 form-control-label"><Strong>Team 2 {{matchDetail.away_team}}</Strong></label>
                <div class="col-sm-2">
                  <input type="number" name="away_team_score" :value="matchDetail.awayteam_score" id="away_team_score" class="form-control" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><strong>Status</strong></label>
                <div class="col-sm-9">
                  <select v-model="matchDetail.match_status" name="match_status" id="match_status" class="form-control ls-select2">
                      <option value="Full-time">Full-time</option>
                      <option value="Penalties">Penalties</option>
                      <option value="Walk-over">Walk-over</option>
                      <option value="Abandoned">Abandoned</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><strong>Winner</strong></label>
                <div class="col-sm-9">
                  <select name="match_winner"  v-model="matchDetail.match_winner"  id="match_winner" class="form-control ls-select2">
                      <option value="">Please Select</option>
                      <option :value="matchDetail.home_team">{{matchDetail.home_team}}</option>
                      <option :value="matchDetail.away_team">{{matchDetail.away_team}}</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label"><strong>Comments</strong></label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="comments" id="comments">{{matchDetail.comments}}</textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
              <div class="">
                <button type="button" class="btn btn-danger pull-left" @click="matchUnschedule()" >Unschedule</button>
              </div>
              <div class="">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" @click="saveFixtureDetail()">Save</button>
              </div>
          </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

var moment = require('moment');
  
  export default {
    data() {
       return {         
         'tournamentId': this.$store.state.Tournament.tournamentId,
         'matchDetail':{},
         'referees': {},
         'matchId': this.matchFixture.id ? this.matchFixture.id : this.matchFixture.matchId
       }
    },
    props: ['matchFixture'],
    mounted() {
       Tournament.getReferees(this.tournamentId).then(
        (response) => {
            this.referees = response.data.referees
        })
      this.matchFixtureDetail()   


    console.log('PtichModal is mounted')
  },
  methods: {
    initialState() {
      return {
        "homeTeam":1,
      }
    },
    matchFixtureDetail(){
      Tournament.getMatchFixtureDetail(this.matchId).then(
        (response) => {
          this.matchDetail = response.data.data
          this.matchDetail.matchTime = moment(response.data.data.match_datetime,'DD-MM-YYYY hh:mm"ss').format('DD.MM.YYYY (hh:mm a)')
      })
    },
    removeReferee(){
      Tournament.removeAssignedReferee(this.matchDetail.id).then(
        (response) => {
          this.matchFixtureDetail()
          toastr.success('Referee has been removed successfully', 'Referee removed', {timeOut: 5000});
        }
        )
    },
    saveFixtureDetail(){
      let data = {'matchId':this.matchDetail.id,'refereeId': this.matchDetail.referee_id,'homeTeamScore':$('#home_team_score').val(),'awayTeamScore':$('#away_team_score').val(),'matchStatus':$('#match_status').val(),'matchWinner':$('#match_winner').val(),'comments':$('#comments').val()}
        Tournament.saveMatchResult(data).then(
          (response) => {
            // this.matchFixtureDetail()
            $('#matchScheduleModal').modal('hide')
            toastr.success('Referee has been assigned successfully', 'Referee assigned', {timeOut: 5000});
          }
        )
    },
    assignReferee(refereeId){
      let data = {'refereeId': refereeId,'matchId': this.matchId}
      Tournament.assignReferee(data).then(
        (response) => {
          // this.matchFixtureDetail()
          toastr.success('Referee has been assigned successfully', 'Referee assigned', {timeOut: 5000});
        }
        )
    },
    matchUnschedule() {
      Tournament.matchUnschedule(this.matchId).then(
        (response) => {
           $('#matchScheduleModal').modal('hide')
          toastr.success('Match has been unscheduled successfully', 'Match Unscheduled', {timeOut: 5000});
      })
    }
    
  }
}


</script>