<template>
    <div class="modal fade bg-modal-color refdel" id="manual_ranking_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Manual Ranking</h5>
            <div class="d-flex align-items-center">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <div class="modal-body" id="pitch_model_body">
          <form method="delete" class="js-delete-modal-form">
            
            <!-- <div class="modal-header"> -->
            <div class="form-group row">
              <div class="col-sm-12 d-flex align-items-start">
                <div>
                  <div class="checkbox">
                    <div class="c-input">
                        <input type="checkbox" id="is_competition_manual_override_standing" class="euro-checkbox" name="chkposition" v-model="is_competition_manual_override_standing" :true-value="1" :false-value="0" />
                        <label for="is_competition_manual_override_standing">Do you wish to manually override the final standings of the group? If so please tick the button and select the ranking of the teams below.</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" :class="{'has-error': errors.has('manual_teams') }" v-if="is_competition_manual_override_standing" v-for="n in teamCount">
              <div class="col-sm-3 form-control-label">Position {{n}}*</div>
              <div class="col-sm-9">
                <select class="form-control ls-select2 col-sm-4" :name="`manual_teams${n}`" id="manual_teams" v-model="teamDetails[n-1]" v-validate="'required'" :class="{'is-danger': errors.has('manual_teams') }">
                  <option value="">Select team</option>
                  <option :value="team.id" v-for="team in teams" v-if="teamDetails[n-1] == team.id || !teamDetails.includes(team.id)">{{ team.name }}</option>
                </select>
                <span class="help is-danger" v-show="errors.has(`manual_teams${n}`)">{{$lang.manual_ranking_team_required}}</span>
              </div>
            </div>
               
            <!-- </div> -->
            <!-- <div class="modal-body js-delete-confirmation-msg">{{ deleteConfirmMsg }}</div> -->
            
            </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
              <button type="submit" class="btn btn-primary" @click.prevent="saveStandingsManually()">{{$lang.manual_ranking_save}}</button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import Tournament from '../api/tournament.js'
import _ from 'lodash';
    export default  {
        props: ['competitionId', 'teamList', 'isManualOverrideStanding', 'teamCount'],
        data() {
          return {
            redeleteConfirmMsg: '',
            standingData:[],
            currentLCompetationId: this.competitionId,
            teamDetails: [],
            is_competition_manual_override_standing: this.isManualOverrideStanding,
            teams: {},
          } 
        },
        created: function() {
        },
        mounted() {
          this.$root.$on('getStandingDataForManualRanking', this.getStandingData);
        },
        methods: {
            getStandingData(currentLCompetationId) {
              if(currentLCompetationId != 0) {
                let vm = this;
                this.is_competition_manual_override_standing = this.isManualOverrideStanding;
                this.sortTeams();

                let TournamentId = this.$store.state.Tournament.tournamentId
                let tournamentData = {'tournamentId': TournamentId,
                   'competitionId':currentLCompetationId }

                Tournament.getStanding(tournamentData).then(
                  (response)=> {
                    if(response.data.status_code == 200) {
                      vm.standingData = response.data.data
                      vm.teamDetails = _.map(response.data.data, (o) => {
                        if(o.manual_order == null) {
                          return '';
                        } else {
                          return o.id;
                        }
                      });
                      let standingTeamLength = response.data.data.length;
                      for(let i = standingTeamLength; i < vm.teamCount; i++) {
                        vm.teamDetails[i]  = '';
                      }
                      // here we add extra Field Fot Not Displat Location
                    }
                  },
                  (error) => {
                    alert('Error in Getting Standing Data')
                  }
                )
              }

            },
            saveStandingsManually() {
              let vm = this;
              this.$validator.validateAll().then(() => {
                this.closeModal();
                $("body .js-loader").removeClass('d-none');
                let data = {'competitionId' : this.competitionId, 'teamDetails': this.teamDetails, 'tournament_id':this.$store.state.Tournament.tournamentId, 'isManualOverrideStanding': this.is_competition_manual_override_standing == 1 ? true : false}
                vm.$emit('competitionAsManualStanding', this.is_competition_manual_override_standing)
                Tournament.saveStandingsManually(data).then((response) => {
                  $("body .js-loader").addClass('d-none');
                  if(this.is_competition_manual_override_standing == 0) {
                    this.teamDetails = _.cloneDeep(_.map(vm.standingData, (o) => {
                      return '';
                    }));
                  }
                  vm.$emit('refreshStanding', this.competitionId)
                  vm.$root.$emit('setStandingData', this.competitionId)
                  toastr.success(response.data.message, 'Manual Ranking Updated', {timeOut: 5000});
                });
              }).catch(() => {
                  //toastr['error']('Please complete all required fields.', 'Error')
              });
            },
            closeModal() {
                $('#manual_ranking_modal').modal('hide')
                return false
            },
            sortTeams() {
              let teamList = _.sortBy(_.cloneDeep(this.teamList), (o) => { return _.lowerCase(o.name) });
              this.teams = _.map(teamList, (o) => { return { id: o.id,  name: o.name }; });
            }
        }
    }
</script>
