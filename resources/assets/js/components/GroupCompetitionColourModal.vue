<template>
    <div class="modal fade bg-modal-color refdel" id="group_competition_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <div class="d-flex align-items-center">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <div class="modal-body" id="pitch_model_body">
          <!-- <form method="delete" class="js-delete-modal-form">
            
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
            <div class="form-group row" :class="{'has-error': errors.has('manual_teams') }" v-if="is_competition_manual_override_standing" v-for="n in teams.length">
              <div class="col-sm-3 form-control-label">Position {{n}}*</div>
              <div class="col-sm-9">
                <select class="form-control ls-select2 col-sm-4" :name="`manual_teams${n}`" id="manual_teams" v-model="teamDetails[n-1]" v-validate="'required'" :class="{'is-danger': errors.has('manual_teams') }">
                  <option value="">Select team</option>
                  <option :value="team.id" v-for="team in teams" v-if="teamDetails[n-1] == team.id || !teamDetails.includes(team.id)">{{ team.name }}</option>
                </select>
                <span class="help is-danger" v-show="errors.has(`manual_teams${n}`)">{{$lang.manual_ranking_team_required}}</span>
              </div>
            </div>
            
            </form> -->
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
              <button type="submit" class="btn btn-primary" @click.prevent="saveCompetitionColour()">{{$lang.manual_ranking_save}}</button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import Tournament from '../api/tournament.js'
import _ from 'lodash';
    export default  {
        props: [],
        data() {
          return {

          }
        },
        created: function() {
        },
        mounted() {
          this.$root.$on('getCategoryCompetitions', this.getCategoryCompetitions);
        },
        methods: {
            getCategoryCompetitions() {  
              let filterValue = this.$store.state.Tournament.tournamentFiler.filterValue
              let data = {'ageGroupId': filterValue.id, 'competationType': 'Round Robin', 'competationRoundNo': 'Round 1' }

              Tournament.getCategoryCompetitions(data).then(
                  (response)=> {
                    if(response.data.status_code == 200) {
                      
                    }
                  },
                  (error) => {
                    alert('Error in getting category competitions')
                  }
                )
            },
            closeModal() {
                $('#group_competition_modal').modal('hide')
                return false
            },
            saveCompetitionColour() {

            },
        }
    }
</script>
