<template>
  <div class="tab-content">
    <div class="card">
      <div class="card-block card-teams">
        <h6 class="fieldset-title mt-10"><strong>{{$lang.teams_terms_groups}}</strong></h6>
        <div class="card mb-0">
          <div class="card-block">
            <div id="step1" class="row">
              <div class="col-md-12">

                <h6 class="text-danger" v-if="competitionList.length === 0 && currentLayout === 'commercialisation'">
                  Before you can upload your teams create an age category in the Competition Formats area.
                </h6>

                <h6><b>Step 1:</b> Download team list</h6>
                <div class="content-card">
                  <div class="row align-items-center gutters-tiny">
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-4">
                          <button class="btn btn-success btn-block" :class="{'is-disabled': competitionList.length === 0 && currentLayout === 'commercialisation'}" @click="downloadTeamsSpreadsheetSample()">Download</button>
                        </div>
                        <div class="col-md-3 text-center">
                          <a href="javascript:void(0);" class="text-primary border-bottom-dashed--primary" @click="previewSpredsheetSample()">View example</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="step2" class="row d-none">
              <div class="col-md-12">
                <h6><b>Step 2:</b> Upload your team list</h6>
                <div class="content-card">
                  <div class="row mb-0 align-items-center" :class="{'form-group': true, 'is-disabled': competitionList.length === 0}">
                    <div class="col-md-8">
                      <form method="post" name="frmCsvImport" id="frmCsvImport" enctype="multipart/form-data">
                        <div class="row gutters-tiny">
                          <div class="col-md-6">
                            <button type="button" class="btn btn-default w-100 btn-color-black--light" id="profile_image_file">Select file (excel files only)</button>
                          </div>
                          <div class="col-md-4 btn-group-agecategory">
                            <button type="button" @click="csvImport()" class="btn-block" :class="{ 'btn': true, 'btn-success': competitionList.length > 0, 'btn-outline-success': competitionList.length === 0, 'is-disabled': !this.isFileUploaded }">Upload list</button>
                          </div>
                        </div>
                        <span id="filename"></span>
                        <input type="file" name="fileUpload" @change="setFileName(this,$event)"  id="fileUpload" style="display:none;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,application/excel,application/vnd.ms-excel,application/vnd.msexcel,text/anytext,application/txt">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="step3" class="row d-none">
              <div class="col-md-12">
                <h6><b>Step 3:</b> Allocate teams</h6>
                <div class="content-card">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="row align-items-end gutters-tiny">
                        <div class="col-md-4">
                          <div class="form-group mb-0">
                            <label>Select category</label>
                            <select class="form-control" v-model="age_category" v-on:change="onSelectAgeCategory('view')">
                              <option value="">{{$lang.teams_all_age_category}}</option>
                              <option v-for="option in options"
                               v-bind:value="option"> {{option.group_name}} ({{option.category_age}})</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="row align-items-center gutters-tiny btn-group-agecategory" v-show="this.age_category != ''">
                            <div class="col-md-4">
                              <button type="button" class="btn btn-primary btn-block" :class="{'is-disabled': (selectedGroupsTeam.length > 0 || ageCategoryHasNoTeams)}" @click="allocateTeams(age_category.id)">Team allocation</button>
                            </div>
                            <div class="col-md-4 text-center">
                              <a href="javascript:void(0);" data-toggle="modal" data-target="#reset_modal" class="text-danger border-bottom-dashed--danger" :class="{'is-disabled': ageCategoryHasNoTeams}">Delete selected teams</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="block-bg age-category mt-4" id="age_category_block" :class="{'age-category-selected': this.age_category != ''}">
                  <div class="age-category-grid" v-if="grpsView.length != 0">
                    <div class="age-category-grid-block" v-for="(group, index) in grpsView">
                      <div class="m_card hoverable h-100 m-0">
                        <div class="card-header">
                          {{ getGroupName(group) }}
                        </div>
                        <div class="card-content">
                          <div v-for="(n, pindex) in group['group_count']">
                            <div class="draggable-item" draggable="true" :data-select-id="groupName(group, n).displayId" :data-team-name="groupName(group, n).displayName" :data-group-name="getGroupName(group)+n" :id="'group_' + index + '_' + pindex" @dragstart="onTeamDrag($event,'group')" @drop="groupName(group, n).isHolderName === true ? onTeamDrop($event) : null" @dragover="groupName(group, n).isHolderName === true ? allowDrop($event) : null">
                              <span class="draggable-handle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7.5 12"  xmlns:v="https://vecta.io/nano"><path d="M3,10.5A1.5,1.5,0,1,1,1.5,9,1.5,1.5,0,0,1,3,10.5Zm-1.5-6A1.5,1.5,0,1,0,3,6,1.5,1.5,0,0,0,1.5,4.5ZM1.5,0A1.5,1.5,0,1,0,3,1.5,1.5,1.5,0,0,0,1.5,0ZM6,3A1.5,1.5,0,1,0,4.5,1.5,1.5,1.5,0,0,0,6,3ZM6,4.5A1.5,1.5,0,1,0,7.5,6,1.51,1.51,0,0,0,6,4.5ZM6,9a1.5,1.5,0,1,0,1.5,1.5A1.5,1.5,0,0,0,6,9Z" fill="#cccccc" fill-rule="evenodd"/></svg>
                              </span>
                              <span>
                                <span>
                                  <span :class="groupFlag(group,n)"></span>
                                  <span class="team-name">{{ groupName(group, n).displayName | truncate(20) }}</span>
                                </span>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-else class="d-flex justify-content-center">
                    <div class="col-sm-9  m-8">
                        <div class="card-content">
                           <div class="card-title text-center mb-0"> {{ $lang.teams_name_select }}</div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-4 team-table">
                  <div class="col-md-9 text-muted">
                    <p>Drag and drop the team name directly in the category structure above. Alternatively, select a value in the 'Allocate' column.</p>
                  </div>
                  <div class="col-md-3" v-show="this.age_category != ''">
                    <a href="javascript:void(0);" v-if="this.role_slug != 'mobile.user'" class="btn btn-primary mb-4 pull-right" @click="printAllocatedTeams()">Download groups</a>
                  </div>
                  <div class="col-md-12">
                    <form name="frmTeamAssign" id="frmTeamAssign" class="frm-team-assign">
                      <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th width="150px" class="text-center">{{$lang.teams_reference}}</th>
                                <th class="text-center">{{$lang.teams_name}}</th>
                                <th class="text-center">{{$lang.teams_country}}</th>
                                <th class="text-center">{{$lang.teams_place}}</th>
                                <th class="text-center">{{$lang.teams_age_category}}</th>
                                <th  class="text-center">{{$lang.teams_name_category}}</th>

                                <th class="text-center" v-if="tournamentFilter.filterKey == 'age_category' && tournamentFilter.filterValue != '' ">{{$lang.teams_age_category_allocate}}</th>
                                <th width="130px" class="text-center" v-else>{{$lang.teams_age_category_allocate}}</th>
                                <th class="text-center">Edit</th>
                            </tr>
                        </thead>
                          <tbody v-if="teams.length!=0">
                              <tr v-for="(team, index) in teams">
                                <td width="150px">{{team.esr_reference}}</td>
                                <td class="team-edit-section">
                                  <div class="custom-d-flex align-items-center justify-content-between" v-show="!(team.id in teamsInEdit)">
                                    <span draggable="true" :data-select-id="team.id" :id="'team_' + index" @dragstart="onTeamDrag($event,'team')">{{team.name}}</span>
                                    <span class="pull-right"><a href="javascript:void(0);" v-on:click="editTeamName($event, team.id, team.name)"><i class="fas fa-pencil" aria-hidden="true"></i></a></span>
                                  </div>
                                  <div v-show="(team.id in teamsInEdit)">
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                      <input type="text" class="form-control" v-model="team.name" />
                                      <a href="javascript:void(0);" v-on:click="cancelTeamNameChange(team.id)" class="btn btn-secondary d-inline-flex align-items-center"><i class="fas fa-times text-center text-danger" aria-hidden="true"></i></a>
                                      <a href="javascript:void(0);" v-on:click="saveTeamNameChanges($event, team.id, team.name)" class="btn btn-secondary d-inline-flex align-items-center"><i class="fas fa-check text-center text-primary" aria-hidden="true"></i></a>
                                    </div>
                                  </div>
                                </td>
                                <td>
                                  <!-- <img :src="team.logo" width="20"> {{team.country_name}} -->
                                    <span :class="'flag-icon flag-icon-'+team.country_flag"></span> {{team.country_name}}
                                </td>
                                <td>{{team.place}} </td>
                                <td>{{team.category_age}} </td>
                                <td>{{team.age_name}} </td>

                                <td width="130px" v-if="(age_category == '')">{{ getModifiedDisplayGroupName(team.group_name) }}</td>
                                <td :class="{'is-disabled': !canChangeTeamOption(team.id)}" width="130px" v-else style="position: relative">
                                  <teamSelect :team="team" :grps="grps" @onAssignGroup="onAssignGroup" @beforeChange="beforeChange" @assignTeamGroupName="assignTeamGroupName" :canChangeTeamOption="canChangeTeamOption(team.id)"></teamSelect>
                                </td>
                                <td class="text-center">
                                  <a class="text-primary" href="javascript:void(0)"
                                   @click="editTeam(team.id)">
                                    <i class="fas fa-pencil"></i>
                                  </a>
                                </td>
                              </tr>

                          </tbody>
                          <tbody v-else>
                            <tr>
                              <td colspan="8"> No teams available</td>
                              </tr>
                          </tbody>
                      </table>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <button v-show="currentStep > 1" type="button" class="btn btn-primary mt-20px" @click="back()">Back</button>
            <button v-show="currentStep < 3" type="button" class="btn btn-success mt-20px pull-right"
            :class="{'is-disabled': competitionList.length === 0 && currentLayout === 'commercialisation'}" @click="nextStep()">Next</button>
            <button type="button" v-if="age_category != '' && currentStep == 3" @click="groupUpdate()" class="btn btn-primary mt-20px pull-right" :class="{'is-disabled': (ageCategoryHasNoTeams || selectedGroupsTeam.length == 0)}">{{$lang.teams_button_savegroups}}</button>
          </div>
        </div>
      </div>
    </div>

    <team-modal v-if="teamId!=''" :teamId="teamId" :countries="countries" :clubs="clubs" :teamColors="teamColors"></team-modal>
    <div class="modal fade" id="reset_modal" tabindex="-1" role="dialog"
      aria-labelledby="resetModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="myModalLabel">Confirmation</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body text-left">
              <p>
                  Are you sure you would like to reset this age category? This will delete
                  <b>ALL</b> team information associated with this age category including team names, fixtures and results.
              </p>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary w-58" data-dismiss="modal">No</button>
              <button type="submit" class="btn btn-primary" @click="resetAllTeams()">Yes</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal team-preview" id="teams_groups_preview_modal" tabindex="-1" role="dialog" aria-labelledby="teams_groups_preview_modal" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">Preview</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
            <img :src="currentLayout === 'commercialisation' 
            ? '/assets/img/teams_groups_preview/TeamsGroupsPreviewEMM.png' 
            : '/assets/img/teams_groups_preview/TeamsGroupsPreview.png'" class="img-fluid">
          </div>
         </div>
      </div>
    </div>
    <div class="modal js-team-upload-error team-upload-summary" id="team_upload_summary" tabindex="-1" role="dialog" aria-labelledby="team_upload_summary" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">Team Upload Error</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body">
            <div class="category-error" v-if="checkForTeamUploadError(nonExistingAgeCategories)">
              <div><strong>The following age categories were not uploaded as they do not currently exist in the tournament:</strong></div>
              <div v-for="category in nonExistingAgeCategories">
                {{ category.categoryName + ' (' + category.ageCategory + ')' }}
              </div>
            </div>
            <div class="category-error" v-if="checkForTeamUploadError(teamNotMatchingAgeCategories)">
              <div><strong>The following age categories were not uploaded due to an error in the number of teams:</strong></div>
              <div v-for="category in teamNotMatchingAgeCategories">
                {{ category.categoryName + ' (' + category.ageCategory + ')' }}
              </div>
            </div>
            <div class="category-error" v-if="checkForTeamUploadError(teamsNotUploadedOfAgeCategory)">
              <div><strong>The following age categories team information has not been updated successfully:</strong></div>
              <div v-for="category in teamsNotUploadedOfAgeCategory">
                {{ category.categoryName + ' (' + category.ageCategory + ')' }}
              </div>
            </div>
            <div class="category-error" v-if="checkForTeamUploadError(teamsInDifferentAgeCategory)">
              <div><strong>The following age categories were not uploaded as one or more teams have a teamID that already exist on the platform:</strong></div>
              <div v-for="category in teamsInDifferentAgeCategory">
                {{ category.categoryName + ' (' + category.ageCategory + ')' }}
              </div>
            </div>
            <div class="category-error" v-if="checkForTeamUploadError(notProcessedAgeCategoriesDueToResultEntered)">
              <div><strong>The following age categories were not uploaded as one or more results have already been entered for one or more teams:</strong></div>
              <div v-for="category in notProcessedAgeCategoriesDueToResultEntered">
                {{ category.categoryName + ' (' + category.ageCategory + ')' }}
              </div>
            </div>
            <div class="category-error" v-if="checkForTeamUploadError(notProcessedAgeCategoriesDuetoSameTeamInUploadSheet)">
              <div><strong>The following age categories were not uploaded as one or more teams in the upload spreadsheet have the same teamID:</strong></div>
              <div v-for="category in notProcessedAgeCategoriesDuetoSameTeamInUploadSheet">
                {{ category.categoryName + ' (' + category.ageCategory + ')' }}
              </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
         </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="pull-right">
            <button v-if="currentStep == 3" class="btn btn-primary" :class="{'is-disabled': teams.length == 0 }" @click="next()">Next&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
   import Tournament from '../../../api/tournament.js'
   import _ from 'lodash'
   import TournamentFilter from '../../../components/TournamentFilter.vue'
   import teamSelect from '../../../components/teamSelect/teamSelect.vue'
   import TeamModal  from  '../../../components/teamSelect/TeamModal.vue'

   import Vue from 'vue'

  export default {
    data() {
    return {
        'teamId': '',
        'teamSize': 5,
        'teams': [],
        // 'teamsIdList': '',
        'tournament_id': this.$store.state.Tournament.tournamentId,
        'role_slug': this.$store.state.Users.userDetails.role_slug,
        'age_category': '',
        'age_category_filter': '',
        'selected': null,
        'value': '',
        'options': [],
        'grpsView': [],
        'grps': [],
        'fileUpload' : '',
        'availableGroupsTeam': [],
        'selectedGroupsTeam': [],
        'beforeChangeGroupName': '',
        'section': 'teams',
        'filterStatus': true,
        'seleTeam':'De-select',
        'canUploadTeamFile': true,
        'teamsInEdit': {},
        'countries': [],
        'clubs': [],
        'teamColors': [],
        'nonExistingAgeCategories': [],
        'teamNotMatchingAgeCategories': [],
        'teamsNotUploadedOfAgeCategory': [],
        'teamsInDifferentAgeCategory': [],
        'notProcessedAgeCategoriesDueToResultEntered': [],
        'notProcessedAgeCategoriesDuetoSameTeamInUploadSheet': [],
        'resultEnteredTeams': [],
        'ageCategoryHasNoTeams': false,
        'currentStep': 1,
        'dragFrom':'',
        'dragFromTeamId':0,
        'isFileUploaded': false,
        currentLayout: this.$store.state.Configuration.currentLayout,
      }
    },

    components: {
      TournamentFilter,
      teamSelect,
      TeamModal,
    },
    computed: {
      tournamentFilter: function() {
        return this.$store.state.Tournament.tournamentFiler
      },
      loggedInUserRole() {
        if(this.role_slug == 'Super.administrator' || this.role_slug == 'tournament.administrator' || this.role_slug == 'Internal.administrator' || this.role_slug == 'Master.administrator') {
          return true;
        }
        return false;
      },
      competitionList()
      {
        return this.$store.state.Tournament.competationList
      },
    },
    filters: {
      truncate: function(string, value) {
        if(string.length <= value) {
            return string;
        } else {
            return string.substring(0, value) + '...';
        }
      },
    },
    mounted() {
      let tournamentId = this.$store.state.Tournament.tournamentId
        if(tournamentId == null || tournamentId == '') {
          toastr['error']('Please Select Tournament', 'Error');
          this.$router.push({name: 'welcome'});
        } else {
            // First Set Menu and ActiveTab
          let currentNavigationData = {activeTab:'teams_groups', currentPage: 'Teams And Groups'}
            this.$store.dispatch('setActiveTab', currentNavigationData)
        }
      this.getAgeCategories()
      // this.getTeams()
      let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId}
      Tournament.getCompetationFormat(TournamentData).then(
        (response) => {
            this.options = response.data.data
        },
        (error) => {
        }
        )
        $('#profile_image_file').click(function(){
          $('#fileUpload').trigger('click')
        })

      this.getTeams()
      this.fetchAllCountries();
      this.fetchAllClubs();
      this.fetchAllTeamColors();
      // let fixmeTop = $('#age_category_block').offset().top;
      // let HeaderHeight = $('.site-header').height();
      // let fixmeTopOffset = fixmeTop - 197;
      // $(window).scroll(function() {
      //   let categoryDivHeight = $('#age_category_block').height();
      //   let teamTableHeight = $(window).height() - (fixmeTop);
      //   let currentScroll = $(window).scrollTop();
      //   if ($('.team-table').height() > teamTableHeight) {
      //     if((currentScroll) < fixmeTopOffset) {
      //       $('#age_category_block').removeClass("is-fixed");
      //       $('.team-table').css({"padding-top": 0});
      //     } else {
      //       let fixmeHeight= ($('#age_category_block').height() + 49);
      //       $('.team-table').css({"padding-top": fixmeHeight});
      //       $('#age_category_block').addClass("is-fixed");
      //     }
      //   } else {
      //     $('#age_category_block').removeClass("is-fixed");
      //     $('.team-table').css({"padding-top": 0});
      //   }
      // });
    },
    created: function() {
      this.$root.$on('getTeamsByTournamentFilter', this.setFilter);
      this.$root.$on('updateTeamList', this.getTeams);
      // this.$root.$on('onAssignGroup', this.onAssignGroup);
      // this.$root.$on('beforeChange', this.beforeChange);
    },
    beforeCreate: function() {
      // Remove custom event listener
      this.$root.$off('getTeamsByTournamentFilter');
      this.$root.$off('updateTeamList');
    },
    // watch: {
    // // whenever question changes, this function will run
    //     selectedGroupsTeam: function (newQuestion) {
    //     this.answer = 'Waiting for you to stop typing...'
    //     this.getAnswer()
    //   }
    // },

    methods: {
      groupFlag(group,no){
        let vm =this

        let fullName = null
        if(typeof group['groups']['actual_group_name'] != "undefined") {
          let actualGroupName = group['groups']['actual_group_name'];
          fullName = actualGroupName + '-' + no;
        } else {
          fullName = group['groups']['group_name']+no;
        }

        let displayName = fullName
         _.find(this.teams, function(team) {
          if(team.age_group_id == vm.age_category.id && fullName == team.group_name){
            displayName =  'flag-icon flag-icon-'+team.country_flag
          } ;
        });
        return displayName
      },
       groupName(group,no){
        let vm =this;
        let groupName = this.getGroupPlaceHolderName(group, no);
        let displayId = 0;
        let displayName = groupName.fullName;
        let actualFullName = groupName.actualFullName;
        let isHolderName = true;

        _.find(this.teams, function(team) {
          if(team.age_group_id == vm.age_category.id && actualFullName == team.group_name){
            displayId =  team.id
            displayName =  team.name
            // isHolderName = false;
          }
        });
        return {'displayId': displayId, 'displayName': displayName, 'isHolderName': isHolderName}
      },
      getGroupPlaceHolderName(group, no) {
        let fullName = null
        let actualFullName = null
        if(typeof group['groups']['actual_group_name'] != "undefined") {
          actualFullName = group['groups']['actual_group_name'] + '-' + no;
          let actualGroupName = group['groups']['actual_group_name'].split('-');
          fullName = actualGroupName[0] + '-' + no;
        } else {
          fullName = actualFullName = group['groups']['group_name']+no;
        }
        let groupName = {};
        groupName.fullName = fullName;
        groupName.actualFullName = actualFullName;
        return groupName;
      },
      initialfunc(id){
        if($('#sel_'+id).find('option:selected').text()!=''){
          this.onAssignGroup(id)
        }
      },
      setFileName(file, event) {
        this.isFileUploaded = true;
        this.canUploadTeamFile = true;
        var extensionsplit = event.target.files[0].name.split(".");
        var extension = extensionsplit[extensionsplit.length - 1];
        if(extension != 'xls' && extension != 'xlsx' && extension != 'csv') {
          this.canUploadTeamFile = false;
        }
        var filename = $('#fileUpload').val();
        var lastIndex = filename.lastIndexOf('\\');

        if (lastIndex >= 0) {
          filename = filename.substring(lastIndex + 1);
        }
        $('#filename').text(filename);
      },
      setFilter(filterKey,filterValue) {
        this.tournamentFilter.filterKey = filterKey
        if(filterKey == 'age_category'){
          this.onSelectAgeCategory('filter',filterValue.tournament_template_id)
        }
        this.getTeams()
      },
      selectTrue(team_group,index,assigned_group){
        if(team_group+index == assigned_group){
          return true
        }else{
          return false
        }
        beforeChange()
      },
      beforeChange(gid) {
        let gdata = $('#sel_'+gid).find('option:selected').val()
        this.beforeChangeGroupName =  gdata;
      },
      onAssignGroup(id) {
        let groupValue = $('#sel_'+id).find('option:selected').val()
        if(groupValue == '') {
          $('#sel_'+id+' .blnk').html('')
        }
        /*if(groupValue!='' && groupValue!= undefined ){
            $(".selTeams option").filter('[value='+ $('#sel_'+id).val() +']').not($('.sel_'+id)).prop("disabled",true);
        }*/
        if(this.beforeChangeGroupName!=''){
          $(".selTeams option").filter('[value='+ this.beforeChangeGroupName +']').prop("disabled", false);
        }
        if(groupValue != null && groupValue != '')  {
          this.selectedGroupsTeam.push(groupValue)
        } else {
          // this.selectedGroupsTeam = [];
        }
        var index = this.availableGroupsTeam.indexOf(groupValue);
        if (index > -1) {
          this.availableGroupsTeam.splice(index, 1);
        }
        document.activeElement.blur();
        
      },
      assignTeamGroupName(id,val) {
        _.map(this.teams, function(team){
          if (id == team.id) {
            team.group_name = val;
          }
        });
      },
      getTeams() {
        this.teams = ''
        let ageCategoryId = this.age_category !== '' ? this.age_category.id : '';
        let ageCategoryName = this.age_category !== '' ? this.age_category.category_age : '';
        let teamData = {'tournamentId':this.tournament_id, 'ageCategoryId' : ageCategoryId, 'filterKey':'age_category', 'filterValue': ageCategoryId};
        Tournament.getTeams(teamData).then(
          (response) => {
            this.ageCategoryHasNoTeams = response.data.data.length == 0 ? true : false;
            this.teams = response.data.data
            this.resultEnteredTeams = response.data.resultEnteredTeams;
            this.$store.dispatch('SetTeams',this.tournament_id);
            let that = this

            // usage as a promise (2.1.0+, see note below)
            Vue.nextTick()
              .then(function () {
                setTimeout(function(){
                  $('.selTeams').each(function( index ) {
                    that.initialfunc($(this).data('id'))
                  });
                  $('.selTeams').prop("disabled", false);
                  $('.selTeams').select2({ minimumResultsForSearch: Infinity });
                }, 500);
              });
          },
          (error) => {
          }
        )
      },
      groupUpdate() {
        let vm = this;
        let grpMain=[]
        // let teamAssign  = new FormData($("#frmTeamAssign")[0]);
        let teamAssign1  = $("#frmTeamAssign").serializeArray();
        let error = false

        _.find(this.grps, function(group) {
         // console.log(group)

         let grp= []
          $('.selTeams').each( function() {
            if(group.groups.group_name == $(this).find('option:selected').text()){
              grp.push($(this).data('id'))
            }
            // console.log($(this).val())
          })
          if(grp.length > group.group_count){
            error = true
            toastr['error']('You are assigning more team  in '+ group.groups.group_name+' . please reassign team.', 'Error');
            return false
          }
          // console.log(grp.length,group.group_count,'11')
          grpMain.push(grp.join(','))

        });
        let teamData = {'teamdata': teamAssign1,'group' : grpMain ,'tournament_id':this.tournament_id, 'age_group':this.age_category.id }
        $("body .js-loader").removeClass('d-none');
        if(error == false){
          Tournament.assignGroups(teamData).then(
          (response) => {
            if(response.data.status_code == '200') {
              toastr['success']('Teams are allocated successfully', 'Success');
            } else {
              toastr.error(response.data.message, 'Error', {timeOut: 2000});
            }
            vm.getTeams();
            $("body .js-loader").addClass('d-none');
          },
          (error) => {
            vm.getTeams();
            $("body .js-loader").addClass('d-none');
          }
        )
        }

      },
      getAgeCategories() {
        let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId}
        Tournament.getCompetationFormat(TournamentData).then(
          (response) => {
            this.options = response.data.data
          },
          (error) => {
          }
        )
      },
      onSelectAgeCategory(stype,tId = '') {
        this.selectedGroupsTeam = [];
        let tournamentTemplateId = ''
        let type = stype
        let templateId = ''
        if(this.age_category != ''){
          // console.log('asda')
           type = 'filter'
        }

        if(tId == ''){
           templateId = this.age_category.tournament_template_id
        }else{
           templateId = tId
        }
        let tournamentFilter = {'filterKey': 'age_category', 'filterValue':this.age_category, 'filterDependentKey': '', 'filterDependentValue': '' }
        this.$store.dispatch('setTournamentFilter', tournamentFilter);
        if(type == 'view'){
          if(this.age_category == ''){
            this.teams = [];
            this.grpsView = []
            // return false;
          }
          tournamentTemplateId = this.age_category.tournament_template_id
        }else{
          tournamentTemplateId = templateId
        }

        if(this.age_category!= '' && this.age_category.tournament_template_id == null) {
          let ageCategoryTemplateJson = this.age_category.template_json_data;
          this.getTournamentTemplate(ageCategoryTemplateJson);
          this.beforeChangeGroupName = ''
          this.getTeams()
          return true;
        }

        // console.log(tournamentTemplateId,'tid')
        if(tournamentTemplateId != undefined && tournamentTemplateId != '' )
        {
          // Now here Fetch the appopriate Template of it
          let TemplateData = {tournamentTemplateId : tournamentTemplateId, ageCategoryId: this.age_category.id}

          Tournament.getTemplate(TemplateData).then (
            (response) => {
              //var JsonTemplateData = JSON.stringify(eval("(" + response.data.data + ")"));

              let jsonObj = response.data.data.json_data;
              this.getTournamentTemplate(jsonObj);
            },
            (error)=> {
              toastr['error']('error in getting json data.', 'Error');
              // alert('error in getting json data')
            }
           )
        }
        this.beforeChangeGroupName = ''
        this.getTeams()
      },
      csvImport() {
        let vm = this;
        this.nonExistingAgeCategories = [];
        this.teamNotMatchingAgeCategories = [];
        this.teamsNotUploadedOfAgeCategory = [];
        this.teamsInDifferentAgeCategory = [];
        this.notProcessedAgeCategoriesDueToResultEntered = [];
        this.notProcessedAgeCategoriesDuetoSameTeamInUploadSheet = [];
        if($('#fileUpload').val()!='') {
          if(this.canUploadTeamFile == false) {
            toastr['error']('Please upload an excel file.', 'Error');
            return;
          }
          let files  = new FormData($("#frmCsvImport")[0]);
          files.append('tournamentId', this.tournament_id);
          files.append('teamSize', this.teamSize);
          this.filterStatus = false;
          $("body .js-loader").removeClass('d-none');
          axios.post('/api/team/create', files).then(response =>  {
            if (response.data.status_code == 422) {
                toastr['error'](response.data.message, 'Error');
            } else {
              let errorFlag = false;
              if(response.data.nonExistingAgeCategories.length > 0 || response.data.teamNotMatchingAgeCategories.length > 0 || Object.keys(response.data.teamsNotUploadedOfAgeCategory).length > 0 || Object.keys(response.data.teamsInDifferentAgeCategory).length > 0 || response.data.notProcessedAgeCategoriesDueToResultEntered.length > 0 || response.data.notProcessedAgeCategoriesDuetoSameTeamInUploadSheet.length > 0) {
                errorFlag = true;
                vm.nonExistingAgeCategories = response.data.nonExistingAgeCategories;
                vm.teamNotMatchingAgeCategories = response.data.teamNotMatchingAgeCategories;
                vm.teamsNotUploadedOfAgeCategory = response.data.teamsNotUploadedOfAgeCategory;
                vm.teamsInDifferentAgeCategory = response.data.teamsInDifferentAgeCategory;
                vm.notProcessedAgeCategoriesDueToResultEntered = response.data.notProcessedAgeCategoriesDueToResultEntered;
                vm.notProcessedAgeCategoriesDuetoSameTeamInUploadSheet = response.data.notProcessedAgeCategoriesDuetoSameTeamInUploadSheet;
                $('#team_upload_summary').modal('show');
              }
              if(!errorFlag) {
                toastr['success']('Teams are uploaded successfully', 'Success');
              }
            }
            this.filterStatus = true;
            this.getTeams();
            $("body .js-loader").addClass('d-none');
          }).catch(error => {});
          $('#filename').text('');
          $("#fileUpload").val(null);
        } else {
          toastr['error']('Please upload an excel file.', 'Error');
        }
      },
      editTeamName: function(event, teamId, teamName){
        Vue.set(this.teamsInEdit, teamId, _.clone(teamName));
        Vue.nextTick(function() {
          $(event.target).closest('.team-edit-section').find('input')[0].focus();
        });
      },
      cancelTeamNameChange: function(teamId) {
        let vm = this;
        _.map(this.teams, function(o) {
          if(o.id === teamId) {
            o.name = vm.teamsInEdit[teamId];
            Vue.delete(vm.teamsInEdit, teamId);
          }
        });
      },
      saveTeamNameChanges: function(event, teamId, teamName) {
        if(teamName.trim() === '') {
          toastr['error']('Please enter team name.', 'Error');
          $(event.target).closest('.team-edit-section').find('input')[0].focus();
          return false;
        }
        let teamData = {'team_id': teamId, 'team_name': teamName}
        Tournament.changeTeamName(teamData).then(
          (response) => {
            Vue.delete(this.teamsInEdit, teamId);
            toastr['success']('Team name have been changed successfully', 'Success');
          },
          (error) => {
          }
        )
      },
      getGroupName(group) {
        let splitGroupName = group['name'].split('-');
        let competitionType = splitGroupName[0];
        if( competitionType == 'PM' ) {
          return group['groups']['group_name'].replace('Group-', '')
        }
        return group['groups']['group_name']
      },
      getModifiedDisplayGroupName(groupName) {
        if(groupName != null && groupName.indexOf('Pos') !== -1) {
          let name = groupName.split('-');
          return name[0] + '-' + name[2]
        }
        if(groupName != null) {
          return groupName.replace('Group-','');
        }
        return groupName;
      },
      editTeam(id) {
        this.teamId = id
        let vm = this

        // usage as a promise (2.1.0+, see note below)
        Vue.nextTick()
          .then(function () {
            $('#team_form_modal').modal('show');
            vm.$root.$emit('editTeamData',  id);
          });
      },
      fetchAllCountries() {
        Tournament.getAllCountries().then(
          (response) => {
            this.countries = response.data.countries
          },
            (error)=> {
            }
          );
      },
      fetchAllClubs() {
        Tournament.getAllClubs().then(
          (response) => {
            this.clubs = response.data.clubs
          },
          (error) => {

          }
        )
      },
      resetAllTeams() {
        let data = {'ageCategoryId':this.age_category.id,'tournamentId':this.tournament_id};
        Tournament.getResetTeams(data).then(
          (response) => {
            if(response.data.status === 'success') {
              this.$root.$emit('updateTeamList');
              // this.selectedGroupsTeam = [];
              toastr['success']('All teams are deleted successfully', 'Success');
            }
            if(response.data.status === 'error') {
              toastr['error'](response.data.message, 'Error');
            }
            $("#reset_modal").modal("hide");
          },
          (error) => {
          }
        )
      },
      fetchAllTeamColors() {
        Tournament.getAllTeamColors().then(
          (response) => {
            this.teamColors = response.data
          },
            (error)=> {
            }
          );
      },
      downloadTeamsSpreadsheetSample() {
        Tournament.getSignedUrlForTeamsSpreadsheetSampleDownload().then(
          (response) => {
            window.location.href = response.data;
          },
          (error) => {
        });
      },
      previewSpredsheetSample() {
        $('#teams_groups_preview_modal').modal('show');
      },
      allowDrop(ev) {
        ev.preventDefault();
      },
      onTeamDrop(ev) {
        $('#' + ev.dataTransfer.getData("id")).removeClass('is-active');
        ev.preventDefault();
        let teamId = ev.dataTransfer.getData("id");
        let teamSelectId = $('#' + teamId).attr('data-select-id');

        // let secondTeamId = ev.target.id;
        let secondTeamId = $(ev.target.closest('.draggable-item')).attr('id');
        let secondteamSelectId = $('#' + secondTeamId).attr('data-select-id');

        let teamGroupName = $('#' + secondTeamId).attr('data-group-name');
        let secondTeamGroupName = $('#' + teamId).attr('data-group-name');

        $('#sel_' + teamSelectId).val(teamGroupName);
        $('#sel_' + teamSelectId).trigger('select2:select');

        //this.assignTeamGroupName(teamSelectId, $('#sel_' + teamSelectId).val());
        this.assignTeamGroupName(teamSelectId, $('#' + secondTeamId).attr('data-group-name'));
        this.onAssignGroup($('#' + teamId).attr('data-select-id'));

        if(this.dragFrom == 'group')
        {
          $('#sel_' + secondteamSelectId).val(secondTeamGroupName);
          $('#sel_' + secondteamSelectId).trigger('select2:select');

          //this.assignTeamGroupName(teamSelectId, $('#sel_' + teamSelectId).val());
          this.assignTeamGroupName(secondteamSelectId, $('#' + teamId).attr('data-group-name'));
          this.onAssignGroup($('#' + secondTeamId).attr('data-select-id'));
          //this.swapSecondTeamInGroup();
        }
        $('.selTeams').prop("disabled", false);
        $('.selTeams').select2({ minimumResultsForSearch: Infinity });
      },
      onTeamDrag(ev,section) {
        $('#' + ev.target.id).addClass('is-active');
        ev.dataTransfer.setData("id", ev.target.id);
        this.beforeChange($('#' + ev.target.id).data('select-id'));
        this.dragFrom= section;
      },
      printAllocatedTeams() {
        let data = 'tournamentId='+this.$store.state.Tournament.tournamentId+'&'+'ageCategoryId='+this.age_category.id+'&'+'tournamentTemplateId='+this.age_category.tournament_template_id;

        if(data != ''){
          Tournament.getSignedUrlForGroupsViewReport(data).then(
            (response) => {
              window.location.href = response.data;
            },
            (error) => {

            }
          )
        } else{
          toastr['error']('Records not available', 'Error');
        }
      },
      getTournamentTemplate(templateJson) {
        //let JsonTemplateData  = response.data.data
        // Now here we put data over there as per group
         let jsonObj = JSON.parse(templateJson)
         let jsonCompetationFormatDataFirstRound = jsonObj['tournament_competation_format']['format_name'][0]['match_type']
         let availGroupTeam = []
         let vm = this;
         // if(type == 'filter'){
            this.grps = []
            this.grpsView = []
            _.forEach(jsonCompetationFormatDataFirstRound, function(group) {
              let splitGroupName = group.name.split('-');
              let competitionType = splitGroupName[0];

              if(competitionType == 'PM' && typeof group.consider_in_team_assignment == "undefined") {
                return;
              }

              let groupName = null;
              if(competitionType == 'PM' && group.consider_in_team_assignment == 1) {
                groupName = group.groups.actual_group_name + '-';
              } else {
                groupName = group.groups.group_name;
              }

              for(var i = 1; i <= group.group_count; i++ ){
                availGroupTeam.push(groupName+i)
              }

              vm.grpsView.push(group);
              vm.grps.push(group);

            });
         // }else{
            // this.grpsView = jsonCompetationFormatDataFirstRound
            // _.forEach(this.grpsView, function(group) {
            //   for(var i = 1; i <= group.group_count; i++ ){
            //     // let gname = group.groups.group_name+i
            //     availGroupTeam.push(group.groups.group_name+i)
            //   }

            // });
         // }


        this.availableGroupsTeam = availGroupTeam
        this.teamSize = jsonObj.tournament_teams
      },
      checkForTeamUploadError(teamError) {
        return Object.keys(teamError).length > 0;
      },
      canChangeTeamOption(teamId) {
        return _.indexOf(_.values(this.resultEnteredTeams), parseInt(teamId)) === -1;
      },
      allocateTeams(ageCategoryId) {
        let vm = this;
        let data = 'ageCategoryId='+ageCategoryId;
        $("body .js-loader").removeClass('d-none');
        Tournament.allocateTeamsAutomatically(data).then(
          (response) => {
            if(response.data.status_code == '200') {
              toastr['success']('Teams are allocated successfully', 'Success');
            } else {
              toastr.error(response.data.message, 'Error', {timeOut: 2000});
            }
            vm.getTeams();
            $("body .js-loader").addClass('d-none');
          },
          (error) => {
            vm.getTeams();
            $("body .js-loader").addClass('d-none');
          }
        )
      },
      next() {
        let currentNavigationData = {activeTab:'tournaments_summary_details', currentPage: 'Administration'}
        this.$store.dispatch('setActiveTab', currentNavigationData)
        this.$router.push({name:'tournaments_summary_details'})
      },
      nextStep() {
        $('#step'+this.currentStep).addClass('d-none');
        this.currentStep = this.currentStep + 1;
        $('#step'+this.currentStep).removeClass('d-none');
      },
      back() {
        $('#step'+this.currentStep).addClass('d-none');
        this.currentStep = this.currentStep - 1;
        $('#step'+this.currentStep).removeClass('d-none');
      }
    }
  }
</script>
