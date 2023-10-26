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
                    <ul role="tablist" class="nav nav-tabs d-flex justify-content-between">
                        <li class="nav-item active">
                            <a data-toggle="tab" role="tab" href="#tournament-list" class="text-center nav-link" id="tournamentTab"><div class="wrapper-tab">{{$lang.user_management_permission_tournament_tab}}</div></a>
                        </li>
                         <li>
                             <select id="yeartou" class="form-control ls-select2 col-sm-10 offset-sm-1 mb-3" v-model="year" v-on:change="filterTournaments">
                                <option value="">{{$lang.tournament_select_year}}</option>
                                <template v-for="yearList in years" v-bind:value="yearList">
                                <option :value="yearList">{{yearList}}</option>
                                </template>
                              </select>
                         </li> 
                    </ul>

                  
                    <div class="tab-content">
                        <div class="tab-pane" id="tournament-list" role="tab-pane">
                          <tournament-listing :allTournaments="allTournaments" @setSelectedTournaments="setSelectedTournaments"></tournament-listing>
                          <div class="form-group row" v-if="selectTournamentError">
                            <div class="col-sm-12">
                              <p class="text-danger mb-0">Please select at least one tournament.</p>
                            </div>
                          </div>
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

    import TournamentListing from './TournamentListing.vue'
    import { ErrorBag } from 'vee-validate';

    export default {
        components: {
            TournamentListing,
        },
        data() {
          return {
            currentView: 'tournament',
            tournament: '',
		        selected: null,
		        value: '',
		        options: [],
            year : '',
            years:[],
            allOptions:[],
            allTournaments: [],
            formValues: {
              tournaments: []
            },
            selectTournamentError: false,
          }
        },
        props:['user', 'isCompulsoryTournamentSelection'],
        created() {
           this.$root.$on('getAllTournaments', this.getAllTournaments);
        },
        beforeMount() {
        },
        mounted() {
        
          Tournament.getAllTournaments().then(
            (response) => {
              this.allTournaments = response.data.data
              this.options = response.data.data;
            },
            (error) => {
            }
          ),
          Tournament.getAllTournamentsYears().then(
          (response) => {
            this.years = response.data.data;
                console.log(response.data.data);
                $("body .js-loader").addClass('d-none');
                  
                  },
                  (error) => {
                  }
          )
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
          filterTournaments() {
            //console.log(this.year);
              if(this.year == '') {
                this.allTournaments = this.options;
              } else {

                let data = this.options;
                var filterData = [];
                for(var i in data) {
                  if(data[i].start_date.includes(this.year))
                  {
                   // console.log(data[i]);
                    filterData.push(data[i]);
                  }
                }
                this.allTournaments = filterData;              
              
              }

            },
          submitPermissions() {
            let vm = this;

            this.formValues.tournaments = [];

            this.$root.$emit('getSelectedTournaments');

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

                let data = { user: vm.user, tournaments: vm.formValues.tournaments}
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
          }
        }
    }
</script>