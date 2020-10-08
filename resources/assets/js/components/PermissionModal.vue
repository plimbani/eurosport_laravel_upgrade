<template>
  <div class="modal" id="permission_modal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form name="frmTournamentPermission" id="frmTournamentPermission" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" v-if="user != null">{{$lang.user_management_permission_title}}  - {{ user.first_name }} {{ user.last_name}} ({{ user.email }})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-if="!isCompulsoryTournamentSelection">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tabs tabs-primary">
                    <ul role="tablist" class="nav nav-tabs">
                        <li class="nav-item active" v-if="isPermisionModalActive">
                            <a data-toggle="tab" role="tab" href="#website-list" class="text-center nav-link"><div class="wrapper-tab">{{$lang.user_management_permission_website_tab}}</div></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="website-list" role="tabpanel">
                            <website-listing :allWebsites="allWebsites" @setSelectedWebsites="setSelectedWebsites"></website-listing>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button v-if="!isCompulsoryTournamentSelection" type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.user_management_user_cancle}}</button>
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

    import WebsiteListing from './WebsiteListing.vue'
    import { ErrorBag } from 'vee-validate';

    export default {
        components: {
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
            },
            selectTournamentError: false,
          }
        },
        props:['user', 'isCompulsoryTournamentSelection'],
        created() {
        },
        beforeMount() {
        },
        mounted() {
          Tournament.getAllTournaments().then(
            (response) => {
              this.allTournaments = response.data.data
            },
            (error) => {
            }
          )

          if(this.$store.state.Users.userDetails.role_slug != 'tournament.administrator') {
            Website.getAllWebsites().then(
              (response) => {
                this.allWebsites = response.data.data
              },
              (error) => {
              }
            )
          }

        },
        computed: {
          userDetails() {
            return this.$store.state.Users.userDetails;
          },
          isPermisionModalActive() {
            if(this.user) {
              if(this.user.role_slug == "Results.administrator" || this.userDetails.role_slug == "tournament.administrator") {
                return false;
              }
            }
             return true;
          }
        },        
        methods: {
          submitPermissions() {
            let vm = this;

            this.formValues.tournaments = [];
            this.formValues.websites = [];

            this.$root.$emit('getSelectedTournaments');
            this.$root.$emit('getSelectedWebsites');

            // usage as a promise (2.1.0+, see note below)
            Vue.nextTick()
              .then(function () {
                let selectedTournaments = _.cloneDeep(vm.formValues.tournaments);
                let adminTournaments = _.map(_.cloneDeep(vm.allTournaments), 'id');
                let intersectCount = adminTournaments.filter(x => selectedTournaments.includes(x));

                vm.selectTournamentError = false;
                if(vm.isCompulsoryTournamentSelection && intersectCount.length === 0) {
                  vm.selectTournamentError = true;
                  return false;
                }

                let data = { user: vm.user, tournaments: vm.formValues.tournaments, websites: vm.formValues.websites}
                $("body .js-loader").removeClass('d-none');
                User.changePermissions(data).then(
                  (response)=> {
                    toastr.success('Permissions have been updated successfully.', 'Permissions', {timeOut: 5000});
                    $("body .js-loader").addClass('d-none');
                    $("#permission_modal").modal("hide");
                    vm.$root.$emit('getResults');
                    vm.formValues.tournaments = [];
                  },
                  (error)=>{
                  }

                )
              });
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