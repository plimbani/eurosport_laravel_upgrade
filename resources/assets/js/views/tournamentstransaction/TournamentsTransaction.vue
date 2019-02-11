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
                                        <th>OrderId</th>
                                        <th>Transaction ID</th>
                                        <th>Teams</th>
                                        <th>App</th>
                                        <th>Duration</th>
                                        <th>Purchase Date</th>
                                        <th>Currency</th>
                                        <th>Total</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="transction in tournamentsTransactions">
                                   <!--  <td>{{ transction.name }}</td>
                                    <td>{{ transction.start_date }}</td>
                                    <td>{{ transction.end_date }}</td>
                                    <td>{{ transction.maximum_teams }}</td>
                                    <td>TEA</td>
                                    <td>{{ transction.created_at }}</td>
                                    <td>Edit</td> -->
                                    
                                  </tr>
                                  <tr><td colspan="8"></td></tr>
                                </tbody>
                            </table>
                            
                        </div>
                        <div v-if="tournamentsTransactions.length == 0" class="col-md-12">
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
                tournamentsTransactions:[],
                tournament_id:160 // currently static
            }
        },
        beforeRouteEnter(to, from, next) {  
            next(vm =>{
                if(Object.keys(to.query).length === 0) {
                    vm.$router.push({name: 'users_list'});
                }else{
                    if(typeof to.query.id != "undefined"){
                        console.log("in detailss",to.query.id);
                        vm.tournament_id = to.query.id; 
                        vm.getTournamentTransactions();
                    }else{  
                        vm.$router.push({name: 'users_list'});
                    }
                }
                
            })
        },
        methods: {
            getTournamentTransactions(){
               let params = {
                    tournament_id:this.tournament_id, // currently static
                }
                // console.log("params::",params)
                axios.post(Constant.apiBaseUrl+'customer-transactions',params).then(response =>  { 
                    // this.disabled = false;
                     console.log("response: transactions:",response.data.data);
                     this.tournamentsTransactions = response.data.data;
                }).catch(error => {
                    this.disabled = false;
                     console.log("error in getTournamentListOfUser::",error);
                });  
            } 
        },
        beforeMount(){  
            // this.getTournamentTransactions();
        }
    }
</script>