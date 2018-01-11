<template>
  <div class="tab-content">
  	<div class="card">
  		<div class="card-block">
  			<h6><strong>{{$lang.teams_terms_groups}}</strong></h6>
  			<form>
        <div class="form-group row">
          <label class="col-sm-2 form-control-label">Import teams</label>
         <div class="col-sm-10">
            <form method="post" name="frmCsvImport" id="frmCsvImport" enctype="multipart/form-data">
            <div class="row">
              <div class="col align-self-center">
                <div class="row">
                  <div class="col-sm-4">
                    <button type="button" class="btn btn-default w-100 btn-color-black--light" id="profile_image_file">Select list (excel files only)</button>
                  </div>
                  <div class="col">
                    <span id="filename"></span>
                    <button type="button" @click="csvImport()"  class="btn btn-primary ml-4">Upload teams
                    </button>
                  </div>
                </div>
                  <input type="file" name="fileUpload" @change="setFileName(this,$event)"  id="fileUpload" style="display:none;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,application/excel,application/vnd.ms-excel,application/vnd.msexcel,text/anytext,application/txt">
              </div>
            </div>
          </form>
          </div>
        </div>
  				<div class="form-group row">
            <label class="col-sm-2 form-control-label">Filter by age category</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <select class="form-control" v-model="age_category" v-on:change="onSelectAgeCategory('view')">
                      <option value="">{{$lang.teams_all_age_category}}</option>
                      <option v-for="option in options"
                       v-bind:value="option"> {{option.group_name}} ({{option.category_age}})</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
  			</form>
        <div class="block-bg age-category mb-4">
          <div class="d-flex flex-row flex-wrap justify-content-center" v-if="grpsView.length != 0">
            <div class="col-sm-3 my-2"  v-for="(group, index) in grpsView">
              <div class="m_card hoverable h-100 m-0">
                <div class="card-content">
                   <span class="card-title text-primary"><strong>
                   {{ getGroupName(group) }}</strong></span>
                    <p class="text-primary left" v-for="n in group['group_count']"><strong><span :class="groupFlag(group,n)" ></span>
                    {{groupName(group,n) | truncate(20)}}</strong></p>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="d-flex justify-content-center">
            <div class="col-sm-9  m-8">
                <div class="card-content">
                   <span class="card-title"> {{ $lang.teams_name_select }}</span>
                </div>
            </div>
          </div>
        </div>
