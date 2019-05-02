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
                                        
                                        <th>Duration</th>
                                        <th>Purchase Date</th>
                                        <th>Currency</th>
                                        <th>Total</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="(transction,index) in tournamentsTransactions">
                                        <td>{{transction.order_id}}</td>
                                        <td>{{transction.transaction_id}}</td>
                                        <td>{{transction.team_size}}</td>
                                        
                                        <td><span v-if='index >= 1 && transction.no_of_days > 0' >+</span>{{transction.no_of_days}} day<span v-if='transction.no_of_days >= 1'>s</span></td>
                                        <td>{{transction.transaction_date}}</td>
                                        <td>{{transction.currency}}</td>
                                        <td>{{transction.amount}}</td>                          
                                    
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
                
                axios.post(Constant.apiBaseUrl+'customer-transactions',params).then(response =>  { 
                    
                     this.tournamentsTransactions = response.data.data;
                }).catch(error => {
                    this.disabled = false;
                     console.log("error in getTournamentListOfUser::",error);
                });  
            },

            /*getDayDifferences(start,end){
                let startDateArr = start.split("/");
                let endDateArr = end.split("/"); 
                let startDateFormat = startDateArr[2]+"/"+startDateArr[1]+"/"+startDateArr[0];
                let endDateFormat = endDateArr[2]+"/"+endDateArr[1]+"/"+endDateArr[0]; 
                let startDate = moment(startDateFormat);
                let endDate = moment(endDateFormat).add('days', 1);
                
                let dayDiff = endDate.diff(startDate, 'days');
                return dayDiff;
            }*/ 
        },
        beforeMount(){  
        }
    }
</script>