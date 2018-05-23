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
              <div class="row" v-show="(this.$store.state.Website.preview_domain != null)">
                <div class="col-lg-6">                  
                  <div class="input-group mt-3">
                    <span>Preview url: {{ this.$store.state.Website.preview_domain }}</span>
                  </div>
                </div>
              </div>
              <button class="btn btn-primary btn-icon generate-preview-btn tooltip" @click="generatePreviewUrl()" v-if="this.$store.state.Website.preview_domain == null" v-show="(this.$store.state.Website.preview_domain == null)"><i class="fa fa-globe"></i><span class="tooltiptext text-center">Generate Preview URL</span></button>
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
      'previewUrl': '',
      'previewDomain': ''
    }
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
      let vm = this;
      if(this.getWebsiteId !== null) {  
        $("body .js-loader").removeClass('d-none');              
        Website.generatePreviewUrl(this.getWebsiteId).then(
          (response)=> {
            $("body .js-loader").addClass('d-none');
            vm.$store.dispatch('SetWebsitePreviewDetail', response.data.data);
          },
          (error)=> {
          }
        );
      }
    },
  },
}
</script>