<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<form name="website_media" enctype="multipart/form-data">
					<div class="form-group row">
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.photo_gallery}}</strong></h6>
	        	</div>
	        	<div class="col-sm-6">
	        		<photo-list @setPhotos="setPhotos"></photo-list>
	        	</div>
	        </div>
	        <hr class="my-4">
	        <div class="form-group row">
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.files_and_documents}}</strong></h6>
	        	</div>
	        	<div class="col-sm-6">
	        		<document-list @setDocuments="setDocuments"></document-list>
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
var moment = require('moment');
import Website from '../../../../js/api/website.js';
import PhotoList from '../../../components/PhotoList.vue';
import DocumentList from '../../../components/DocumentList.vue';

export default {
	components: {
		PhotoList,
		DocumentList,
	},
	data() {
		return {
			media: {
				websiteId: null,
				photos: [],
				documents: [],
			},
		}
	},
	mounted() {
		let currentNavigationData = {
			activeTab:'website_media',
			currentPage:'Media'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {
	},
	methods: {
		redirectToForward() {
      this.$root.$emit('getPhotos');
      this.$root.$emit('getDocuments');

      this.media.websiteId = this.getWebsiteId();
      $("body .js-loader").removeClass('d-none');
			Website.saveMediaPageData(this.media).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Media page has been updated successfully.', 'Success');
          var route = this.getWebsiteForwardRoute('media');
          if(route) {
            this.$router.push({name:route});
          }
        },
        (error)=>{
        }
      );
		},
		redirectToBackward() {
			var route = this.getWebsiteBackwardRoute('media');
      if(route) {
        this.$router.push({name:route});
      }
		},
		setPhotos(photos) {
			this.media.photos = photos;
		},
		setDocuments(documents) {
			this.media.documents = documents;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
	},
}
</script>
