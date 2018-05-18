<template>
	<div class="card">
    <div class="card-block">
        <div class="row">
          <div class="col-lg-12">
          	<div class="tabs tabs-primary">
          		<ul class="nav nav-tabs" role="tablist">
          			<li class="nav-item">
          				<a :class="[activePath == 'website_add' ? 'active' : '', 'nav-link']" data-toggle="tab" href="#website_add" role="tab" @click="GetSelectComponent('website_add')">{{$lang.website_label}}</a>
          			</li>
          			<li class="nav-item" v-if="isPageEnabled('home')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_homepage' ? 'active' : '', 'nav-link']" href="#website_homepage" role="tab" @click="GetSelectComponent('website_homepage')">{{$lang.website_homepage}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('teams')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_teams' ? 'active' : '', 'nav-link']" href="#website_teams" role="tab" @click="GetSelectComponent('website_teams')">{{$lang.website_teams}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('venue')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_venue' ? 'active' : '', 'nav-link']" href="#website_venue" role="tab" @click="GetSelectComponent('website_venue')">{{$lang.website_venue}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('tournament')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_tournament' ? 'active' : '', 'nav-link']" href="#website_tournament" role="tab" @click="GetSelectComponent('website_tournament')">{{$lang.website_tournament}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('program')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_program' ? 'active' : '', 'nav-link']" href="#website_program" role="tab" @click="GetSelectComponent('website_program')">{{$lang.website_program}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('stay')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_stay' ? 'active' : '', 'nav-link']" href="#website_stay" role="tab" @click="GetSelectComponent('website_stay')">{{$lang.website_stay}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('visitors')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_visitors' ? 'active' : '', 'nav-link']" href="#website_visitors" role="tab" @click="GetSelectComponent('website_visitors')">{{$lang.website_visitors}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('media')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_media' ? 'active' : '', 'nav-link']" href="#website_media" role="tab" @click="GetSelectComponent('website_media')">{{$lang.website_media}}</a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('contact')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_contact' ? 'active' : '', 'nav-link']" href="#website_contact" role="tab" @click="GetSelectComponent('website_contact')">{{$lang.website_contact}}</a>
                </li>
          		</ul>
              <div class="row">
                <div class="col-lg-6">                  
                  <div class="input-group mt-3" v-if="">
                    <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon2" disabled="true" v-model="this.previewUrl">
                    <div class="input-group-append">
                      <v-popover class="d-inline-flex copy-link">
                        <!-- <a class="text-primary" href="javascript:void(0)" @click="initializeDocumentLink(document, index)"> -->
                        <button class="btn btn-primary" type="button" @click="initializePreviewLink()">Copy to clipboard</button>
                        <!-- </a> -->
                        <template slot="popover">
                          <input class="tooltip-content" :class="`js-popover-content`" type="text" v-model="this.previewUrl" />
                          <span><i class="fa fa-check"></i> Link copied to clipboard</span>
                        </template>
                      </v-popover>
                      <button type="button" class="js-copy-clipboard-preview-link" v-clipboard:copy="this.previewUrl" v-show="false"></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <span v-model="this.previewUrl">{{ this.previewUrl }}</span> -->
              <button class="btn btn-primary btn-icon generate-preview-btn tooltip" @click="generatePreviewUrl()" v-if="!(this.isNewWebsite) && (previewUrl == '')"><i class="fa fa-globe"></i><span class="tooltiptext text-center">Generate Preview URL</span></button>
          		<router-view></router-view>
          	</div>
          </div>
        </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Website from '../../../api/website.js';
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip';

export default {
  data() {
    return {
    	'header': 'header',
      'previewUrl': ''
    }
  },
  components: {
    VPopover,
    VClosePopover,
  },
  mounted() {
   
  },
  computed: {
    isNewWebsite() {
      return this.$store.state.Website.id == null;
    },
   	activePath() {
      return this.$store.state.activePath
    },
    getWebsiteId() {
      return this.$store.state.Website.id;
    },
  },
  methods: {
  	GetSelectComponent(componentName) {
  		this.$router.push({name: componentName});
    	if(componentName != 'website_add' || componentName != 'website_homepage') {
        setTimeout( function(){
      		if ($(document).height() > $(window).height()) {
            $('.site-footer').removeClass('sticky');
          } else {
            $('.site-footer').addClass('sticky');
          }
    		},2000 )
    	}
  	},
    generatePreviewUrl() {
      if(this.getWebsiteId !== null) {  
        $("body .js-loader").removeClass('d-none');              
        Website.generatePreviewUrl(this.getWebsiteId).then(
          (response)=> {
            $("body .js-loader").addClass('d-none');
            this.previewUrl = response.data.previewUrl;
          },
          (error)=> {
          }
        );
      }
    },
    initializePreviewLink() {
      // console.log(this.previewUrl);
      // this.documentLink = this.getDocumentPath + this.getWebsite + '/' + document.file_name;
      this.$nextTick().then(() => {
        $('.js-popover-content').focus();
        $('.js-popover-content').select();
        $('.js-copy-clipboard-preview-link').trigger('click');
      });
    }
  },
}
</script>