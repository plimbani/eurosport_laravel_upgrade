<template>
	<div>
		<div class="draggable--section">
			<draggable v-if="documents.length" v-model="documents" :options="{draggable:'.document-item', handle: '.document-handle'}">
		  	<div class="draggable--section-card document-item" v-for="(document, index) in documents" :key="document.id">
		  		<div class="draggable--section-card-header">
			  		<div class="draggable--section-card-header-panel">
			        <div>
			  				{{ document.file_name }}
			  			</div>
			        <div class="draggable--section-card-header-icons">
								<v-popover class="d-inline-flex copy-link" v-show="checkForUploadedDocument(document)">
	  							<a class="text-primary" href="javascript:void(0)" @click="initializeDocumentLink(document, index)">
					        	<i class="fa fa-link"></i>
					        </a>
								  <template slot="popover">
								  	<input class="tooltip-content" :class="`js-popover-content-${index}`" type="text" v-model="documentLink" />
								  </template>
								</v-popover>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deleteDocument(index)">
				        	<i class="jv-icon jv-dustbin"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editDocument(document, index)">
				        	<i class="jv-icon jv-edit"></i>
				        </a>
				      </div>
			      </div>
		      	<!-- Add child tags like draggable--section-child-1 -->
		      </div>
		    </div>
			</draggable>
			<p v-else class="help-block text-muted">{{ $lang.no_document_found }}</p>
			<button type="button" class="btn btn-primary" @click="addDocument()" v-if="documents.length < 10">{{ $lang.add_file }}</button>
			<div class="help-block mt-2">{{$lang.document_instruction}}</div>
  		<div class="help-block mt-2 pt-0">{{$lang.copy_link_instruction}}</div>
  		<button v-clipboard:copy="documentLink">Copy</button>
			<document-modal :currentDocumentOperation="currentDocumentOperation" @storeDocument="storeDocument" @updateDocument="updateDocument"></document-modal>
		</div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import DocumentModal  from  './DocumentModal.vue';
	import _ from 'lodash';
	import { VTooltip, VPopover, VClosePopover } from 'v-tooltip';
	import clipboard from 'vue-clipboard';

	export default {
		data() {
			return {
				documents: [],
				currentDocumentIndex: -1,
				currentDocumentOperation: 'add',
				documentLink: '',
			};
		},
		components: {
			draggable,
			DocumentModal,
			VPopover,
			VClosePopover,
			clipboard,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
			getDocumentPath() {
				return this.$store.state.Image.documentPath;
			},
		},
		mounted() {
			// Get all documents
			this.getAllDocuments();
			this.$root.$on('getDocuments', this.getDocuments);
		},
		methods: {
			getAllDocuments() {
				var vm = this;
				Website.getDocuments(this.getWebsite).then(
	        (response) => {
	          vm.documents = response.data.data;
	          vm.documents = _.map(response.data.data, function(document) {
						  document.file = vm.getDocumentPath + vm.getWebsite + '/' + document.file_name;
						  return document;
						});
	        },
	        (error) => {
	        }
	      );
			},
			addDocument() {
				if(this.documents.length == 10) {
					return;
				}
				var formData = {
					id: '',
					file: '',
					file_name: '',
				};
				this.currentDocumentIndex = this.documents.length;
				this.currentDocumentOperation = 'add';
				this.initializeModal(formData);
			},
			storeDocument(documentData) {
				this.documents.push({ id: '', file: documentData.file, file_name: documentData.file_name });
				$('#document_modal').modal('hide');
			},
			editDocument(document, index) {
				var formData = {
					id: document.id,
					file: document.file,
					file_name: document.file_name,
				};
				this.currentDocumentIndex = index;
				this.currentDocumentOperation = 'edit';
				this.initializeModal(formData);
			},
			updateDocument(documentData) {
				this.documents[this.currentDocumentIndex].file = documentData.file;
				this.documents[this.currentDocumentIndex].file_name = documentData.file_name;
				$('#document_modal').modal('hide');
			},
			deleteDocument(deleteIndex) {
				this.documents = _.remove(this.documents, function(document, index) {
				  return index != deleteIndex;
				});
			},
			initializeModal(formData) {
				var vm = this;
				this.$root.$emit('setDocumentData', formData);
				$('#document_modal').modal('show');
			},
			getDocuments() {
        this.$emit('setDocuments', this.documents);
      },
      initializeDocumentLink(document, index) {
      	this.documentLink = this.getDocumentPath + this.getWebsite + '/' + document.file_name;
      	setTimeout(function(){
      		$('.js-popover-content-' + index).focus();
      		$('.js-popover-content-' + index).select();
      	}, 500);
      },
      checkForUploadedDocument(document) {
      	if(document.file.indexOf(this.getDocumentPath + this.getWebsite) !== -1) {
      		return true;
      	}
      	return false;
      },
		},
	}
</script>