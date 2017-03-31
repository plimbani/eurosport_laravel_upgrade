<template> 
  <div class="tab-content">
  	<div class="card">
  		<div class="card-block">
  			<h6><strong>{{$lang.teams_terms_groups}}</strong></h6>
  			<form>
  				<div class="form-group row">
                  <label class="col-sm-2 form-control-label">{{$lang.teams_age_category}}</label>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <select class="form-control ls-select2" v-model="age_category" v-on:change="onSelectAgeCategory">
	                            <option value="">{{$lang.teams_select_age_category}}</option>
	                            <option v-for="option in options" 
                               v-bind:value="option"> {{option.group_name}}</option>
	                        </select>
	                    </div>
                    </div>
                </div>
  			</form>
        <div class="block-bg age-category">
          <div class="d-flex justify-content-center align-items-center">
          <div class="col-sm-3" v-for="(group, index) in grps">
            <div class="m_card hoverable">
                  <div class="card-content">
                     <span class="card-title">{{group['groups']['group_name']}}</span>
                     <p v-for="n in group['group_count']">{{group['groups']['group_name']}}{{n}}</p>
                  </div>
            </div>      
          </div>
          </div>
        </div>  
<!--   			<div class="block-bg age-category">
  				<div class="d-flex justify-content-center align-items-center">
  					<div v-for="(group, index) in grps">

            
              <div class="m_card hoverablex ">
  						  <div class="card-content">
  							  <span class="card-title">
                  {{group['groups']['group_name']}}</span>
                  <p v-for="n in group['group_count']">
                   {{group['groups']['group_name']}}{{n}}
                  </p>
      					</div>

    					</div>
            </div>
            </div>
  			</div> -->
  			<div class="clearfix">
  				<div class="pull-left">
	  				<div class="mt-4"><strong>{{$lang.teams_team_list}}</strong></div>
            <form method="post" name="frmCsvImport" id="frmCsvImport" enctype="multipart/form-data">

            <div >
            
              <input type="file" name="fileUpload"  id="fileUpload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
              <p class="help-block">Only excel and csv allowed.</p>
            </div>
	  				<button type="button" @click="csvImport()" :disabled="age_category==''"  class="btn btn-primary">{{$lang.teams_upload_team}}</button>
            </form>
            
	  			</div>
	  			<div class="pull-right mt-4">
	  				<form  class="form-inline filter-category-form" >
	  					<div class="form-group">
	  						<label for="nameInput" class="control-label"><strong>{{$lang.teams_filter}}</strong></label> 
	  						<label class="radio-inline control-label">
  								<input type="radio" name="gender" value="team" checked="checked">{{$lang.teams_team}}
                            </label>
                             <label class="radio-inline control-label">
                            	<input type="radio" name="gender" value="country">{{$lang.teams_country}}
                            </label>
                            <label class="radio-inline control-label">
                            	<input type="radio" name="gender" value="age category">{{$lang.teams_age}}
                            </label>
                            <select class="form-control ls-select2">
	                            <option value="">{{$lang.teams_select_location}}</option>
	                            <option value="">{{$lang.teams_select_location_1}}</option>
	                            <option value="">{{$lang.teams_select_location_2}}</option>
	                            <option value="">{{$lang.teams_select_location_3}}</option>
	                            <option value="">{{$lang.teams_select_location_4}}</option>
	                            <option value="">{{$lang.teams_select_etc}}</option>
	                        </select>
	                        <label class="control-label">
	                        	<a href="#">{{$lang.teams_clear}}</a>
	                        </label>
	  					</div> 
	  					
	  				</form>
	  			</div>
  			</div>
  			<div class="row mt-4">
  				<div class="col-md-12">
          <form name="frmTeamAssign" id="frmTeamAssign">
  					<table class="table add-category-table">
                        <thead>
                            <tr >
                                <th>{{$lang.teams_reference}}</th>
                                <th>{{$lang.teams_name}}</th>
                                <th>{{$lang.teams_country}}</th>
                                <th>{{$lang.teams_agecategory}}</th>
                                <th>{{$lang.teams_group}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :id="team.team_id" v-for="team in teams">
                              <td>{{team.esr_reference}}</td>
                              <td>{{team.name}}</td>
                              <td>
                              	<img :src="team.logo" width="20">{{team.country_name}} 
                              </td>
                              <td>{{team.age_name}}</td>
                              <td>
                                <select v-bind:data-id="team.id" v-bind:data-category-name="age_category.group_name" v-on:focus="beforeChange(team.id)" v-on:change="onAssignGroup(team.id)"  :name="'sel_'+team.id" :id="'sel_'+team.id" class="form-control ls-select2 selTeams">
			                            <option value="">Select Team</option>
                                  <optgroup :label="group.groups.group_name" v-for="group in grps">
                                    <option :class="'sel_'+team.id" v-for="(n,index) in group['group_count']" :disabled="isSelected(group['groups']['group_name'],n)" :value="group['groups']['group_name']+n" >{{group['groups']['group_name']}}{{n}}</option>
                                  </optgroup>
                               
		                            </select>
                              </td>
                            </tr>
                           

                        </tbody>
            </table>
            <button type="button" @click="groupUpdate()" class="btn btn-primary pull-right">{{$lang.teams_button_updategroups}}</button>
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

	export default {
    data() {
    return {
        'teamSize': 5,
        'teams': [],
        'tournament_id': this.$store.state.Tournament.tournamentId,
        'age_category': '',
        'selected': null,
        'value': '',
        'options': [],
        'grps': '',
        'fileUpload' : '',
        'availableGroupsTeam': [],
        'selectedGroupsTeam': [],
        'beforeChangeGroupName': ''

        }
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

    },
    // watch: {
    // // whenever question changes, this function will run
    //     selectedGroupsTeam: function (newQuestion) {
    //     this.answer = 'Waiting for you to stop typing...'
    //     this.getAnswer()
    //   }
    // },

    methods: {
      isSelected(grp,index){
        return false

      },
      beforeChange(gid) {
        let gdata = $('#sel_'+gid).val()
        this.beforeChangeGroupName =  gdata;
      },
      onAssignGroup(id) {
        let groupValue = $('#sel_'+id).val()
        // console.log(groupValue,'l')
        if(groupValue!=''){
          $(".selTeams option:contains("+$('#sel_'+id).val()+")").not( $('.sel_'+id)).attr("disabled","disabled");
        }
        if(this.beforeChangeGroupName!=''){
          $(".selTeams option:contains("+this.beforeChangeGroupName+")").removeAttr("disabled");  
        }
        
        this.selectedGroupsTeam.push(groupValue)
        var index = this.availableGroupsTeam.indexOf(groupValue);
        if (index > -1) {
          this.availableGroupsTeam.splice(index, 1);
        }
      },
       getTeams() {
        Tournament.getTeams(this.tournament_id).then(
          (response) => { 
            this.teams = response.data.data        
          },
        (error) => {
           console.log('Error occured during Tournament api ', error)
        }
        ) 
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
        let teamData = {'teamdata': teamAssign1,'group' : grpMain }
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
      onSelectAgeCategory() {

        let tournamentTemplateId = this.age_category.tournament_template_id

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
            this.grps = jsonCompetationFormatDataFirstRound
            let availGroupTeam = []
            _.forEach(this.grps, function(group) {
              for(var i = 1; i <= group.group_count; i++ ){
                // let gname = group.groups.group_name+i
                availGroupTeam.push(group.groups.group_name+i)
              }
                
            });
            this.availableGroupsTeam = availGroupTeam 
            this.teamSize = jsonObj.tournament_teams 

          }, 
          (error)=> {
            alert('error in getting json data')
          }
        )

      },
      csvImport() {
        if($('#fileUpload').val()!=''){
          let files  = new FormData($("#frmCsvImport")[0]);
          // console.log(files->)
          files.append('ageCategory', this.age_category.id);
          files.append('tournamentId', this.tournament_id);
          files.append('teamSize', this.teamSize);
          // let uploadFile = document.getElementById('frmCsvImport');
           
          // console.log(document.getElementById('frmCsvImport'))
          // Tournament.createTeam(TData).then(
          //   (response) => {           
          //    this.getTeams()                     
          //   },
          //   (error) => {
          //      console.log('Error occured during Tournament api ', error)
          //   }
          // )
          return axios.post('/api/team/create',files).then(response =>  {
          if(response.data.bigFileSize == true){
            toastr['error']('Total Team size is more than available. Only top '+this.teamSize+' teams have been added.', 'Error');
          }
            
          this.getTeams()
                                // this.pitchId = response.data.pitchId
          }).catch(error => {
              
          });  
        }

      }

    }
  }
</script>