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
  					<div v-for="(group, index) in grps">
            
            <div class="col-sm-3">
  						<div class="m_card hoverable">
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
            </div>
  			</div>
  			<div class="clearfix">
  				<div class="pull-left">
	  				<div class="mt-4"><strong>{{$lang.teams_team_list}}</strong></div>
            <form method="post" name="frmCsvImport" id="frmCsvImport" enctype="multipart/form-data">

            <div >
            
              <input type="file" name="fileUpload" id="fileUpload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
              <p class="help-block">Only excel and csv allowed.</p>
            </div>
	  				<button type="button" @click="csvImport()"  class="btn btn-primary">{{$lang.teams_upload_team}}</button>
            </form>
            
	  			</div>
	  			<div class="pull-right mt-4">
	  				<form class="form-inline filter-category-form" >
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
                                	<img src="/assets/img/flag.png" width="20">{{team.country_name}} 
                                </td>
                                <td>{{team.country_name}}</td>
                                <td>
                                	<select class="form-control ls-select2">
			                            <option value="">Select a location</option>
			                            <option value="">Location 1</option>
			                            <option value="">Location 2</option>
			                            <option value="">Location 3</option>
			                            <option value="">Location 4</option>
			                            <option value="">Etc...</option>
			                        </select>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary pull-right">{{$lang.teams_button_updategroups}}</button>
  				</div>
  			</div>
  		</div>
  	</div>
  </div>
</template>

<script type="text/babel">
   import Tournament from '../../../api/tournament.js'
	export default {
    data() {
    return {
        'teamSize': 5,
        'teams': [],
        'tournament_id': this.$store.state.Tournament.tournamentId
        'age_category': '',
        'selected': null,
        'value': '',
        'options': [],
        'grps': ''

        }
    },
    mounted() {
      return axios.get('/api/teams/'+this.tournament_id).then(response =>  {
        console.log(response)
        this.teams = response.data.data
                                // this.pitchId = response.data.pitchId
      }).catch(error => {
      });
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
    methods: {
      onSelectAgeCategory() {
        let tournamentTemplateId = this.age_category.tournament_template_id

        // Now here Fetch the appopriate Template of it
        let TemplateData = {tournamentTemplateId : tournamentTemplateId}
        Tournament.getTemplate(TemplateData).then (
          (response) => {
            //var JsonTemplateData = JSON.stringify(eval("(" + response.data.data + ")"));

            let jsonObj = JSON.parse(response.data.data)
            console.log(jsonObj)
            //let JsonTemplateData  = response.data.data
            // Now here we put data over there as per group
             let jsonCompetationFormatDataFirstRound = jsonObj['tournament_competation_format']['format_name'][0]['match_type']
            this.grps = jsonCompetationFormatDataFirstRound
            this.teamSize = jsonObj.tournament_teams 
          }, 
          (error)=> {
            alert('error in getting json data')
          }
        )

      },
      csvImport() {
        let files  = new FormData($("#frmCsvImport")[0]);
        // console.log(document.getElementById('fileUpload').files[0])
        return axios.post('/api/team/create',files).then(response =>  {
          console.log(response)
                                // this.pitchId = response.data.pitchId
          }).catch(error => {
              
          });
      }

    }
  }
</script>