<template>
    <div class="manage-tournament section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-uppercase font-weight-bold mb-4">Manage Tournaments</h3>

                    <div class="row">
                        <div class="col-md-6 col-lg-12 d-flex" v-for="tournament of tournaments">
                            <div class="card w-100">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-xl-7">
                                            <p class="h7 text-uppercase mb-0">License: #{{tournament.access_code}}</p>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-lg-7">
                                                    <h3 class="font-weight-bold mb-0">{{tournament.name}}</h3>
                                                </div>
                                                
                                                <div class="col-lg-5">
                                                    <ul class="list-unstyled mb-0 tournament-information">
                                                        <li class="d-inline h7 text-uppercase font-weight-bold pr-2"><span><i class="fa fa-globe"></i></span>&nbsp; <a target="_blank" v-bind:href="tournament.website">View public website</a></li>
                                                        <li v-if="tournament.access_code" id="open-share-popup" @click="openSharePopup(tournament)" class="d-inline h7 text-uppercase font-weight-bold"><span><i class="fa fa-share-alt"></i></span>&nbsp; <a href="#">Share</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-list-ul" aria-hidden="true"></i> &nbsp;{{tournament.maximum_teams}} Teams</li>
                                                <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp;{{tournament.no_of_match_per_day_pitch}} Matches</li>
                                                <li class="d-block d-lg-inline"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;{{tournament.start_date}} - {{tournament.end_date}}</li>
                                            </ul>
                                        </div>
                                        <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                            <div class="btn-group" v-if="!isTournamentExpired(tournament.end_date)">
                                                <button class="btn btn-outline" v-on:click="redirectToTournamentDetailPage(tournament)"><span><i class="fa fa-pencil" aria-hidden="true"></i></span>&nbsp; Edit</button>
                                                <button class="btn btn-outline ml-2" v-on:click="redirectToManageTournament(tournament)">Manage License</button>
                                            </div>
                                            <div class="btn-group" v-if="isTournamentExpired(tournament.end_date)">
                                                <button class="btn btn-outline ml-2" v-on:click="redirectToRenewTournament(tournament)">Renew License</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <modal name="open-share-popup" @before-open="getAccessCode">
                      <div class="example-modal-content p-4">
                        <h4>Share Tournment <span v-on:click="closeSharePopup()" class="pull-right"><i class="fa fa-times"></i></span></h4> 
                        <p>You can invite anyone to follow your tournament online and in the app. Simply share your following URL by email, SMS or any other social Media.</p>
                        <p class="popup-access-code mb-0 text-center py-4 px-1 font-weight-bold" v-on:click="copyAccessCode()">{{ access_code_popup }}</p>
                        <input type="hidden" id="access_code_popup" :value="access_code_popup">
                      </div>
                    </modal>

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
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'

    // console.log("register  page");
    export default {
        data() {
            return { 
                tournaments:[],
                access_code_popup:"",
                url:"app.tournament-planner.com/t/"
            }
        },
        methods: { 

            getTournamentList(){ 
                axios.get(Constant.apiBaseUrl+'tournaments/list', {}).then(response =>  {  
                        if (response.data.success) { 
                             this.tournaments = response.data.data;
                             // console.log("tournaments::",this.tournaments[0].end_date)
                         }else{ 
                            toastr['error'](response.data.message, 'Error');
                         }
                 }).catch(error => {
                     
                 }); 
                
            },
             isTournamentExpired(expireDate){
                // console.log("expireDate::",expireDate)
                let expireDateArr = expireDate.split("/");
                let currentDateArr = moment().format("DD/MM/YYYY").split("/");

                let startDateFormat = expireDateArr[2]+"/"+expireDateArr[1]+"/"+expireDateArr[0];
                let endDateFormat = currentDateArr[2]+"/"+currentDateArr[1]+"/"+currentDateArr[0]; 

                // let currentDateArr = moment().add('days',1).format("DD/MM/YYYY").split("/");
                let startDate = moment(startDateFormat);
                let endDate = moment(endDateFormat);
                let dayDifference = endDate.diff(startDate, 'days');
                // console.log("dayDifference::",dayDifference)
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
                    twitter:selectedTournament.twitter
                }
                this.$store.dispatch('SetTournamentName', tournamentSel);
                let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'};
                this.$store.dispatch('setActiveTab', currentNavigationData);
                this.$router.push({name:'tournament_add'});
            },

            closeSharePopup(){
                this.$modal.hide('open-share-popup');
            },

            openSharePopup(tournament){
                // console.log("openSharePopup::",tournament.access_code)
                this.access_code_popup = this.url + tournament.access_code;
                this.$modal.show('open-share-popup', 
                    { 
                        access_code_popup: tournament.access_code ,
                        title: 'Alert!',
                    });
            },

            getAccessCode (event) {
                // console.log(event.params.access_code);
            },
            copyAccessCode () {
              let testingCodeToCopy = document.querySelector('#access_code_popup')
              testingCodeToCopy.setAttribute('type', 'text')    
              testingCodeToCopy.select()

              try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                // alert('Testing code was copied ' + msg);
                // alert('');
                toastr['success']('Tournament url has been copied successfully.', 'Success');
              } catch (err) {
                toastr['error']('Oops, unable to copy.', 'Error');
                // alert('');
              }

              /* unselect the range */
              testingCodeToCopy.setAttribute('type', 'hidden')
              window.getSelection().removeAllRanges()
            },
            redirectToAddTournament(){
                this.$router.push({name: 'buylicense'});
            },

            redirectToRenewTournament(tournament){
                // console.log("id:::",tournament.id)
                this.$router.push({name: 'buylicense', query: {id:tournament.id}});   
            },
            redirectToManageTournament(tournament){
                // console.log("id:::",tournament.id)
                this.$router.push({name: 'buylicense', query: {id:tournament.id}});   
            },
             
            
        },
        beforeMount(){  
             this.getTournamentList();
        }
    }
</script>