<template>
	<div class="modal" id="copyAgeCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true"  data-animation="false">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      	<div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">{{$lang.copy_age_category_modal_header}}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		            <span aria-hidden="true">×</span>
		        </button>
	      	</div>
	      	<div class="modal-body">
			    <form id="copyAgeCategoryForm">
			        <div class="form-group row">
			          <div class="col-sm-12">
			            <p class="mb-0">{{ $lang.copy_age_category_modal_note }}</p>
			          </div>
			        </div>
			        <div class="form-group row" v-show="ageCategoryAlreadyExist">
	                	<div class="col-sm-12 help is-danger">
	                  		{{ ageCategoryAlreadyExistMessage }}
	                	</div>
	              	</div>
			        <div class="form-group row align-items-center">
			          <div class="col-sm-4 form-control-label">{{$lang.competation_label_age_category_name}}</div>
			          <div class="col-sm-8">
			            <div class="row">
			              <div class="col-sm-12">
			               <multiselect  name="category_age" id="category_age"
			               v-model="competition_format.category_age"
			                :options="categoryAgeArr" :multiple="false"
			                 :hide-selected="false" :ShowLabels="false" :value="value" track-by="id"
			                 :clear-on-select="false" :Searchable="true" @input="onChange" @close="onTouch"
			                 @select="onSelect">
			                  <option v-for="categoryAge in categoryAgeArr"
			                  :value="categoryAge">{{categoryAge}}
			                  </option>
			                </multiselect>
			               <span class="help is-danger" v-show="isInvalid">{{$lang.copy_age_category_modal_validation_message}}</span>
			              </div>
			            </div>
			          </div>
			        </div>

			        <div class="form-group row align-items-center" :class="{'has-error': errors.has('competition_format.ageCategory_name') }">
			          <div class="col-sm-4 form-control-label">
			            {{$lang.competation_label_name_category}}
			            <span class="pr-2 pl-2 text-primary" data-toggle="popover" data-animation="false" data-placement="right" data-content="Enter an additional name for the category"><i class="fas fa-info-circle"></i></span>
			          </div>
			            <div class="col-sm-8">
			              <div class="row">
			                <div class="col-sm-12">
			                  <input type="text" class="form-control"
			                  placeholder="e.g. U11, U16-A"  v-validate="{ rules: { required : true, regex: /^[a-zA-Z0-9\/ ]*$/ } }" :class="{'is-danger': errors.has('ageCategory_name') }" v-model="competition_format.ageCategory_name" name="ageCategory_name">
			                  <i v-show="errors.has('ageCategory_name')" class="fas fa-warning"></i>
			                  <span class="help is-danger" v-show="errors.has('ageCategory_name')">{{$lang.copy_age_category_modal_validation_message}}</span>
			                </div>
			              </div>
			            </div>
			        </div>

		            <div class="form-group row align-items-center">
			            <label class="col-sm-4 form-control-label">Pitch size*</label>
			            <div class="col-sm-8">
			              <select name="pitch_size" id="pitch_size" class="form-control ls-select2" v-model="competition_format.pitch_size" v-validate="'required'" :class="{'is-danger': errors.has('pitch_size') }">
			                 <option value="">{{$lang.pitch_modal_pitch_size}}</option>
			                    <option value="5-a-side">{{$lang.pitch_modal_details_size_side}}</option>
			                    <option value="7-a-side">{{$lang.pitch_modal_details_size_side_one}}</option>
			                    <option value="8-a-side">{{$lang.pitch_modal_details_size_side_two}}</option>
			                    <option value="9-a-side">{{$lang.pitch_modal_details_size_side_three}}</option>
			                    <option value="11-a-side">{{$lang.pitch_modal_details_size_side_four}}</option>
			              </select>
			              <span class="help is-danger" v-show="errors.has('pitch_size')">{{$lang.copy_age_category_modal_validation_message}}</span>
			            </div>
			        </div>
			    </form>
	       	</div>
	      	<div class="modal-footer">
	          <button type="button" class="btn btn-danger" @click="closeModal()">{{$lang.competation_modal_button_cancle}}</button>
	          <button type="button" class="btn button btn-primary" @click="copyAgeCategory" :disabled="isSaveInProcess" v-bind:class="{ 'is-loading' : isSaveInProcess }">{{$lang.competation_modal_button_save}}</button>
	      	</div>
	    </div>
	  </div>
	</div>
