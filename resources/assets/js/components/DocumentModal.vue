<template>
	<div class="modal" id="document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentDocumentOperation == 'add' ? $lang.add_an_image : $lang.edit_image }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('file') }">
            <label class="col-sm-5 form-control-label">{{ $lang.add_file }}*</label>
            <div class="col-sm-6">
              <img :src="getFile" width="100px" height="100px"/>
              <button type="button" class="btn btn-default" @click="selectFile()">{{$lang.tournament_tournament_choose_button}}</button>
              <input type="file" id="file" style="display:none;" @change="onDocumentChange">
              <input type="hidden" v-model="formValues.file" name="file" v-validate="'required'" />
              <span class="help is-danger" v-show="errors.has('file')">{{ errors.first('file') }}</span>
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
		props: ['currentDocumentOperation'],
		data() {
			return {
				formValues: {
					id: '',
					file: '',
				},
			};
		},
		created() {
	    this.$root.$on('setDocumentData', this.setDocumentData);
	  },
	  computed: {
	  	getFile() {
	  		return this.formValues.file == '' ? 'http://placehold.it/250x250?text=noimage' : this.formValues.file;
	  	},
	  },
		methods: {
			validateForm() {
				this.$validator.validateAll().then((response) => {
					if(response) {
						if(this.currentDocumentOperation == 'add') {
							this.$emit('storeDocument', this.formValues);
						} else {
							this.$emit('updateDocument', this.formValues);
						}
					}
				}).catch(() => {
					// fail stuff
				});
			},
			setDocumentData(documentData) {
				this.formValues.id = documentData.id;
				this.formValues.file = documentData.file;
				this.errors.clear();
			},
			selectFile() {
				$('#file').trigger('click');
			},
			onDocumentChange(e) {
				var vm = this;
				var files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return;

		    if(Plugin.ValidateImageType(files[0]) == false) {
	        toastr['error']('Document is not a valid file type', 'Error');
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