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
			            <strong>{{ $lang.copy_age_category_modal_note }}</strong>
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
						<input type="hidden" v-model="competition_format.category_age_color">
          				<input type="hidden" v-model="competition_format.category_age_font_color">		          
			        </div>

			        <div class="form-group row align-items-center" :class="{'has-error': errors.has('competition_format.age_category_name') }">
			          <div class="col-sm-4 form-control-label">
			            {{$lang.competation_label_name_category}}
			            <span class="pr-2 pl-2 text-primary" data-toggle="popover" data-animation="false" data-placement="right" data-content="Enter an additional name for the category"><i class="fa fa-info-circle"></i></span>
			          </div>
			            <div class="col-sm-8">
			              <div class="row">
			                <div class="col-sm-12">
			                  <input type="text" class="form-control"
			                  placeholder="e.g. U11, U16-A"  v-validate="{ rules: { required : true, regex: /^[a-zA-Z0-9\/ ]*$/ } }" :class="{'is-danger': errors.has('ageCategory_name') }" v-model="competition_format.age_category_name" name="ageCategory_name">
			                  <i v-show="errors.has('ageCategory_name')" class="fa fa-warning"></i>
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
	    		competition_format: {
		    		age_category_name: '',
		    		category_age: '',
		    		pitch_size: '',
		    		category_age_color:null,
	         		category_age_font_color:null,
	    		},
				categoryAgeArr: ['U08/5','U09','U09/5','U09/7','U10','U10/5','U10/7','U10/9','U10/5A','U10/7A','U10/5B','U10/7B','U11','U11/11','U11/7','U11/7A','U11/7B','U12','U12/7','U12/8','U12/9','U12-A','U12/7A','U12/8A','U12-B','U12/7B','U12/8B','U13','U13/7','U13/8','U13/9','U13-A','U13/7A','U13/8A','U13/9A','U13-B','U13/8B','U13/9B','U14','U14/7','U14-A','U14-B','U15','U15/7','U15/8','U15-A','U15-B','U16','U16-A','U16-B','U17','U17-A','U17-B','U18','U19','U19-A','U19-B','U10-U9','G08/5','G09/5','G09/7','G10/5','G10/7','G10/7A','G10/7B','G11','G11/7','G12','G12/7','G12/8','G12/9','G12/7A','G12/7B','G13','G13/7','G13/8','G13/9','G13/7A','G13/7B','G14','G14/7','G14/8','G14-A','G14-B','G15','G15/7','G15/8','G15-A','G15-B','G16','G17','G17/7','G17-A','G17-B','G18','G18/7','G18-A','G18-B','G19','G19-A','G19-B','M-O','M-O/5','M-O/7','M32','M35','M35/7','W-O','W-O/7'],
		       categoryAgeColorArr: {
		        	'U08/5' : '#ffc0cb','U09' : '#008080','U09/5' : '#ffe4e1','U09/7' : '#ff0000','U10' : '#ffd700','U10/5' : '#d3ffce','U10/7' : '#00ffff','U10/9' : '#40e0d0','U10/5A' : '#ff7373','U10/7A' : '#e6e6fa','U10/5B' : '#0000ff','U10/7B' : '#ffa500','U11' : '#b0e0e6','U11/11' : '#7fffd4','U11/7' : '#333333','U11/7A' : '#faebd7','U11/7B' : '#003366','U12' : '#fa8072','U12/7' : '#800080','U12/8' : '#20b2aa','U12/9' : '#ffb6c1','U12-A' : '#c6e2ff','U12/7A' : '#00ff00','U12/8A' : '#f6546a','U12-B' : '#f08080','U12/7B' : '#468499','U12/8B' : '#ffff00','U13' : '#ffc3a0','U13/7' : '#088da5','U13/8' : '#fff68f','U13/9' : '#ff6666','U13-A' : '#00ced1','U13/7A' : '#66cdaa','U13/8A' : '#800000','U13/9A' : '#660066','U13-B' : '#ff00ff','U13/8B' : '#D8BFD8','U13/9B' : '#c39797','U14' : '#c0d6e4','U14/7' : '#0e2f44','U14-A' : '#cbbeb5','U14-B' : '#ff7f50','U15' : '#ffdab9','U15/7' : '#990000','U15/8' : '#808000','U15-A' : '#daa520','U15-B' : '#8b0000','U16' : '#b4eeb4','U16-A' : '#afeeee','U16-B' : '#ffff66','U17' : '#f5f5dc','U17-A' : '#81d8d0','U17-B' : '#b6fcd5','U18' : '#66cccc','U19' : '#00ff7f','U19-A' : '#ccff00','U19-B' : '#cc0000','U10-U9' : '#a0db8e','G08/5' : '#8a2be2','G09/5' : '#ff4040','G09/7' : '#3399ff','G10/5' : '#3b5998','G10/7' : '#0099cc','G10/7A' : '#794044','G10/7B' : '#ff4444','G11' : '#000080','G11/7' : '#6897bb','G12' : '#6dc066','G12/7' : '#31698a','G12/8' : '#191970','G12/9' : '#191919','G12/7A' : '#4169e1','G12/7B' : '#B0171F','G13' : '#FFBBFF','G13/7' : '#7D26CD','G13/8' : '#27408B','G13/9' : '#00C78C','G13/7A' : '#3D9140','G13/7B' : '#00EE00','G14' : '#EEEE00','G14/7' : '#FF9912','G14/8' : '#CD6600','G14-A' : '#F4A460','G14-B' : '#8B4C39','G15' : '#CD0000','G15/7' : '#8E8E38','G15/8' : '#FFEC8B','G15-A' : '#EE9A49','G15-B' : '#CD8162','G16' : '#BBFFFF','G17' : '#008B8B','G17/7' : '#1874CD','G17-A' : '#9F79EE','G17-B' : '#EE3A8C','G18' : '#92C685','G18/7' : '#C2B182','G18-A' : '#47CE6E','G18-B' : '#00A998','G19' : '#C2A9FD','G19-A' : '#D5FD30','G19-B' : '#CACA8E','M-O' : '#8D8812','M-O/5' : '#0075EA','M-O/7' : '#DCB8D4','M32' : '#F0FF18','M35' : '#60262E','M35/7' : '#B2F3B7','W-O' : '#532C5E','W-O/7' : '#BBF47F'
		      	},
		        categoryAgeFontColorArr: {
		        	'U08/5' : '#000000','U09' : '#FFFFFF','U09/5' : '#000000','U09/7' : '#000000','U10' : '#000000','U10/5' : '#000000','U10/7' : '#000000','U10/9' : '#000000','U10/5A' : '#000000','U10/7A' : '#000000','U10/5B' : '#FFFFFF','U10/7B' : '#000000','U11' : '#000000','U11/11' : '#000000','U11/7' : '#FFFFFF','U11/7A' : '#000000','U11/7B' : '#FFFFFF','U12' : '#000000','U12/7' : '#FFFFFF','U12/8' : '#000000','U12/9' : '#000000','U12-A' : '#000000','U12/7A' : '#000000','U12/8A' : '#000000','U12-B' : '#000000','U12/7B' : '#000000','U12/8B' : '#000000','U13' : '#000000','U13/7' : '#000000','U13/8' : '#000000','U13/9' : '#000000','U13-A' : '#000000','U13/7A' : '#000000','U13/8A' : '#FFFFFF','U13/9A' : '#000000','U13-B' : '#000000','U13/8B' : '#000000','U13/9B' : '#000000','U14' : '#000000','U14/7' : '#FFFFFF','U14-A' : '#000000','U14-B' : '#000000','U15' : '#000000','U15/7' : '#FFFFFF','U15/8' : '#FFFFFF','U15-A' : '#000000','U15-B' : '#FFFFFF','U16' : '#000000','U16-A' : '#000000','U16-B' : '#000000','U17' : '#000000','U17-A' : '#000000','U17-B' : '#000000','U18' : '#000000','U19' : '#000000','U19-A' : '#000000','U19-B' : '#FFFFFF','U10-U9' : '#000000','G08/5' : '#000000','G09/5' : '#000000','G09/7' : '#000000','G10/5' : '#FFFFFF','G10/7' : '#000000','G10/7A' : '#FFFFFF','G10/7B' : '#000000','G11' : '#FFFFFF','G11/7' : '#000000','G12' : '#000000','G12/7' : '#FFFFFF','G12/8' : '#FFFFFF','G12/9' : '#FFFFFF','G12/7A' : '#FFFFFF','G12/7B' : '#FFFFFF','G13' : '#000000','G13/7' : '#FFFFFF','G13/8' : '#FFFFFF','G13/9' : '#000000','G13/7A' : '#000000','G13/7B' : '#000000','G14' : '#000000','G14/7' : '#000000','G14/8' : '#000000','G14-A' : '#000000','G14-B' : '#000000','G15' : '#000000','G15/7' : '#000000','G15/8' : '#000000','G15-A' : '#000000','G15-B' : '#000000','G16' : '#000000','G17' : '#000000','G17/7' : '#000000','G17-A' : '#000000','G17-B' : '#000000','G18' : '#000000','G18/7' : '#000000','G18-A' : '#000000','G18-B' : '#000000','G19' : '#000000','G19-A' : '#000000','G19-B' : '#000000','M-O' : '#000000','M-O/5' : '#000000','M-O/7' : '#000000','M32' : '#000000','M35' : '#FFFFFF','M35/7' : '#000000','W-O' : '#FFFFFF','W-O/7' : '#000000'
		      	},				
	    	}
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
        			this.isSaveInProcess = true;
					let ageCategoryData = { 'competition_format': this.competition_format, 'copiedAgeCategoryId': this.copiedAgeCategoryId}
					Tournament.copyAgeCategory(ageCategoryData).then(
						(response) => {
							if(response.data.status_code == 200) {
								toastr.success('Age category has been copied successfully.', 'Add Age Category', {timeOut: 5000});
								$('#copyAgeCategoryModal').modal('hide');
								this.$root.$emit('displayCompetationList');
								this.resetForm();
								this.isSaveInProcess = false;
							}
						},
	                    (error) => {

	                    });
				}).catch((errors) => {

                });					
			},
		    onChange (value) {
		      this.competition_format.category_age_color = null;
		      this.competition_format.category_age_font_color = null;
		      if(typeof this.competition_format.category_age != 'object') {
		        this.competition_format.category_age_color = this.categoryAgeColorArr[this.competition_format.category_age];
		        this.competition_format.category_age_font_color = this.categoryAgeFontColorArr[this.competition_format.category_age];
		      }
		      
		      this.value = value
		    },
		    onSelect (option) {
		      if (option === 'Disable me!') this.isDisabled = true
		    },
		    onTouch () {
		      this.isTouched = true
		    },
		    resetForm() {
		    	this.competition_format.age_category_name = '';
		    	this.competition_format.category_age = '';
		    	this.competition_format.pitch_size = '';
 		    	this.competition_format.category_age_color = '';
		    	this.competition_format.category_age_font_color = '';
		    	this.clearErrorMsgs();
		    },
		    closeModal() {
		    	$('#copyAgeCategoryModal').modal('hide');
		    	this.isInvalid = false;
		    	this.resetForm();
		    }
		}
	}
</script>