<!--
          <div class="row align-items-center">
            <div class="col-sm-3">
              <h6 class="m-0"><strong>{{$lang.teams_team_list}}</strong></h6>
            </div>
            <div class="col-sm-12">
              <tournamentFilter v-if="filterStatus" :section="section"></tournamentFilter>
            </div>
          </div>
          <div class="row">

  			</div> -->
  			<div class="row mt-4">
  				<div class="col-md-12">
          <form name="frmTeamAssign" id="frmTeamAssign">
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
                  </tr>
              </thead>
                <tbody v-if="teams.length!=0">
                    <tr v-for="(team, index) in teams">
                      <td width="150px">{{team.esr_reference}}</td>
                      <td class="team-edit-section">
                        <div class="custom-d-flex align-items-center justify-content-between" v-show="!(team.id in teamsInEdit)">
                          <span>{{team.name}}</span>
                          <span class="pull-right"><a href="javascript:void(0);" v-on:click="editTeamName($event, team.id, team.name)"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                        </div>
                        <div v-show="(team.id in teamsInEdit)">
                          <div class="btn-group btn-group-sm w-100" role="group">
                            <input type="text" class="form-control" v-model="team.name" />
                            <a href="javascript:void(0);" v-on:click="cancelTeamNameChange(team.id)" class="btn btn-secondary d-inline-flex align-items-center"><i class="fa fa-times text-center text-danger" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" v-on:click="saveTeamNameChanges($event, team.id, team.name)" class="btn btn-secondary d-inline-flex align-items-center"><i class="fa fa-check text-center text-primary" aria-hidden="true"></i></a>
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

                      <td width="130px" v-if="age_category != ''" style="position: relative">
                        <teamSelect :team="team" :grps="grps" @onAssignGroup="onAssignGroup" @beforeChange="beforeChange" @assignTeamGroupName="assignTeamGroupName"></teamSelect>
                      </td>
                      <td width="130px" v-else>{{ getModifiedDisplayGroupName(team.group_name) }}</td>
                    </tr>

                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="7"> No teams available</td>
                    </tr>
                </tbody>
            </table>
            <button type="button"  v-if="tournamentFilter.filterKey == 'age_category'" @click="groupUpdate()" class="btn btn-primary pull-right">{{$lang.teams_button_updategroups}}</button>
          </form>
  				</div>
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

   import Vue from 'vue'

   // Vue.filter('groupName', function (value) {
   //    return value+'hi '
   //  })
	export default {
    data() {
    return {
        'teamSize': 5,
        'teams': [],
        // 'teamsIdList': '',
        'tournament_id': this.$store.state.Tournament.tournamentId,
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
        // 'tournamentFilter':{
        //   'filterKey':'team',
        //   'filterValue': ''
        // }

        }
    },

    components: {
      TournamentFilter,
      teamSelect
    },
    computed: {
       tournamentFilter: function() {
        return this.$store.state.Tournament.tournamentFiler
      }
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

    },
    created: function() {
      this.$root.$on('getTeamsByTournamentFilter', this.setFilter);
      // this.$root.$on('onAssignGroup', this.onAssignGroup);
      // this.$root.$on('beforeChange', this.beforeChange);
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
        let vm =this
        let fullName = null
        let actualFullName = null
        if(typeof group['groups']['actual_group_name'] != "undefined") {
          actualFullName = group['groups']['actual_group_name'] + '-' + no;
          let actualGroupName = group['groups']['actual_group_name'].split('-');
          fullName = actualGroupName[0] + '-' + no;
        } else {
          fullName = actualFullName = group['groups']['group_name']+no;
        }

        let displayName = fullName

        _.find(this.teams, function(team) {
          if(team.age_group_id == vm.age_category.id && actualFullName == team.group_name){
            displayName =  team.name
          } ;
        });
        return displayName
      },
      initialfunc(id){
        if($('#sel_'+id).find('option:selected').text()!=''){
          this.onAssignGroup(id)
        }
      },
      setFileName(file, event) {
        this.canUploadTeamFile = true;
        var extensionsplit = event.target.files[0].name.split(".");
        var extension = extensionsplit[extensionsplit.length - 1];
        if(extension != 'xls' && extension != 'xlsx') {
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
        $('.selTeams').prop("disabled", true);
        let groupValue = $('#sel_'+id).find('option:selected').val()
        if(groupValue == '') {
          //this.seleTeam = ''
          $('#sel_'+id+' .blnk').html('')
        }
        if(groupValue!='' && groupValue!= undefined ){
            $(".selTeams option").filter('[value='+ $('#sel_'+id).val() +']').not($('.sel_'+id)).prop("disabled",true);
        }
        if(this.beforeChangeGroupName!=''){
          $(".selTeams option").filter('[value='+ this.beforeChangeGroupName +']').prop("disabled", false);
        }
        if(groupValue != null && groupValue != '')  {
          this.selectedGroupsTeam.push(groupValue)
        }
        var index = this.availableGroupsTeam.indexOf(groupValue);
        if (index > -1) {
          this.availableGroupsTeam.splice(index, 1);
        }
        document.activeElement.blur();
        $('.selTeams').prop("disabled", false);
        $('.selTeams').select2({ minimumResultsForSearch: Infinity });
      },
      assignTeamGroupName(id,val) {
        _.map(this.teams, function(team){
          if (id == team.id) {
            team.group_name = val;
          }
        });
      },
      getTeams() {
        // if(this.age_category === '') {
        //   this.teams = [];
        //   return;
        // }
        this.teams = ''
        let ageCategoryId = this.age_category !== '' ? this.age_category.id : '';
        let teamData = {'tournamentId':this.tournament_id, 'ageCategoryId' : ageCategoryId, 'filterKey':'age_category', 'filterValue': ageCategoryId};
        // console.log(teamData,'td')
        Tournament.getTeams(teamData).then(
          (response) => {
            this.teams = response.data.data
            let that = this
            setTimeout(function(){
              $('.selTeams').each(function( index ) {
                that.initialfunc($(this).data('id'))
              })
            },1000)
          },
          (error) => {
          }
        )
        //  Tournament.getTeamsGroup(teamData).then(
        //   (response) => {
        //     this.teams = response.data.data
        //   },
        // (error) => {
        //    console.log('Error occured during Tournament api ', error)
        // }
        // )
      },
      groupUpdate() {
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
            $("body .js-loader").addClass('d-none');
          },
          (error) => {
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
        // console.log(tournamentTemplateId,'tid')
        if(tournamentTemplateId != undefined && tournamentTemplateId != '' )
        {
          // Now here Fetch the appopriate Template of it
          let TemplateData = {tournamentTemplateId : tournamentTemplateId}

          Tournament.getTemplate(TemplateData).then (
            (response) => {
              //var JsonTemplateData = JSON.stringify(eval("(" + response.data.data + ")"));

              let jsonObj = JSON.parse(response.data.data.json_data)
              //let JsonTemplateData  = response.data.data
              // Now here we put data over there as per group
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
        if($('#fileUpload').val()!=''){
          if(this.canUploadTeamFile == false) {
            toastr['error']('Please upload an excel file.', 'Error');
            return;
          }
          let files  = new FormData($("#frmCsvImport")[0]);
          // files.append('ageCategory', this.age_category.id);
          files.append('tournamentId', this.tournament_id);
          files.append('teamSize', this.teamSize);
          // let uploadFile = document.getElementById('frmCsvImport');
          this.filterStatus = false
           axios.post('/api/team/create',files).then(response =>  {
          if(response.data.bigFileSize == true){
            toastr['error']('Total Team size is more than available. Only top '+this.teamSize+' teams have been added.', 'Error');
          }else{
            toastr['success']('teams are uploaded successfully', 'Success');
          }
          this.filterStatus = true
          this.getTeams()
          }).catch(error => {

          });
        }else{
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
    }
  }
</script>
