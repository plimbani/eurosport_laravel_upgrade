<template>
  <div class="modal" id="matchScheduleModal" tabindex="-1" role="dialog" aria-labelledby="refreesModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Match Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="form-group row mb-0">
              <label class="col-sm-3">Match number</label><p class="col-sm-9"> {{matchFixture.title}}</p>
              <label class="col-sm-3"></label><p class="col-sm-9">Team 1 ({{matchDetail.home_team_name}}) and Team 2 ({{matchDetail.away_team_name}}) </p>
              <label class="col-sm-3">Date</label><p class="col-sm-9">{{matchDetail.matchTime}}</p>
              <label class="col-sm-3">Pitch</label><p class="col-sm-9" v-if="matchDetail.pitch.pitch_number">{{matchDetail.pitch.pitch_number}}</p>
            </div>
            <p class="mt-0 refree_name">
            <div v-if="matchDetail.referee">
              <div class="form-group row">
                  <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                    Referee
                  </label>
                  <div class="col-sm-6 align-self-center">
                      <input class="form-control mr-sm-2" type="text"
                      v-model="matchDetail.referee.first_name"
                      readonly>

                  </div>
                  <div class="col-sm-3 align-self-center">
                      <a class="btn btn-danger w-100" href="javascript:void(0)" @click="removeReferee()">Remove</a>
                  </div>
              </div>
            </div>
            <div class="row" v-else>
            <label class="col-sm-3 form-control-label">Referee</label>
              <div class="col-sm-9">
                <select  v-model="matchDetail.referee_id" class="form-control ls-select2" name="selReferee">
                  <option value="0">Select referee</option>
                  <option :value="referee.id" v-for="referee in referees">{{referee.first_name}}</option>
                </select>
              </div>
            </div>

            </p>

            <form>
              <div class="form-group row">
                <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                  Result
                </label>
                <div class="col-sm-6 align-self-center">
                  Team 1 ({{matchDetail.home_team_name}})
                </div>
                <div class="col-sm-3 align-self-center">
                  <input type="number" min="0" name="home_team_score" :value="matchDetail.hometeam_score" id="home_team_score" class="form-control">
                </div>
                <label class="col-sm-3 col-sm-3 form-control-label align-self-center">
                  &nbsp;
                </label>
                <div class="col-sm-6 align-self-center">
                  Team 2 ({{matchDetail.away_team_name}})
                </div>
                <div class="col-sm-3 align-self-center">
                  <input type="number" min="0" name="away_team_score" :value="matchDetail.awayteam_score" id="away_team_score" class="form-control">
                </div>
              </div>
              <div class="form-group row">

                <label class="col-sm-3 form-control-label">Status</label>
                <div class="col-sm-9">
                  <select v-model="matchDetail.match_status" name="match_status" id="match_status" class="form-control ls-select2">
                      <option value="0">Please select</option>

                      <option value="Full-time">Full-time</option>
                      <option value="Penalties">Penalties</option>
                      <option value="Walk-over">Walk-over</option>
                      <option value="Abandoned">Abandoned</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">

                <label class="col-sm-3 form-control-label">Winner</label>
                <div class="col-sm-9">
                  <select name="match_winner" v-model="matchDetail.match_winner"  id="match_winner" class="form-control ls-select2">
                      <option value="0">Please select</option>
                      <option :value="matchDetail.home_team">Team 1 ({{matchDetail.home_team_name}})</option>
                      <option :value="matchDetail.away_team">Team 2 ({{matchDetail.away_team_name}})</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label">Comments</label>
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
          console.log(this.matchDetail)
          if(this.matchDetail.referee == null) {

          } else {
          this.matchDetail.referee.first_name = this.matchDetail.referee.first_name+', '+this.matchDetail.referee.last_name
          }
         // this.matchDetail.matchTime = moment(response.data.data.match_datetime,' hh:mm"ss DD-MMM-YYYY ').format(' kk:mm DD MMM  YYYY ')

          let date = moment(response.data.data.match_datetime,'YYYY-MM-DD hh:mm:ss')
          this.matchDetail.matchTime = date.format('hh:mm ddd DD MMM YYYY')
          this.matchDetail.match_winner =  (this.matchDetail.match_winner == null) ? '0': this.matchDetail.match_winner
          // Set Some Values
          this.matchDetail.match_status = (this.matchDetail.match_status == null || this.matchDetail.match_status == '') ? '0' : this.matchDetail.match_status

          this.matchDetail.hometeam_score = (this.matchDetail.hometeam_score == null) ? '0' : this.matchDetail.hometeam_score
          this.matchDetail.awayteam_score = (this.matchDetail.awayteam_score == null) ? '0' : this.matchDetail.awayteam_score
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
            this.$root.$emit('setPitchReset')
            $('#matchScheduleModal').modal('hide')
            toastr.success('This match has been updated.', 'Match Details', {timeOut: 5000});
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
      let vm =this
      Tournament.matchUnschedule(this.matchId).then(
        (response) => {
          vm.$root.$emit('setPitchReset')
           $('#matchScheduleModal').modal('hide')
          toastr.success('Match has been unscheduled successfully', 'Match Unscheduled', {timeOut: 5000});
      })
    }

  }
}


</script>
