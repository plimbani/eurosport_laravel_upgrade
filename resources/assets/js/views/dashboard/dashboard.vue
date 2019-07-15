<template>
    <div class="main-section">
        <div class="manage-tournament section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-uppercase font-weight-bold mb-4">Manage Tournaments</h3>
                        <div class="row">
                            <div class="col-md-6 col-lg-12 d-flex" v-for="tournament in tournaments">
                                <div class="card w-100">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7" :class="!tournamentDashboardEdit(tournament.tournamentExpireTime) ? '' : 'is-disabled'">
                                                <p class="h7 text-uppercase mb-0 text-uppercase">License: #{{tournament.access_code}}</p>
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-lg-7">
                                                        <h3 class="font-weight-bold mb-0">{{tournament.name}}</h3>
                                                    </div>
                                                    
                                                    <div class="col-lg-5">
                                                        <ul class="list-unstyled mb-0 tournament-information">
                                                            <li v-if="tournament.website" class="d-inline h7 text-uppercase font-weight-bold pr-2"><span><img src="/images/website.png" alt=""></span>&nbsp; <a target="_blank" v-bind:href="tournament.website">View public website</a></li>

                                                            <li v-if="tournament.access_code" id="open-share-popup" @click="openSharePopup(tournament)" class="d-inline h7 text-uppercase font-weight-bold"><span><img src="/images/share.png" alt=""></span>&nbsp; <a href="#">Share</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><img src="/images/team.png" alt=""> &nbsp;{{tournament.maximum_teams}} Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2" v-if="tournament.matchlistCount != 0"><img src="/images/match.png" alt=""> &nbsp;{{tournament.matchlistCount}} Matches</li>
                                                    <li class="d-block d-lg-inline"><img src="/images/date.png" alt=""> &nbsp;{{displayDashboardTournamentDateFormat(tournament)}}</li>
                                                </ul>
                                            </div>
                                
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group" v-if="!tournamentDashboardEdit(tournament.tournamentExpireTime)">
                                                    <button class="btn btn-outline" v-on:click="redirectToTournamentDetailPage(tournament)"><img src="/images/edit.png" alt=""> Edit</button>
                                                    <button v-if="tournamentDashboardManageLicense(tournament.start_date)" class="btn btn-outline ml-2" v-on:click="redirectToManageTournament(tournament)">Manage License</button>
                                                </div>
                                                <div class="btn-group" v-if="tournamentDashboardEdit(tournament.tournamentExpireTime)">
                                                    <button class="btn btn-outline ml-2" v-on:click="redirectToRenewTournament(tournament)">Renew License</button>
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
                                        <h5 class="modal-title">Share Tournament</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>You can invite anyone to follow the tournament online and in the app. Simply share the following URL by email, SMS or any other social media.</p>
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
                                                <p class="h7 mb-0 text-uppercase">Version 1</p>
                                                <h3 class="font-weight-bold mb-2">Ben’s 4x4 + Knockout</h3>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><img src="/images/team.png" alt=""> &nbsp;32 Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><img src="/images/match.png" alt=""> &nbsp;48 Matches</li>
                                                    <li class="d-block d-lg-inline"><img src="/images/date.png" alt=""> &nbsp;19th - 22nd October 2018</li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group">
                                                    <button class="btn btn-link"><img src="/images/delete.png" alt=""> Delete</button>
                                                    <button class="btn btn-outline ml-2"><img src="/images/edit.png" alt=""> Edit</button>
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
    
    export default {
        data() {
            return { 
                tournaments: [],
                access_code_popup:"",
                baseUrl:"",
                url:"tournament-detail",
                dashboardTournamentDisplayDateFormat:"",
                currentDateTime: moment(),
            }
        },
        computed: {

        },
        methods: {
            getTournamentList(){
                axios.get(Constant.apiBaseUrl+'tournaments/list', {}).then(response =>  {
                        if (response.data.success) { 
                            this.baseUrl = response.data.data.baseUrl;
                            this.tournaments = response.data.data.data;
                        }else{ 
                            toastr['error'](response.data.message, 'Error');
                        }
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

                console.log("here",dayDifference);
                
                if(dayDifference >= 2){
                    return true;
                }else{
                    return false;
                }

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

            openSharePopup(tournament){
                this.access_code_popup = this.baseUrl + '/' + this.url + '?code=' + tournament.access_code;
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
            redirectToRenewTournament(tournament){
                this.$router.push({name: 'buylicense', query: {id:tournament.id}});   
            },
            redirectToManageTournament(tournament){
                this.$router.push({name: 'buylicense', query: {id:tournament.id}});   
            },
            displayDashboardTournamentDateFormat(tournament){
                let vm = this;
                let startDateFormat = moment(tournament.start_date, 'DD/MM/YYYY').format('Do MMM YYYY');
                let endDateFormat = moment(tournament.end_date, 'DD/MM/YYYY').format('Do MMM YYYY');

                let startDay = moment(tournament.start_date, 'DD/MM/YYYY').format('Do');
                let startMonth = moment(tournament.start_date, 'DD/MM/YYYY').format('MMM');
                let startDateMonth = moment(tournament.start_date, 'DD/MM/YYYY').format('Do MMM');
                let startYear = moment(tournament.start_date, 'DD/MM/YYYY').format('YYYY');

                let endDay = moment(tournament.end_date, 'DD/MM/YYYY').format('Do');
                let endMonth = moment(tournament.end_date, 'DD/MM/YYYY').format('MMM');
                let endYear = moment(tournament.end_date, 'DD/MM/YYYY').format('YYYY');

                if(startMonth == endMonth){
                    return vm.dashboardTournamentDisplayDateFormat = startDay+ ' - '+endDateFormat;
                } else if(startYear != endYear){
                    return vm.dashboardTournamentDisplayDateFormat = startDateFormat+ ' - ' +endDateFormat;
                } else if(startMonth != endMonth) {
                    return vm.dashboardTournamentDisplayDateFormat = startDateMonth+ ' - ' +endDateFormat;
                } 
            },
            tournamentDashboardEdit(endDate){
                //let currentDateTime = this.currentDateTime;
                let tournamentEndDate = endDate;

                let tournamentExpireTime = moment(tournamentEndDate).format('DD/MM/YYYY HH:mm:ss');
                let currentDateTime = moment(this.currentDateTime).format('DD/MM/YYYY HH:mm:ss');

                if(tournamentExpireTime > currentDateTime) {
                   return false;
                } else {
                  return true;
                }
            },

            tournamentDashboardManageLicense(startDate) {
                let currentDateTime = this.currentDateTime;
                let tournamentStartDate = moment(startDate, 'DD/MM/YYYY HH:mm:ss');
                if(tournamentStartDate > currentDateTime){
                    return true
                } else {
                    return false
                }
            }      
            
        },
        beforeMount(){  
             this.getTournamentList();
        }
    }
</script>