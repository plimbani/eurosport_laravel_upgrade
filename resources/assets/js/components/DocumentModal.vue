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
	              	<button type="button" class="btn btn-default" @click="selectFile()">{{$lang.tournament_tournament_choose_button}}</button>
	              </div>
	              <div class="col-sm-12">{{ formValues.file_name }}</div>
	              <input type="file" id="file" style="display:none;" @change="onDocumentChange">
	              <input type="hidden" v-model="formValues.file" name="file" v-validate="'required'" />
	              <span class="help is-danger col-sm-12" v-show="errors.has('file')">{{ errors.first('file') }}</span>
	            </div>
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
					file_name: '',
				},
			};
		},
		created() {
	    this.$root.$on('setDocumentData', this.setDocumentData);
	  },
	  computed: {
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
				var that = this;
				$('#file').val('');
				this.formValues.id = documentData.id;
				this.formValues.file = documentData.file;
				this.formValues.file_name = documentData.file_name;
				Vue.nextTick(function () {
					that.errors.clear();
				});
			},
			selectFile() {
				$('#file').trigger('click');
			},
			onDocumentChange(e) {
				var vm = this;
				var files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return;

				if(Plugin.ValidateDocumentSize(files[0], 10485760) == false) {
					toastr['error']('Image reached maximum size limit of 10MB', 'Error');
					this.formValues.file = '';
					this.formValues.file_name = '';
	        return;
				}

		    if(Plugin.ValidateDocumentType(files[0]) == false) {
	        toastr['error']('Document is not a valid file type', 'Error');
	        this.formValues.file = '';
	        this.formValues.file_name = '';
	        return;
	      }

				var reader = new FileReader();
				reader.onload = (r) => {
					vm.formValues.file = r.target.result;
				};

				var filename = $('#file').val();
	      var lastIndex = filename.lastIndexOf('\\');
	      if (lastIndex >= 0) {
	        filename = filename.substring(lastIndex + 1);
	      }
	      this.formValues.file_name = filename;

				reader.readAsDataURL(files[0]);
			},
		},
	};
</script>