<template>
  <div class="tab-content">
  	<div class="card">
  		<div class="card-block">
  			<h6><strong>{{$lang.teams_terms_groups}}</strong></h6>
  			<form>
  				<div class="form-group row">
            <label class="col-sm-2 form-control-label">{{$lang.teams_name_category}}</label>
            <div class="col-sm-4">
              <div class="form-group">
                  <select class="form-control ls-select2" v-model="age_category" v-on:change="onSelectAgeCategory('view')">
                    <option value="">{{$lang.teams_select}}</option>
                    <option v-for="option in options"
                     v-bind:value="option"> {{option.group_name}} ({{option.category_age}})</option>
                  </select>
              </div>
            </div>
          </div>
  			</form>
        <div class="block-bg age-category mb-4">

          <div class="d-flex justify-content-center" v-if="grpsView.length != 0">
            <div class="col-sm-3 m_card hoverable m-2"  v-for="(group, index) in grpsView">
                <div class="card-content">
                   <span class="card-title text-primary"><strong>{{group['groups']['group_name']}}</strong></span>
                   <p class="text-primary" v-for="n in group['group_count']"><strong><span v-text="groupName(group['groups']['group_name'],n)"></span></strong></p>
                </div>
            </div>
          </div>
          <div v-else class="d-flex justify-content-center">
            <div class="col-sm-9  m-8">
                <div class="card-content">
                   <span class="card-title"> Select a category name above to view information</span>

                </div>
            </div>
          </div>

        </div>

          <div class="row align-items-center">
            <div class="col-sm-3">
              <h6 class="m-0"><strong>{{$lang.teams_team_list}}</strong></h6>
            </div>
            <div class="col-sm-9">
              <tournamentFilter v-if="filterStatus" :section="section"></tournamentFilter>
            </div>
          </div>
          <div class="row">
          <div class="col-sm-12">
            <form method="post" name="frmCsvImport" id="frmCsvImport" enctype="multipart/form-data">
            <div>
            <button type="button" class="btn btn-default" id="profile_image_file">Choose file</button><span id="filename"></span>
              <input type="file" name="fileUpload" @change="setFileName(this)"  id="fileUpload" style="display:none;" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/csv,text/plain,application/csv,text/comma-separated-values,application/excel,application/vnd.ms-excel,application/vnd.msexcel,text/anytext,application/txt,text/tsv,text/csv,text/comma-separated-values" >
              <small class="form-text text-muted">Excel and CSV files only</small>

            </div>
	  				<button type="button" @click="csvImport()"  class="btn btn-primary mt-2">{{$lang.teams_upload_team}}

            </button>
            </form>
	  			</div>
  			</div>
  			<div class="row mt-4">
  				<div class="col-md-12">
          <form name="frmTeamAssign" id="frmTeamAssign">
  					<table class="table table-hover table-bordered">
              <thead>
                  <tr>
                      <th class="text-center">{{$lang.teams_reference}}</th>
                      <th class="text-center">{{$lang.teams_name}}</th>
                      <th class="text-center">{{$lang.teams_country}}</th>
                      <th class="text-center">{{$lang.teams_place}}</th>
                      <th class="text-center">{{$lang.teams_age_category}}</th>
                      <th class="text-center">{{$lang.teams_name_category}}</th>

                      <th class="text-center" v-if="tournamentFilter.filterKey == 'age_category'">{{$lang.teams_group}}</th>
                      <th class="text-center" v-else>{{$lang.teams_age_category_group}}</th>
                  </tr>
              </thead>
                <tbody v-if="teams.length!=0">
                    <tr  v-for="team in teams">
                      <td>{{team.esr_reference}}</td>
                      <td>{{team.name}}</td>
                      <td>
                      	<!-- <img :src="team.logo" width="20"> {{team.country_name}} -->
                            <span :class="'flag-icon flag-icon-'+team.country_flag"></span> {{team.country_name}}
                      </td>
                      <td>{{team.place}} </td>
                      <td>{{team.category_age}} </td>
                      <td>{{team.age_name}} </td>

                      <td v-if="tournamentFilter.filterKey == 'age_category'">
                        <select  v-bind:data-id="team.id" v-model="team.group_name" v-on:click="beforeChange(team.id)" v-on:change="onAssignGroup(team.id)"  :name="'sel_'+team.id" :id="'sel_'+team.id" class="form-control ls-select2 selTeams">
                          <option value="">Select Team</option>
                          <optgroup :label="group.groups.group_name" v-for="group in grps">
                            <option :class="'sel_'+team.id" v-for="(n,index) in group['group_count']" :disabled="isSelected(group['groups']['group_name'],n)"  :value="group['groups']['group_name']+n" >{{group['groups']['group_name']}}{{n}} </option>
                          </optgroup>
                        </select>
                      </td>
                      <td v-else>{{team.group_name}}</td>
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
        'grpsView': '',
        'grps': '',
        'fileUpload' : '',
        'availableGroupsTeam': [],
        'selectedGroupsTeam': [],
        'beforeChangeGroupName': '',
        'section': 'teams',
        'filterStatus': true,
        'tournamentFilter':{
          'filterKey':'team',
          'filterValue': ''
        }

        }
    },

    components: {
      TournamentFilter
    },
    // computed: {
    //   availableGroupsTeam: function() {
    //           return this.$store.state.Tournament.currentTotalTime
    //         },
    // },


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
            console.log('Error occured during Tournament api ', error)
        }
        )
        $('#profile_image_file').click(function(){
          $('#fileUpload').trigger('click')
        })

      this.getTeams(this.tournamentFilter.filterKey,this.tournamentFilter.filterValue)

    },
    created: function() {
      this.$root.$on('getTeamsByTournamentFilter', this.setFilter);
    },

    // watch: {
    // // whenever question changes, this function will run
    //     selectedGroupsTeam: function (newQuestion) {
    //     this.answer = 'Waiting for you to stop typing...'
    //     this.getAnswer()
    //   }
    // },

    methods: {
      groupName(grpName,no){
        let vm =this
        let fullName = grpName+no
        let displayName = fullName
         _.find(this.teams, function(team) {
          if(team.age_group_id == vm.age_category.id && fullName == team.group_name){
            displayName =  team.name
          } ;
        });
        return displayName
      },
      isSelected(grp,index){
        return false

      },
      initialfunc(id){
        if($('#sel_'+id).find('option:selected').text()!=''){
          this.onAssignGroup(id)
        }
      },
      setFileName(file) {
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
        this.getTeams(filterKey,filterValue)
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
        if(groupValue!='' && groupValue!= undefined ){
            $(".selTeams option:contains("+$('#sel_'+id).val()+")").not( $('.sel_'+id)).attr("disabled","disabled");
        }
        if(this.beforeChangeGroupName!=''){
          $(".selTeams option:contains("+this.beforeChangeGroupName+")").removeAttr("disabled");
        }
        if(groupValue != null && groupValue != '')  {
          this.selectedGroupsTeam.push(groupValue)
        }
        var index = this.availableGroupsTeam.indexOf(groupValue);
        if (index > -1) {
          this.availableGroupsTeam.splice(index, 1);
        }


      },
       getTeams(filterKey,filterValue) {
        this.teams = ''
         let teamData = {'tournamentId':this.tournament_id,'filterKey':filterKey, 'filterValue': filterValue};
        // console.log(teamData,'td')
        Tournament.getTeams(teamData).then(
          (response) => {
            this.teams = response.data.data

            _.forEach(response.data.data, function(key,team) {
             //  console.log(team.id)
             // this.teamsIdList=team.id
            });
          },
        (error) => {
           console.log('Error occured during Tournament api ', error)
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
        if(error == false){
          Tournament.assignGroups(teamData).then(
          (response) => {
            toastr['success']('Groups are assigned successfully', 'Success');
          },
          (error) => {
             console.log('Error occured during Tournament api ', error)
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
             console.log('Error occured during Tournament api ', error)
          }
        )
      },
      onSelectAgeCategory(type,templateId = '') {
        let tournamentTemplateId = ''

        if(type == 'view'){
          if(this.age_category == ''){
            this.grpsView = ''
            return false;
          }
          tournamentTemplateId = this.age_category.tournament_template_id
        }else{
          tournamentTemplateId = templateId
        }
        if(tournamentTemplateId != undefined && tournamentTemplateId != '' )
        {
          // Now here Fetch the appopriate Template of it
          let TemplateData = {tournamentTemplateId : tournamentTemplateId}

          Tournament.getTemplate(TemplateData).then (
            (response) => {
              //var JsonTemplateData = JSON.stringify(eval("(" + response.data.data + ")"));

              let jsonObj = JSON.parse(response.data.data)
              // console.log(jsonObj)
              //let JsonTemplateData  = response.data.data
              // Now here we put data over there as per group
               let jsonCompetationFormatDataFirstRound = jsonObj['tournament_competation_format']['format_name'][0]['match_type']
               let availGroupTeam = []
               if(type == 'filter'){
                  this.grps = jsonCompetationFormatDataFirstRound
                  _.forEach(this.grps, function(group) {
                    for(var i = 1; i <= group.group_count; i++ ){
                      // let gname = group.groups.group_name+i
                      availGroupTeam.push(group.groups.group_name+i)
                    }

                  });
               }else{
                  this.grpsView = jsonCompetationFormatDataFirstRound
                  _.forEach(this.grpsView, function(group) {
                    for(var i = 1; i <= group.group_count; i++ ){
                      // let gname = group.groups.group_name+i
                      availGroupTeam.push(group.groups.group_name+i)
                    }

                  });
               }


              this.availableGroupsTeam = availGroupTeam
              this.teamSize = jsonObj.tournament_teams

              let that = this


              setTimeout(function(){
                $('.selTeams').each(function( index ) {
                  that.initialfunc($(this).data('id'))
                })
              },1000)
            },
            (error)=> {
              toastr['error']('error in getting json data.', 'Error');
              // alert('error in getting json data')
            }
           )
       }
      },
      csvImport() {
        if($('#fileUpload').val()!=''){

          let files  = new FormData($("#frmCsvImport")[0]);
          // files.append('ageCategory', this.age_category.id);
          files.append('tournamentId', this.tournament_id);
          files.append('teamSize', this.teamSize);
          // let uploadFile = document.getElementById('frmCsvImport');

          this.filterStatus = false
           axios.post('/api/team/create',files).then(response =>  {
          if(response.data.bigFileSize == true){
            toastr['error']('Total Team size is more than available. Only top '+this.teamSize+' teams have been added.', 'Error');
          }
          this.filterStatus = true
          this.getTeams(this.tournamentFilter.filterKey,this.tournamentFilter.filterValue)
                                // this.pitchId = response.data.pitchId
          }).catch(error => {

          });
        }else{
           toastr['error']('Please upload csv file.', 'Error');
        }

      }

    }
  }
</script>
