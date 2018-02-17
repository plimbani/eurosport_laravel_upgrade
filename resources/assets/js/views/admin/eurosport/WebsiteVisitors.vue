<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.visitors}}</strong></h6>
				<form name="website_homepage" enctype="multipart/form-data">
	        <div class="form-group justify-content-between row">
	        	<div class="col-sm-12">
	        		<div class="row">
		          	<label class="col-sm-12 no-padding form-control-label">{{$lang.visitor_arrival_and_checkin_information}}</label>
		          	<div class="col-sm-12">
		          		<insert-text-editor :id="'arrival_check_in_information'" :value="visitor.arrival_check_in_information" @setEditorValue="setArrivalCheckInInformationText"></insert-text-editor>
		          	</div>
	          	</div>
	          </div>
	        </div>
	        <div class="form-group justify-content-between row">
	        	<div class="col-sm-12">
	        		<div class="row">
		          	<label class="col-sm-12 no-padding form-control-label">{{$lang.visitor_public_transport}}</label>
		          	<div class="col-sm-12">
		          		<insert-text-editor :id="'public_transport'" :value="visitor.public_transport" @setEditorValue="setPublicTransportText"></insert-text-editor>
		          	</div>
	          	</div>
	          </div>
	        </div>
	        <div class="form-group justify-content-between row">
	        	<div class="col-sm-12">
	        		<div class="row">
		          	<label class="col-sm-12 no-padding form-control-label">{{$lang.visitor_tips}}</label>
		          	<div class="col-sm-12">
		          		<insert-text-editor :id="'tips'" :value="visitor.tips" @setEditorValue="setTipsText"></insert-text-editor>
		          	</div>
	          	</div>
	          </div>
	        </div>
	        <hr class="my-4">
	        <div class="form-group row">
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.tourist_information}}</strong></h6>
	        	</div>
	        	<div class="col-sm-12">
	        		<div class="form-group justify-content-between row">
			        	<div class="col-sm-12">
			        		<div class="row">
				          	<label class="col-sm-12 no-padding form-control-label">{{$lang.page_content}}</label>
				          	<div class="col-sm-12">
				          		<insert-text-editor :id="'tourist_information'" :value="visitor.tourist_information" @setEditorValue="setTouristInformationText"></insert-text-editor>
				          	</div>
			          	</div>
			          </div>
			        </div>
	        	</div>
	        </div>
				</form>
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
import InsertTextEditor from '../../../components/InsertTextEditor/InsertTextEditor.vue';
import StatisticList from '../../../components/StatisticList.vue';
import OrganiserLogoList from '../../../components/OrganiserLogoList.vue';
import Website from '../../../../js/api/website.js';

export default {
	components: {
		InsertTextEditor,
		StatisticList,
		OrganiserLogoList,
	},
	data() {
		return {
			visitor: {
				websiteId: null,
				arrival_check_in_information: '',
				public_transport: '',
				tips: '',
				tourist_information: '',
			},
		}
	},
	mounted() {
		// Set current as active
		let currentNavigationData = {
			activeTab:'website_visitors',
			currentPage:'Visitors'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
		this.getPageContent();
	},
	computed: {
		
	},
	methods: {
		redirectToForward() {
			this.$root.$emit('getEditorValue');

      this.visitor.websiteId = this.getWebsiteId();
      $("body .js-loader").removeClass('d-none');
			Website.saveVisitorPageData(this.visitor).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Visitor page has been updated successfully.', 'Success');
          this.$router.push({name:'website_media'});
        },
        (error)=>{
        }
      );
		},
		setArrivalCheckInInformationText(content) {
			this.visitor.arrival_check_in_information = content;
		},
		setPublicTransportText(content) {
			this.visitor.public_transport = content;
		},
		setTipsText(content) {
			this.visitor.tips = content;
		},
		setTouristInformationText(content) {
			this.visitor.tourist_information = content;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		redirectToBackward() {
			this.$router.push({name:'website_stay'})
		},
		getPageContent() {
			var websiteId = this.getWebsiteId();
			Website.getVisitorPageData(websiteId).then(
        (response)=> {
        	var responseData = response.data.data;
        	if(responseData.visitor.meta !== null) {
        		this.visitor.arrival_check_in_information = typeof responseData.visitor.meta.arrival_check_in_information != 'undefined' && responseData.visitor.meta.arrival_check_in_information != null ? responseData.visitor.meta.arrival_check_in_information : '';
          	this.visitor.public_transport = typeof responseData.visitor.meta.public_transport != 'undefined' && responseData.visitor.meta.public_transport != null ? responseData.visitor.meta.public_transport : '';
          	this.visitor.tips = typeof responseData.visitor.meta.tips != 'undefined' && responseData.visitor.meta.tips != null ? responseData.visitor.meta.tips : '';
        	}
        	this.visitor.tourist_information = responseData.tourist.content !== null ? responseData.tourist.content : '';
        },
        (error)=>{
        }
      );
		},
	},
}
</script>