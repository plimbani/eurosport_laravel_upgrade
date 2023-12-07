<template>
	<div class="card main-card">
    <div class="card-block">
        <div class="row">
          <div class="col-lg-12">
          	<div class="tabs tabs-primary">
          		<ul class="nav nav-tabs" role="tablist">
          			<li class="nav-item">
          				<a :class="[activePath == 'website_add' ? 'active' : '', 'nav-link']" data-toggle="tab" href="#website_add" role="tab" @click="GetSelectComponent('website_add')">
                    <div class="wrapper-tab">{{$lang.website_label}}</div>
                  </a>
          			</li>
          			<li class="nav-item" v-if="isPageEnabled('home')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_homepage' ? 'active' : '', 'nav-link']" href="#website_homepage" role="tab" @click="GetSelectComponent('website_homepage')">
                    <div class="wrapper-tab">{{$lang.website_homepage}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('teams')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_teams' ? 'active' : '', 'nav-link']" href="#website_teams" role="tab" @click="GetSelectComponent('website_teams')">
                    <div class="wrapper-tab">{{$lang.website_teams}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('venue')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_venue' ? 'active' : '', 'nav-link']" href="#website_venue" role="tab" @click="GetSelectComponent('website_venue')">
                    <div class="wrapper-tab">{{$lang.website_venue}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('tournament')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_tournament' ? 'active' : '', 'nav-link']" href="#website_tournament" role="tab" @click="GetSelectComponent('website_tournament')">
                    <div class="wrapper-tab">{{$lang.website_tournament}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('program')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_program' ? 'active' : '', 'nav-link']" href="#website_program" role="tab" @click="GetSelectComponent('website_program')">
                    <div class="wrapper-tab">{{$lang.website_program}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('stay')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_stay' ? 'active' : '', 'nav-link']" href="#website_stay" role="tab" @click="GetSelectComponent('website_stay')">
                    <div class="wrapper-tab">{{$lang.website_stay}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('visitors')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_visitors' ? 'active' : '', 'nav-link']" href="#website_visitors" role="tab" @click="GetSelectComponent('website_visitors')">
                    <div class="wrapper-tab">{{$lang.website_visitors}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('media')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_media' ? 'active' : '', 'nav-link']" href="#website_media" role="tab" @click="GetSelectComponent('website_media')">
                    <div class="wrapper-tab">{{$lang.website_media}}</div>
                  </a>
                </li>
                <li class="nav-item" v-if="isPageEnabled('contact')">
                  <a data-toggle="tab" :class="[isNewWebsite ? 'is-disabled' : '', activePath == 'website_contact' ? 'active' : '', 'nav-link']" href="#website_contact" role="tab" @click="GetSelectComponent('website_contact')">
                    <div class="wrapper-tab">{{$lang.website_contact}}</div>
                  </a>
                </li>
          		</ul>
              <div class="row" v-if="( $store.state.Website.preview_domain != null )">
                <div class="col-lg-12 mt-3">
                  <div class="alert alert-warning mb-0">
                    <span class="font-weight-bold">Preview url: </span>
                    <a target="_blank" v-bind:href=" $store.state.Website.preview_url">
                    {{ $store.state.Website.preview_url }}
                    </a>(Please note this URL will expire in {{urlExpirationTime}})<br>
                    <span class="font-weight-bold">Username: </span>preview
                    <span class="font-weight-bold">Password: </span>t0urPrev!eM
                  </div>
                </div>
              </div>
              <div class="row" v-if="$store.state.Website.preview_domain == null && $store.state.Website.id !== null">
                <div class="col-lg-12 mt-3">                
                  <div class="alert alert-warning mb-0">                  
                    Preview URL has expired. Please <a href="#" @click="generatePreviewUrl()">click here</a> to generate a new URL
                  </div>
                </div>
              </div>
          		<router-view></router-view>
          	</div>
          </div>
        </div>
    </div>
  </div>
</template>
<script type="text/babel">
import Website from '../../../api/website.js';
import moment from 'moment';

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
    urlExpirationTime() {
      let currentDateTime = moment.utc(new Date()).add(1, 'hours').format("YYYY-MM-DD HH:mm:ss");
      let previewDomainGeneratedAtObj = moment(this.$store.state.Website.preview_domain_generated_at);
      let totalMinutes = moment(currentDateTime).diff(previewDomainGeneratedAtObj, 'minutes');
      totalMinutes = window.previewUrlExpireTimeMinutes - totalMinutes;
      let diffInHours = totalMinutes / 60;
      let diffInMinutes = totalMinutes % 60;
      return (parseInt(diffInHours) > 0 ? parseInt(diffInHours) + (parseInt(diffInHours) > 1 ? ' hrs' : ' hr') + ' and ' : '') + diffInMinutes + (parseInt(diffInMinutes) > 1 ? ' mins' : ' min');
    }
  },
  methods: {
  	GetSelectComponent(componentName) {
  		this.$router.push({name: componentName});
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