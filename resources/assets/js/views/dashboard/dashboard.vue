<template>
    <div class="main-section">
        <div class="manage-tournament section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-uppercase font-weight-bold mb-4">Manage Tournaments</h3>
                        <div class="row">
                            <div class="col-md-6 col-lg-12 d-flex" v-for="transaction of transactions">
                                
                                <div class="card w-100">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7">
                                                <p class="h7 text-uppercase mb-0">License: #{{transaction.tournament.access_code}}</p>
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-lg-7">
                                                        <h3 class="font-weight-bold mb-0">{{transaction.tournament.name}}</h3>
                                                    </div>
                                                    
                                                    <div class="col-lg-5">
                                                        <ul class="list-unstyled mb-0 tournament-information">
                                                            <li class="d-inline h7 text-uppercase font-weight-bold pr-2"><span><i class="fa fa-globe"></i></span>&nbsp; <a target="_blank" v-bind:href="transaction.tournament.website">View public website</a></li>
                                                            <li v-if="transaction.tournament.access_code" id="open-share-popup" @click="openSharePopup(tournament)" class="d-inline h7 text-uppercase font-weight-bold"><span><i class="fa fa-share-alt"></i></span>&nbsp; <a href="#">Share</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-list-ul" aria-hidden="true"></i> &nbsp;{{transaction.tournament.maximum_teams}} Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp;{{transaction.tournament.no_of_match_per_day_pitch}} Matches</li>
                                                    <li class="d-block d-lg-inline"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;{{transaction.tournament.start_date}} - {{transaction.tournament.end_date}}</li>
                                                </ul>
                                            </div>
                                
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group" v-if="!isTournamentExpired(transaction.tournament.end_date)">
                                                    <button class="btn btn-outline" v-on:click="redirectToTournamentDetailPage(transaction)"><span><i class="fa fa-pencil" aria-hidden="true"></i></span>&nbsp; Edit</button>
                                                    <button class="btn btn-outline ml-2" v-on:click="redirectToManageTournament(transaction)">Manage License</button>
                                                </div>
                                                <div class="btn-group" v-if="isTournamentExpired(transaction.tournament.end_date)">
                                                    <button class="btn btn-outline ml-2" v-on:click="redirectToRenewTournament(transaction)">Renew License</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="modal fade" id="open_share_popup">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Share Tournment</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>You can invite anyone to follow your tournament online and in the app. Simply share your following URL by email, SMS or any other social Media.</p>
                                        <p class="popup-access-code mb-0 text-center py-4 px-1 font-weight-bold" v-on:click="copyAccessCode()">{{ access_code_popup }}</p>
                                        <input type="hidden" id="access_code_popup" :value="access_code_popup">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success" v-on:click="redirectToAddTournament()">Add Tournament</button>

                        <h3 class="text-uppercase font-weight-bold mb-4 mt-5">Manage Templates</h3>
                        <div class="row">
                            <div class="col-md-6 col-lg-12 d-flex">
                                <div class="card w-100">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7">
                                                <p class="h7 mb-0">Version 1</p>
                                                <h3 class="font-weight-bold mb-2">Ben’s 4x4 + Knockout</h3>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-list-ul" aria-hidden="true"></i> &nbsp;32 Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp;48 Matches</li>
                                                    <li class="d-block d-lg-inline"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;19th - 22nd October 2018</li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group">
                                                    <button class="btn btn-link"><span><i class="fa fa-trash" aria-hidden="true"></i></span>&nbsp;Delete</button>
                                                    <button class="btn btn-outline ml-2"><span><i class="fa fa-pencil" aria-hidden="true"></i></span>&nbsp; Edit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success">Add Template</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'
    import Commercialisation from '../../api/commercialisation.js'
    
    export default {
        data() {
            return { 
                tournaments: [],
                transactions: [],
                access_code_popup:"",
                url:"app.tournament-planner.com/t/"
            }
        },
        methods: {
            getTournamentList(){
                axios.get(Constant.apiBaseUrl+'getUserTransactions', {}).then(response =>  {
                    // this.transactions = response.data.transactions;
                        // if (response.data.success) { 
                            this.transactions = response.data.transactions;
                             
                         // }else{ 
                         //    toastr['error'](response.data.message, 'Error');
                         // }
                 }).catch(error => {
                     
                 });                
            },
            isTournamentExpired(expireDate){
                
                let expireDateArr = expireDate.split("/");
                let currentDateArr = moment().format("DD/MM/YYYY").split("/");

                let startDateFormat = expireDateArr[2]+"/"+expireDateArr[1]+"/"+expireDateArr[0];
                let endDateFormat = currentDateArr[2]+"/"+currentDateArr[1]+"/"+currentDateArr[0]; 

                
                let startDate = moment(startDateFormat);
                let endDate = moment(endDateFormat);
                let dayDifference = endDate.diff(startDate, 'days');
                
                if(dayDifference >= 2){
                    return true;
                }else{
                    return false;
                }

            }, 

            redirectToTournamentDetailPage(selectedTournament){
                let name = selectedTournament.tournament.name
                let id = selectedTournament.tournament.id
                let tournamentDays = Plugin.setTournamentDays(selectedTournament.tournament.start_date, selectedTournament.tournament.end_date)
                let tournamentSel  = {
                    name:name,
                    id:id,
                    maximum_teams:selectedTournament.tournament.maximum_teams,
                    tournamentDays: tournamentDays,
                    tournamentLogo: selectedTournament.tournament.tournamentLogo,
                    tournamentStatus:selectedTournament.tournament.status,
                    tournamentStartDate:selectedTournament.tournament.start_date,
                    tournamentEndDate:selectedTournament.tournament.end_date,
                    facebook:selectedTournament.tournament.facebook,
                    website:selectedTournament.tournament.website,
                    twitter:selectedTournament.tournament.twitter
                }
                this.$store.dispatch('SetTournamentName', tournamentSel);
                let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'};
                this.$store.dispatch('setActiveTab', currentNavigationData);
                this.$router.push({name:'tournament_add'});
            },

            

            openSharePopup(tournament){
                this.access_code_popup = this.url + tournament.tournament.access_code;
                $("#open_share_popup").modal('show'); 
            },

            getAccessCode (event) {
                
            },
            copyAccessCode () {
              let testingCodeToCopy = document.querySelector('#access_code_popup')
              testingCodeToCopy.setAttribute('type', 'text')    
              testingCodeToCopy.select()

              try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                
                toastr['success']('Tournament url has been copied successfully.', 'Success');
              } catch (err) {
                toastr['error']('Oops, unable to copy.', 'Error');
                
              }

              /* unselect the range */
              testingCodeToCopy.setAttribute('type', 'hidden')
              window.getSelection().removeAllRanges()
            },
            redirectToAddTournament(){
                this.$router.push({name: 'buylicense'});
            },
            redirectToRenewTournament(transaction){
                this.$router.push({name: 'buylicense', query: {id:transaction.id}});   
            },
            redirectToManageTournament(transaction){
                this.$router.push({name: 'buylicense', query: {id:transaction.id}});   
            }             
            
        },
        beforeMount(){  
             this.getTournamentList();
        }
    }
</script>