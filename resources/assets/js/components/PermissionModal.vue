<template>
  <div class="modal" id="permission_modal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form name="frmTournamentPermission" id="frmTournamentPermission" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">{{$lang.user_management_permission_title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tabs tabs-primary">
                    <ul role="tablist" class="nav nav-tabs">
                        <li class="nav-item active">
                            <a data-toggle="tab" role="tab" href="#tournament-list" class="text-center nav-link px-3" id="tournamentTab">Tournament</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" role="tab" href="#website-list" class="text-center nav-link px-3">Website</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tournament-list" role="tab-pane">
                            <tournament-listing :allTournaments="allTournaments" @setSelectedTournaments="setSelectedTournaments"></tournament-listing>
                        </div>
                        <div class="tab-pane" id="website-list" role="tabpanel">
                            <website-listing :allWebsites="allWebsites" @setSelectedWebsites="setSelectedWebsites"></website-listing>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
                <button type="button" class="btn btn-primary" @click="submitPermissions()">{{$lang.user_management_user_save}}</button>
            </div> 
        </form>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">

    import User from '../api/users.js'
    import Tournament from '../api/tournament.js'
    import Website from '../api/website.js'

    import TournamentListing from './TournamentListing.vue'
    import WebsiteListing from './WebsiteListing.vue'
    import { ErrorBag } from 'vee-validate';

    export default {
        components: {
            TournamentListing,
            WebsiteListing,
        },
        data() {
          return {
            currentView: 'tournament',
            allTournaments: [],
            allWebsites: [],
            formValues: {
              tournaments: [],
              websites: []
            }
          }
        },
        props:['user'],
        created() {
          // console.log("This is created")
        },
        beforeMount() {
          // console.log("This is before mounted")
        },
        mounted() {
          // console.log("This is mounted")
          Tournament.getAllTournaments().then(
            (response) => {
              this.allTournaments = response.data.data
            },
            (error) => {
            }
          )

          Website.getAllWebsites().then(
            (response) => {
              this.allWebsites = response.data.data
            },
            (error) => {
            }
          )

        },
        methods: {
          submitPermissions() {
            this.$root.$emit('getSelectedTournaments');
            this.$root.$emit('getSelectedWebsites');

            let data = { user: this.user, tournaments: this.formValues.tournaments, websites: this.formValues.websites}

            User.changePermissions(data).then(
              (response)=> {
                toastr.success('Tournament permissions has been updated successfully.', 'Tournament permissions', {timeOut: 5000});
                $("#tournament_permission_modal").modal("hide");
                this.formValues.tournaments = [];
              },
              (error)=>{
              }

            )

          },
          setSelectedTournaments(tournaments) {
            this.formValues.tournaments = tournaments;
          },
          setSelectedWebsites(websites) {
            this.formValues.websites = websites;
          }
        }
    }
</script>