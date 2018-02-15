<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.age_categories}}</strong></h6>
				<div class="form-group justify-content-between row">
	        	<div class="col-sm-6">
	        		<age-category-list @setAgeCategories="setAgeCategories" :countries="team.countries"></age-category-list>
	        	</div>
				</div>
				<hr class="my-4">
				<div class="form-group row">
        	<div class="col-sm-12">
        		<h6><strong>{{$lang.upload_teams}}</strong></h6>
        	</div>
        	<div class="col-sm-8">
            <form method="post" name="frm_team_import" id="frm_team_import" enctype="multipart/form-data">
          		<div class="mb-2 row">
          			<div class="col-sm-3">{{$lang.upload_teams}}</div>
          			<div class="col-sm-4">
                  <button type="button" class="btn btn-default w-100 btn-color-black--light" @click="browseFiles()">Choose file (excel files only)</button>
                </div>
                <div class="col-sm-4">
                  <span>{{ this.currentImportFileName }}</span>
                  <button type="button" @click="importTeamFile()"  class="btn btn-primary ml-4">Upload</button>
                  <input type="file" name="team_upload" @change="setFileName(this, $event)"  id="team_upload" style="display:none;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,application/excel,application/vnd.ms-excel,application/vnd.msexcel,text/anytext,application/txt" />
                </div>
          		</div>
          		<div class="mb-2"><strong>{{$lang.please_note}}</strong> {{$lang.upload_team_note}}</div>
          		<div>{{$lang.team_sheet_format}}</div>
          		<div>- {{$lang.age_category}}</div>
          		<div>- {{$lang.excel_team_name}}</div>
          		<div>- {{$lang.country}}</div>
          		<div class="mt-2">{{$lang.country_recognisation}}</div>
            </form>
        	</div>
        </div>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12">
		      <div class="pull-left">
		        <button class="btn btn-primary" @click="redirectToBackward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
		      </div>
		      <div class="pull-right">
		        <button class="btn btn-primary" @click="redirectToForward()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
		      </div>
		    </div>
	  	</div>
  	</div>
</template>
<script>
var moment = require('moment');
import Website from '../../../api/website.js';
import Tournament from '../../../api/tournament.js';
import AgeCategoryList from '../../../components/AgeCategoryList.vue';

export default {
	components: {
		AgeCategoryList,
	},
	data() {
		return {
			team: {
				websiteId: null,
				ageCategories: [],
				countries: [],
			},
      canUploadTeamFile: false,
      currentImportFileName: '',
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_teams',
			currentPage:'Teams'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
		this.getPageContent();
	},
	computed: {
		getWebsite() {
			return this.$store.state.Website.id;
		},
	},
	methods: {
		redirectToForward() {
			this.team.websiteId = this.getWebsite;
			this.$root.$emit('getAgeCategories');
      $("body .js-loader").removeClass('d-none');
      Website.saveTeamPageData(this.team).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Team has been updated successfully.', 'Success');
          this.$router.push({name:'website_venue'});
        },
        (error)=>{
        }
      );
		},
		redirectToBackward() {
			this.$router.push({name:'website_homepage'});
		},
		setAgeCategories(ageCategories) {
			this.team.ageCategories = ageCategories;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		getPageContent() {
			var websiteId = this.getWebsiteId();

			Website.getTeamPageData(websiteId).then(
        (response)=> {
        	this.team.countries = response.data.data.countries;
        },
        (error)=>{
        }
      );
		},
		importTeamFile() {
      if($('#team_upload').val()!=''){
        if(this.canUploadTeamFile == false) {
          toastr['error']('Please upload an excel file.', 'Error');
          return;
        }
        let formData  = new FormData($("#frm_team_import")[0]);
        formData.append('websiteId', this.getWebsiteId());
        Website.importAgeCategoryAndTeamData(formData).then(
          (response)=> {
          	if(response.data.status_code == 200) {
          		this.$root.$emit('importAgeCategories', response.data.data.ageCategories);
          		toastr['success']('Teams have been imported successfully.', 'Success');
          	} else {
          		toastr['error'](response.data.message, 'Error');
          	}
          	this.currentImportFileName = '';
          	$('#team_upload').val('');
          },
          (error)=>{
          }
        );
      } else {
         toastr['error']('Please upload an excel file.', 'Error');
      }
		},
		browseFiles() {
			$("#team_upload").trigger('click');
		},
		setFileName(file, event) {
      this.canUploadTeamFile = true;
      if(event.target.files.length > 0) {
      	var extensionsplit = event.target.files[0].name.split(".");
	      var extension = extensionsplit[extensionsplit.length - 1];
	      if(extension != 'xls' && extension != 'xlsx') {
	        this.canUploadTeamFile = false;
	      }
	      var filename = $('#team_upload').val();
	      var lastIndex = filename.lastIndexOf('\\');

	      if (lastIndex >= 0) {
	        filename = filename.substring(lastIndex + 1);
	      }
	      this.currentImportFileName = filename;
      }
    },
	},
}
</script>