</template>

<script type="text/babel">
	import Tournament from '../api/tournament.js'
	export default {
		props: ['copiedAgeCategoryId'],
	  	data() {
	    	return  {
	    		value: [],
	    		isInvalid: false,
	    		isSaveInProcess: false,
	    		ageCategoryAlreadyExist: false,
	    		ageCategoryAlreadyExistMessage: '',
	    		competition_format: {
		    		ageCategory_name: '',
		    		category_age: '',
		    		pitch_size: '',
	    		},
				categoryAgeArr: ['U08/5','U09','U09/5','U09/7','U10','U10/5','U10/7','U10/9','U10/5A','U10/7A','U10/5B','U10/7B','U11','U11/11','U11/7','U11/7A','U11/7B','U12','U12/7','U12/8','U12/9','U12-A','U12/7A','U12/8A','U12-B','U12/7B','U12/8B','U13','U13/7','U13/8','U13/9','U13-A','U13/7A','U13/8A','U13/9A','U13-B','U13/8B','U13/9B','U14','U14/7','U14-A','U14-B','U15', 'U15/7','U15/8','U15-A','U15-B','U16','U16-A','U16-B','U17','U17-A','U17-B','U18','U19','U19-A','U19-B','U10-U9','G08/5','G09/5','G09/7','G10/5','G10/7','G10/7A','G10/7B','G11','G11/7','G12','G12/7','G12/8','G12/9','G12/7A','G12/7B','G13','G13/7','G13/8','G13/9','G13/7A','G13/7B','G14','G14/7','G14/8','G14-A','G14-B','G15','G15/7','G15/8','G15-A','G15-B','G16','G17','G17/7','G17-A','G17-B','G18','G18/7','G18-A','G18-B','G19','G19-A','G19-B','M-O','M-O/5','M-O/7','M32','M35','M35/7','W-O','W-O/7'],				
	    	}
		},
		mounted() {
		    $("[data-toggle=popover]").popover({
		        html : false,
		        trigger: 'hover',
		        content: function() {
		            var content = $(this).attr("data-popover-content");
		            return $(content).children(".popover-body").html();
		        },
		        title: function() {
		            var title = $(this).attr("data-popover-content");
		            return $(title).children(".popover-heading").html();
		        }
		    });
		},
		methods: {
			copyAgeCategory() {
			    this.isInvalid = false
			    if(this.value.length === 0) {
			       this.isInvalid = true
			    }
				this.$validator.validateAll().then((response) => {
					if(this.isInvalid == true) {
              			return false;
        			}

        			if(response) {
	        			this.isSaveInProcess = true;
						let ageCategoryData = { 'competition_format': this.competition_format, 'copiedAgeCategoryId': this.copiedAgeCategoryId, 'tournament_id': this.$store.state.Tournament.tournamentId}
						Tournament.copyAgeCategory(ageCategoryData).then(
							(response) => {
								if(response.data.status_code == 200) {
									toastr.success('Age category has been copied successfully.', 'Add Age Category', {timeOut: 5000});
									$('#copyAgeCategoryModal').modal('hide');
									this.$root.$emit('displayCompetationList');
									this.resetForm();
									this.isSaveInProcess = false;
								} else if(response.data.status_code == 403) {
									this.isSaveInProcess = false;
									this.ageCategoryAlreadyExistMessage = response.data.message;
									this.ageCategoryAlreadyExist = true;
			                    }
							},
		                    (error) => {

		                    });
					}
				}).catch((errors) => {

                });					
			},
		    onChange (value) {		      
		      this.value = value
		    },
		    onSelect (option) {
		      if (option === 'Disable me!') this.isDisabled = true
		    },
		    onTouch () {
		      this.isTouched = true
		    },
		    resetForm() {
		    	this.competition_format.ageCategory_name = '';
		    	this.competition_format.category_age = '';
		    	this.competition_format.pitch_size = '';
		    	this.clearErrorMsgs();
		    },
		    closeModal() {
		    	$('#copyAgeCategoryModal').modal('hide');
		    	this.isInvalid = false;
		    	this.ageCategoryAlreadyExist = false;
	    		this.ageCategoryAlreadyExistMessage = '';
		    	this.resetForm();
		    }
		}
	}
</script>