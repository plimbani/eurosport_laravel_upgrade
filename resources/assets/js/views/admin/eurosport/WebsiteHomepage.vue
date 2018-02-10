<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_homepage_options}}</strong></h6>
				<form name="website_homepage" enctype="multipart/form-data">
	        <div class="form-group justify-content-between row">
	        	<div class="col-sm-6">
	        		<div class="row">
		          	<label class="col-sm-12 no-padding form-control-label">{{$lang.introduction_text}}</label>
		          	<div class="col-sm-12">
		          		<insert-text-editor :id="'introduction_text'" :value="homepage.introduction_text" @setEditorValue="setIntroductionText"></insert-text-editor>
		          	</div>
	          	</div>
	          </div>
	          <div class="col-sm-5">
	          	<div class="form-group row">
	          		<label class="col-sm-4 no-padding form-control-label">{{$lang.homepage_hero_image}}</label>
		          	<div class="col-sm-8">
		          		<img :src="getHeroImage" width="100px" height="100px"/>
		          		<button v-if="homepage.hero_image != ''" class="btn btn-default" @click="removeImage('hero_image')">{{$lang.tournament_tournament_remove_button}}</button>
		              <button v-else type="button" class="btn btn-default" @click="selectHeroImage()">{{$lang.tournament_tournament_choose_button}}</button>
		              <input type="file" id="hero_image" style="display:none;" @change="onImageChange($event, 'hero_image')">
		              <input type="hidden" v-model="homepage.hero_image" name="hero_image" />
		          	</div>
	          	</div>
	          	<div class="form-group row">
	          		<label class="col-sm-4 no-padding form-control-label">{{$lang.homepage_welcome_image}}</label>
		          	<div class="col-sm-8">
		          		<img :src="getWelcomeImage" width="100px" height="100px"/>
		          		<button v-if="homepage.welcome_image != ''" class="btn btn-default" @click="removeImage('welcome_image')">{{$lang.tournament_tournament_remove_button}}</button>
		              <button v-else type="button" class="btn btn-default" @click="selectWelcomeImage()">{{$lang.tournament_tournament_choose_button}}</button>
		              <input type="file" id="welcome_image" style="display:none;" @change="onImageChange($event, 'welcome_image')">
		              <input type="hidden" v-model="homepage.welcome_image" name="welcome_image" />
		          	</div>
	          	</div>
	          </div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.homepage_statistics}}</strong></h6>
	        	</div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-sm-6">
	        		<statistic-list @setStatistics="setStatistics"></statistic-list>
	        	</div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.homepage_organiser_logos}}</strong></h6>
	        	</div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-sm-6">
	        		<organiser-logo-list @setOrganiserLogos="setOrganiserLogos"></organiser-logo-list>
	        	</div>
	        </div>
	        <div class="">
						<!-- <draggable v-model="myArray" :options="{group:'people'}" @start="drag=true" @end="drag=false">
						   <div v-for="element in myArray" :key="element.id">{{element.name}}</div>
						</draggable> -->
						<!-- <draggable v-model="myArray" :options="{draggable:'.item', handle: '.my-handle'}">
						    <div v-for="element in myArray" :key="element.id" class="item">
						        <draggable v-model="element.array" :options="{draggable:'.item', handle: '.my-handle1'}">
										    <div v-for="inneritem in element.array" :key="inneritem.id" class="item">
										        {{inneritem.name}}
										        <span class="my-handle1">inner</span>
										    </div>
										    <button slot="footer" @click="addPeople">Add</button>
										</draggable>
						        <span class="my-handle">outer</span>
						    </div>
						    <button slot="footer" @click="addPeople">Add</button>
						</draggable> -->
					</div>
				</form>
			</div>
		</div>
		<div class="row">
	    <div class="col-md-12">
	      <div class="pull-left">
	          <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.website_back_button}}</button>
	      </div>
	      <div class="pull-right">
	          <button class="btn btn-primary" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
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
			homepage: {
				websiteId: null,
				introduction_text: '',
				hero_image: '',
				welcome_image: '',
				statistics: [],
				organiserLogos: [],
			},
			// myArray: [
			// {
			// 	id: 1,
			// 	array: [
			// 	{
			// 		id: 1,
			// 		name: 'atest'
			// 	},
			// 	{
			// 		id: 2,
			// 		name: 'btest'
			// 	}
			// 	]
			// },
			// {
			// 	id: 2,
			// 	array: [
			// 	{
			// 		id: 1,
			// 		name: 'ctest'
			// 	},
			// 	{
			// 		id: 2,
			// 		name: 'dtest'
			// 	}
			// 	]
			// }
			// ],
		}
	},
	mounted() {
		// Set current as active
		let currentNavigationData = {
			activeTab:'website_homepage',
			currentPage:'Homepage options'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
		this.getPageContent();
	},
	computed: {
		getHeroImage() {
  		return this.homepage.hero_image == '' ? 'http://placehold.it/250x250' : this.homepage.hero_image;
  	},
  	getWelcomeImage() {
  		return this.homepage.welcome_image == '' ? 'http://placehold.it/250x250' : this.homepage.welcome_image;
  	},
	},
	methods: {
		next() {
			this.$root.$emit('getEditorValue');
      this.$root.$emit('getStatistics');
      this.$root.$emit('getOrganiserLogos');

      this.homepage.websiteId = this.getWebsiteId();
      $("body .js-loader").removeClass('d-none');
			Website.saveHomePageData(this.homepage).then(
        (response)=> {
        	$("body .js-loader").addClass('d-none');
          toastr.success('Page has been updated successfully.', 'Homepage');
        },
        (error)=>{
        }
      );
		},
		setIntroductionText(content) {
			this.homepage.introduction_text = content;
		},
		setStatistics(statistics) {
			this.homepage.statistics = statistics;
		},
		setOrganiserLogos(organiserLogos) {
			this.homepage.organiserLogos = organiserLogos;
		},
		getWebsiteId() {
			return this.$store.state.Website.id;
		},
		redirectToForward() {
			this.$router.push({name:'website_teams'})
		},
		backward() {
			this.$router.push({name:'website_add'})
		},
		selectHeroImage() {
			$('#hero_image').trigger('click');
		},
		selectWelcomeImage() {
			$('#welcome_image').trigger('click');
		},
		onImageChange(e, key) {
			var vm = this;
			var files = e.target.files || e.dataTransfer.files;

			if (!files.length)
				return;

			var reader = new FileReader();
			reader.onload = (r) => {
				vm.homepage[key] = r.target.result;
			};

			reader.readAsDataURL(files[0]);
		},
		removeImage(key) {
			this.homepage[key] = '';
			e.preventDefault();
		},
		getPageContent() {
			var websiteId = this.getWebsiteId();

			Website.getHomePageData(websiteId).then(
        (response)=> {
          this.homepage.introduction_text = response.data.data.content;
          this.homepage.hero_image = typeof response.data.data.meta.hero_image != 'undefined' && response.data.data.meta.hero_image != null ? response.data.data.meta.hero_image : '';
          this.homepage.welcome_image = typeof response.data.data.meta.welcome_image != 'undefined' && response.data.data.meta.welcome_image != null ? response.data.data.meta.welcome_image : '';
        },
        (error)=>{
        }
      );
		},
	},
}
</script>