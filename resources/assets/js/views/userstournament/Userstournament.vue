<template> 
    <div> 
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center mb-3 ">
                      <div class="col-md-5">
                            <p class="mb-0">{{$lang.user_management_all_users_sentence}}</p>
                      </div>
                      <div class="col-md-7">
                        <div class="row align-items-center justify-content-end">
                          <div class="col-12">
                            <div class="row">
                              <div class="col-md-5">
                               
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
                                        <th>Teams</th>
                                        <th>App</th>
                                        <th>Purchase Date</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="tournament in usersTourmanents">
                                    <td v-on:click="redirectToTournamentDetailPage(tournament)">{{ tournament.name }}</td>
                                    <td>{{ tournament.start_date }}</td>
                                    <td>{{ tournament.end_date }}</td>
                                    <td>{{ tournament.maximum_teams }}</td>
                                    <td>TEA</td>
                                    <td v-on:click="redirectToTransactionListPage(tournament)" >{{ tournament.created_at }}</td>
                                    <td>Edit</td>
                                    
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
    // console.log("register  page");
    import Constant from '../../services/constant'
    export default {
        data() {
            return {
                usersTourmanents:[],
                customer_id:0, // currently static
            }
        },
        beforeRouteEnter(to, from, next) {  
            next(vm =>{
                if(Object.keys(to.query).length === 0) {
                    vm.$router.push({name: 'users_list'});
                }else{
                    if(typeof to.query.id != "undefined"){
                        vm.customer_id = to.query.id; 
                        vm.getTournamentListOfUser();
                    }else{  
                        vm.$router.push({name: 'users_list'});
                    }
                }
                
            })
        },
        methods: {
            getTournamentListOfUser(){
                let params = {
                    customer_id:this.customer_id, // currently static
                }
                // console.log("params::",params)
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
                    twitter:selectedTournament.twitter
                }
                this.$store.dispatch('SetTournamentName', tournamentSel);
                let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'};
                this.$store.dispatch('setActiveTab', currentNavigationData);
                this.$router.push({name:'tournament_add'});
            },

            redirectToTransactionListPage(tournament){
                 this.$router.push({name: 'tournamentstransaction', query: {id:tournament.id}}); 
            } 
        },
        beforeMount(){  
            // console.log("userstourmanent");
            // this.getTournamentListOfUser();
        }
    }
</script>