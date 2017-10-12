<template>
  <div class="modal" id="tournament_permission_modal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form name="frmTournamentPermission" id="frmTournamentPermission" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">{{$lang.user_management_tournament_permission_title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-sm-6" v-for="tournament in allTournaments">
                    <div class="checkbox">
                      <div class="c-input">
                          <input type="checkbox" v-bind:id="tournament.id" class="euro-checkbox" v-bind:value="tournament.id" v-model="formValues.tournaments" />
                          <label v-bind:for="tournament.id">{{ tournament.name }} ({{tournament.status}})</label>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
                <button type="button" class="btn btn-primary" @click="submitTournamentPermission()">{{$lang.user_management_user_save}}</button>
            </div> 
        </form>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import User from '../api/users.js'
import Tournament from '../api/tournament.js'
import { ErrorBag } from 'vee-validate';
    export default {
        data() {
            return {
                allTournaments: [],
                formValues: {
                  tournaments: [],
                },
            }
        },
        created() {
          this.$root.$on('getUserTournaments', this.getUserTournaments);
        },
        mounted(){
          Tournament.getAllTournaments().then(
            (response) => {
              this.allTournaments = response.data.data
            },
            (error) => {
            }
          )
        },
        props:['user'],
        methods: {
            submitTournamentPermission() {
              let data = { user: this.user, tournaments: this.formValues.tournaments}
              User.changeTournamentPermission(data).then(
                (response)=> {
                  toastr.success('Tournament permissions has been updated successfully.', 'Tournament permissions', {timeOut: 5000});
                  $("#tournament_permission_modal").modal("hide");
                  this.formValues.tournaments = [];
                },
                (error)=>{
                }

              )
            },
            getUserTournaments(user) {
              if(user) {
                this.formValues.tournaments = [];
                User.getUserTournaments(user.id).then(
                  (response)=> {
                    this.formValues.tournaments = response.data
                  },
                  (error)=>{
                  }

                )
              }
            },
        }
    }
</script>
