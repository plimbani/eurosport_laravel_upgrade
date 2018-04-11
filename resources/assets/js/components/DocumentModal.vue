<template>
	<div class="modal" id="document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ currentDocumentOperation == 'add' ? $lang.add_a_file : $lang.edit_file }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row" :class="{'has-error': errors.has('file') }">
            <label class="col-sm-5 form-control-label">{{ $lang.add_file }}*</label>
            <div class="col-sm-6">
            	<div class="row">
	              <div class="col-sm-12">
	              	<button :disabled="is_document_uploading" type="button" class="btn btn-default" @click="selectFile()">{{is_document_uploading ? $lang.uploading : $lang.tournament_tournament_choose_button}}</button>
	              </div>
	              <div class="col-sm-12" style="overflow-wrap: break-word">{{ formValues.file_name }}</div>
	              <input type="file" id="file" style="display:none;" @change="onDocumentChange">
	              <input type="hidden" v-model="formValues.file" name="file" v-validate="'required'" />
	              <span class="help is-danger col-sm-12" v-show="errors.has('file')">{{ errors.first('file') }}</span>
	            </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $lang.cancel_button }}</button>
          <button :disabled="is_document_uploading" type="button" class="btn btn-primary" @click="validateForm()">{{ $lang.save_button }}</button>
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
					file_name: '',
				},
				is_document_uploading: false,
			};
		},
		created() {
	    this.$root.$on('setDocumentData', this.setDocumentData);
	  },
	  beforeCreate: function() {
      // Remove custom event listener 
      this.$root.$off('setDocumentData');
    },
	  computed: {
	  	getWebsite() {
				return this.$store.state.Website.id;
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
				$('#file').val('');
				this.formValues.id = documentData.id;
				this.formValues.file = documentData.file;
				this.formValues.file_name = documentData.file_name;
				this.clearErrorMsgs();
			},
			selectFile() {
				$('#file').trigger('click');
			},
			onDocumentChange(e) {
				var vm = this;
				var files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return false;

				// Validate document size
				if(Plugin.ValidateDocumentSize(files[0], 10485760) == false) {
					toastr['error']('Document reached maximum size limit of 10MB', 'Error');
					this.formValues.file = '';
					this.formValues.file_name = '';
	        return false;
				}

				// Validate document type
		    if(Plugin.ValidateDocumentType(files[0]) == false) {
	        toastr['error']('Document is not a valid file type', 'Error');
	        this.formValues.file = '';
	        this.formValues.file_name = '';
	        return false;
	      }

				vm.is_document_uploading = true;
	      var formData = new FormData();
	      formData.append('file', files[0]);
	      formData.append('websiteId', this.getWebsite);
	      axios.post('/api/media/uploadDocument', formData).then(
		      (response)=> {
		      	vm.formValues.file = response.data;
		      	vm.formValues.file_name = response.data.substring(response.data.lastIndexOf('/')+1, response.data.length);
		      	vm.is_document_uploading = false;
		      },
		      (error)=>{
		      }
	      );
			},
		},
	};
</script>