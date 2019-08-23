<template> 
    <div> 
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center mb-3 ">
                      <div class="col-md-5">
                            <p class="mb-0" v-if="currentLayout == 'commercialisation'">{{$lang.user_view_and_edit_tournaments_associated}}</p>
                            <p class="mb-0" v-else>{{$lang.user_management_all_users_sentence}}</p>
                      </div>
                      <div class="col-md-7">
                        <div class="row align-items-center justify-content-end">
                          <div class="col-12">
                            <div class="row">
                              <div class="col-md-5">
                                <!-- UsersTournament -->
                              </div>
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row d-flex flex-row align-items-center">
                        <div class="col-md-12">
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Tournament</th>
                                        <th>Custom</th>
                                        <th>Teams</th>
                                        <th>Purchase date</th>
                                        <th>Edit</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="tournament in usersTourmanents">
                                    <td v-on:click="redirectToTournamentDetailPage(tournament)">
                                    <a href="javascript:void(0)" class="text-primary"><u>{{ tournament.name }}</u></a></td>
                                    <td>{{ tournament.start_date }}</td>
                                    <td>{{ tournament.end_date }}</td>
                                    <td>{{ getTournamentType(tournament.tournament_type) }}</td>
                                    <td>{{ getTournamentFormat(tournament.custom_tournament_format) }}</td>
                                    <td>{{ tournament.maximum_teams }}</td>
                                    <!-- <td>TEA</td> -->
                                    <td v-on:click="redirectToTransactionListPage(tournament)" >
                                    <a href="javascript:void(0)" class="text-primary"><u>{{ tournament.created_at }}</u></a></td>
                                    <td><a class="text-primary" href="javascript:void(0);"  v-on:click="redirectToTournamentDetailPage(tournament)" title="Edit"><i class="fas fa-pencil"></i></a></td>                                    
                                  </tr>
                                  <tr><td colspan="8"></td></tr>
                                </tbody>
                            </table>
                            
                        </div>
                        <div v-if="usersTourmanents.length == 0" class="col-md-12">
                            <h6 class="block text-center">No record found</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
<script type="text/babel">
    
    import Constant from '../../services/constant'
    export default {
        data() {
            return {
                usersTourmanents:[],
                customer_id:0, // currently static
                currentLayout: this.$store.state.Configuration.currentLayout,
            }
        },
        methods: {
            getTournamentListOfUser(){
                let params = {
                    customer_id:this.customer_id, // currently static
                }
                
                axios.post(Constant.apiBaseUrl+'customer-tournament',params).then(response =>  {  
                     this.usersTourmanents = response.data.data;
                }).catch(error => {
                    this.disabled = false;
                     console.log("error in getTournamentListOfUser::",error);
                });  
            },
            redirectToTournamentDetailPage(selectedTournament){
                let name = selectedTournament.name
                let id = selectedTournament.id
                let tournamentDays = Plugin.setTournamentDays(selectedTournament.start_date, selectedTournament.end_date)
                let tournamentSel  = {
                    name:name,
                    id:id,
                    maximum_teams:selectedTournament.maximum_teams,
                    tournamentDays: tournamentDays,
                    tournamentLogo: selectedTournament.tournamentLogo,
                    tournamentStatus:selectedTournament.status,
                    tournamentStartDate:selectedTournament.start_date,
                    tournamentEndDate:selectedTournament.end_date,
                    facebook:selectedTournament.facebook,
                    website:selectedTournament.website,
                    twitter:selectedTournament.twitter,
                    access_code:selectedTournament.access_code
                }
                this.$store.dispatch('SetTournamentName', tournamentSel);
                let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'};
                this.$store.dispatch('setActiveTab', currentNavigationData);
                this.$router.push({name:'tournament_add'});
            },
            redirectToTransactionListPage(tournament){
                 this.$router.push({name: 'tournamentstransaction', query: {id:tournament.id, customer_id:this.customer_id}}); 
            },
            getTournamentRecord() {
                if(Object.keys(this.$route.query).length === 0) {
                    this.$router.push({name: 'users_list'});
                }else{
                    if(this.$route.query.id != ''){
                        this.customer_id = this.$route.query.id; 
                        this.getTournamentListOfUser();
                    }else{  
                        this.$router.push({name: 'users_list'});
                    }
                }
            },
            getTournamentType(type) {
                return type.charAt(0).toUpperCase() + type.slice(1);
            },
            getTournamentFormat(format) {
                return format === 1 ? "Yes" : "No";
            },
        },
        beforeMount(){  
            this.getTournamentRecord();
        }
    }
</script>