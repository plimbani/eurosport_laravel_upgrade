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

                        <div v-if="isToShowTemplateSection" :class="{'is-disabled': !isTemplateSectionAccessible}">
                            <h3 class="text-uppercase font-weight-bold mb-4 mt-5">Manage Templates</h3>
                            <div class="row" v-for="template in templates">
                                <div class="col-md-6 col-lg-12 d-flex">
                                    <div class="card w-100">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col-xl-7">
                                                    <p class="h7 mb-0 text-uppercase">Version {{ template.version }}</p>
                                                    <h3 class="font-weight-bold mb-2">{{ template.name }}</h3>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><img src="/images/team.png" alt=""> &nbsp;{{ template.total_teams }} Teams</li>
                                                        <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><img src="/images/match.png" alt=""> &nbsp;{{ template.total_matches }} Matches</li>
                                                        <!-- <li class="d-block d-lg-inline"><img src="/images/date.png" alt=""> &nbsp;19th - 22nd October 2018</li> -->
                                                    </ul>
                                                </div>
                                                <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                    <div class="btn-group">
                                                        <button class="btn btn-link" @click="deleteTemplate(template)"><img src="/images/delete.png" alt=""> Delete</button>
                                                        <button class="btn btn-outline ml-2" @click="checkForEditTemplate(template)"><img src="/images/edit.png" alt=""> Edit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" v-if="templates.length == 0">
                                <div class="col-md-6 col-lg-12 d-flex">
                                    <div class="card w-100">
                                        <div class="card-block">
                                            <h4 class="mb-0">No templates found.</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-success" @click="addTemplate()">Add Template</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
        <template-in-use-modal v-show="templateInUseModal"></template-in-use-modal>
        <template-version-confirm-modal v-show="templateEditModal" @confirmed="editConfirmed()"></template-version-confirm-modal>

    </div>
</template>
<script type="text/babel">
    import Auth from '../../services/auth'
    import Ls from '../../services/ls'
    import Constant from '../../services/constant'
    import Template from '../../api/template'
    import DeleteModal from '../../components/DeleteModal.vue'
    import TemplateInUseModal from '../../components/TemplateInUseModal.vue'
    import TemplateVersionConfirmModal from '../../components/TemplateVersionConfirmModal.vue'
    
    export default {
        data() {
            return { 
                tournaments: [],
                access_code_popup:"",
                url:"tournament-detail",
                dashboardTournamentDisplayDateFormat:"",
                currentDateTime: moment(),
                templates: [],
                deleteAction: '',
                templateInUseModal: false,
                deleteConfirmMsg: 'Are you sure you would like to delete this template?',
                templateEditModal: false,
                templateEdit: null,
                isToShowTemplateSection: false,
                isTemplateSectionAccessible: false,
            }
        },
        components: {
            DeleteModal, TemplateInUseModal, TemplateVersionConfirmModal
        },        
        computed: {
        },
        mounted() {
            this.getTemplateList();

            if(this.userDetails.role_slug == 'customer' && this.$route.query.error == true) {
                toastr['error']('This action is unauthorized.', 'Error');
            }
        },
        methods: {
            getTournamentList(){
                let vm = this;
                axios.get(Constant.apiBaseUrl+'tournaments/list', {}).then(response =>  {
                        if (response.data.success) { 
                            vm.tournaments = response.data.data;
                            vm.showTemplateSection();
                            vm.checkAccessiblityOfTemplateSection();
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
                    access_code:selectedTournament.access_code,
                    tournament_type: selectedTournament.tournament_type,
                    custom_tournament_format: selectedTournament.custom_tournament_format
                }
                this.$store.dispatch('SetTournamentName', tournamentSel);
                let currentNavigationData = {activeTab:'tournament_add', currentPage: 'Tournament details'};
                this.$store.dispatch('setActiveTab', currentNavigationData);
                this.$router.push({name:'tournament_add'});
            },
            openSharePopup(tournament){
                let tournamentAccessCode = tournament.access_code.toUpperCase();
                this.access_code_popup = appUrl + '/' + this.url + '?code=' + tournamentAccessCode;
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

                let tournamentExpireTime = moment(tournamentEndDate).format('YYYY-MM-DD HH:mm:ss');
                let currentDateTime = moment(this.currentDateTime).format('YYYY-MM-DD HH:mm:ss');
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
            },
            getTemplateList() {
                Template.getTemplates().then(
                    (response)=> {
                        this.templates = response.data.data.data;
                    },
                    (error)=> {
                    }
                )
            },
            addTemplate() {
                this.$router.push({name: 'templates_list', query: {from: 'add'}});
            },
            checkForEditTemplate(template){
                this.templateEdit = null;
                Template.getTemplateDetail(template).then(
                  (response)=> {
                      this.templateEdit = template;
                      if(response.data.data.length == 0) {
                        this.editTemplate(template.id);
                      } else {
                        this.templateEditModal = true;
                        $('#template_version_confirm_modal').modal('show');
                        return true;
                      }
                  },
                  (error)=> {
                  }
                )
            },
            editConfirmed(){
                $('#template_version_confirm_modal').modal('hide');
                this.editTemplate(this.templateEdit.id);
                this.templateEdit = null;
            },
            editTemplate(templateId) {
                this.$router.push({name: 'templates_list', query: {templateId: templateId, from: 'edit'}});
            },
            deleteTemplate(template) {
                Template.getTemplateDetail(template).then(
                  (response)=> {
                      if(response.data.data.length == 0) {
                        this.deleteAction="template/delete/" +template.id;
                        $('#delete_modal').modal('show');
                      } else {
                        this.templateInUseModal = true;
                        $('#template_in_use_modal').modal('show');
                      }
                  },
                  (error)=> {
                  }
                )
            },
            deleteConfirmed() {
                $("body .js-loader").removeClass('d-none');
                Template.deleteTemplate(this.deleteAction).then(
                  (response)=> {
                    $("body .js-loader").addClass('d-none');
                    $("#delete_modal").modal("hide");
                    toastr.success('Template has been deleted successfully.', 'Delete Template', {timeOut: 5000});
                    this.getTemplateList();
                  },
                  (error)=> {
                  }
                )
            },
            showTemplateSection() {
                let vm = this;
                let customTournaments = _.filter(_.cloneDeep(this.tournaments), function(o) {
                    if(o.tournament_type === 'cup' && o.custom_tournament_format == 1) {
                        return true;
                    }
                    return false;
                });
                this.isToShowTemplateSection = customTournaments.length > 0 ? true : false;
            },
            checkAccessiblityOfTemplateSection() {
                let vm = this;
                let customActiveTournaments = _.filter(_.cloneDeep(this.tournaments), function(o) {
                    if(o.tournament_type === 'cup' && o.custom_tournament_format == 1 && !vm.tournamentDashboardEdit(o.tournamentExpireTime)) {
                        return true;
                    }
                    return false;
                });
                this.isTemplateSectionAccessible = customActiveTournaments.length > 0 ? true : false;
            },
        },
        beforeMount(){  
            this.getTournamentList();
        }
    }
</script>