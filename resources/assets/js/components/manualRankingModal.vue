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
            <h5 class="modal-title" id="myModalLabel">
              Do you wish to manually override the final standings of the group? If so please tick the button and select the ranking of the teams below
              <input type="checkbox" name="chkposition" v-model="chkposition">
            </h5>

            <div class="row" v-if="chkposition" v-for="n in teams.length">
              <div class="col-md-3">Position {{n}}</div>
              <div class="col-md-6">
                <select class="form-control ls-select2 col-sm-4">
                  <option value="">Select Team</option>
                  <option :value="team.id" v-for="team in teams" @change="assignTeamPosition()">{{team.name}}</option>
                </select>
              </div>
              <div class="col-md-3"></div>
                
            </div>
               
            <!-- </div> -->
            <!-- <div class="modal-body js-delete-confirmation-msg">{{ deleteConfirmMsg }}</div> -->
            
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger"  @click="hideModal()">{{$lang.user_management_cancel}}</button>
                  <button type="submit" class="btn btn-primary" @click.prevent="confirmDelete()">{{$lang.user_management_save}}</button>
              </div>
              <input name="_method" value="DELETE" type="hidden" />
            </form>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import Tournament from '../api/tournament.js'
    export default  {

        props: ['competitionId'],
        data() {
          return {
            redeleteConfirmMsg: '',
            teams: [],
            chkposition: false
          }
        },
        mounted() {
          let vm =this
          setTimeout(function(){
            Tournament.getAllTeamsFromCompetitionId({'competitionId':vm.competitionId}).then(
            (response) => {
              vm.teams = response.data.data
              vm.teams = _.orderBy(vm.teams, ['name'], ['asc']);
            })
          },200)
          
        },
        methods: {
            confirmDelete() {
              this.$emit('confirmed');
            },
            hideModal() {
                $('#delete_modal').modal('hide')
                return false
            },
            closeModal() {
                $('#delete_modal').modal('hide')
                return false
              
            }
        }
    }
</script>
