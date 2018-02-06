<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<h6><strong>{{$lang.website_homepage_options}}</strong></h6>
				<form name="website_homepage" enctype="multipart/form-data">
	        <div class="form-group row">
	        	<div class="col-sm-8">
	        		<div class="row">
		          	<label class="col-sm-12 no-padding form-control-label">{{$lang.introduction_text}}</label>
		          	<div class="col-sm-12">
		          		<insert-text-editor :id="'introduction_text'" :value="homepage.introduction_text"></insert-text-editor>
		          	</div>
	          	</div>
	          </div>
	        </div>
	        <div class="form-group row">
	        	<div class="col-sm-12">
	        		<h6><strong>{{$lang.website_statistics}}</strong></h6>
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
		          <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true"></i>{{$lang.tournament_button_home}}</button>
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
import draggable from 'vuedraggable';
export default {
	components: {
		InsertTextEditor,
		draggable,
	},
	data() {
		return {
			homepage: {
				introduction_text: '',
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
		let currentNavigationData = {
			activeTab:'website_homepage', 
			currentPage:'Homepage options'
		};
		this.$store.dispatch('setActiveTab', currentNavigationData);
	},
	computed: {

	},
	methods: {
		next() {
			this.$validator.validateAll().then(
			(response) => {
				this.website.tournament_date = document.getElementById('tournament_date').value
				this.$store.dispatch('SaveWebsiteDetails', this.website)
					toastr['success']('Website details added successfully', 'Success');
				// setTimeout(this.redirectCompetation, 5000);
			},
			(error) => {

			})
		},
		addPeople() {

		}
	},
}
</script>