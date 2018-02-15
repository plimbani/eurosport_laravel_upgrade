<template>
	<div class="modal" id="photo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentPhotoOperation == 'add' ? $lang.add_an_image : $lang.edit_image }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('image') }">
            <label class="col-sm-5 form-control-label">{{ $lang.image }}*</label>
            <div class="col-sm-6">
              <img :src="getImage" width="100px" height="100px"/>
              <button type="button" class="btn btn-default" @click="selectImage()">{{$lang.tournament_tournament_choose_button}}</button>
              <input type="file" id="image" style="display:none;" @change="onPhotoChange">
              <input type="hidden" v-model="formValues.image" name="image" v-validate="'required'" />
              <span class="help is-danger" v-show="errors.has('image')">{{ errors.first('image') }}</span>
            </div>
          </div>
          <div class="form-group row" :class="{'has-error': errors.has('caption') }">
            <label class="col-sm-5 form-control-label">{{ $lang.caption }}*</label>
            <div class="col-sm-6">
                <input v-model="formValues.caption" v-validate="'required'"
                :class="{'is-danger': errors.has('caption') }"
                name="caption" type="text"
                class="form-control" placeholder="Enter caption">
                <i v-show="errors.has('caption')" class="fa fa-warning"></i>
                <span class="help is-danger" v-show="errors.has('caption')">{{ errors.first('caption') }}
                </span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $lang.cancel_button }}</button>
          <button type="button" class="btn btn-primary" @click="validateForm()">{{ $lang.save_button }}</button>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import { ErrorBag } from 'vee-validate';

	export default {
		props: ['currentPhotoOperation'],
		data() {
			return {
				formValues: {
					id: '',
					caption: '',
					image: '',
				},
			};
		},
		created() {
	    this.$root.$on('setPhotoData', this.setPhotoData);
	  },
	  computed: {
	  	getImage() {
	  		return this.formValues.image == '' ? 'http://placehold.it/250x250?text=noimage' : this.formValues.image;
	  	},
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentPhotoOperation == 'add') {
							this.$emit('storePhoto', this.formValues);
						} else {
							this.$emit('updatePhoto', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setPhotoData(photoData) {
				this.formValues.id = photoData.id;
				this.formValues.caption = photoData.caption;
				this.formValues.image = photoData.image;
				this.errors.clear();
			},
			selectImage() {
				$('#image').trigger('click');
			},
			onPhotoChange(e) {
				var vm = this;
				var files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return;

		    if(Plugin.ValidateImageType(files[0]) == false) {
	        toastr['error']('Photo is not a valid image', 'Error');
	        return;
	      }

				var reader = new FileReader();
				reader.onload = (r) => {
					vm.formValues.image = r.target.result;
				};

				reader.readAsDataURL(files[0]);
			},
		},
	};
</script>