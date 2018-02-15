<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="documents" :options="{draggable:'.document-item', handle: '.document-handle'}">
		  	<div class="draggable--section-card document-item" v-for="(document, index) in documents" :key="document.id">
		  		<div class="draggable--section-card-header">
			  		<div class="draggable--section-card-header-panel">
			        <div>
			  				{{ document.name }}
			  			</div>
			        <div class="draggable--section-card-header-icons">
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deleteDocument(index)">
				        	<i class="jv-icon jv-dustbin"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editDocument(document, index)">
				        	<i class="jv-icon jv-edit"></i>
				        </a>
				        <a class="text-primary document-handle draggable-handle" href="javascript:void(0)">
				        	<i class="fa fa-bars"></i>
				        </a>
				      </div>
			      </div>
		      	<!-- Add child tags like draggable--section-child-1 -->
		      </div>
		    </div>
			</draggable>
			<button type="button" class="btn btn-primary" @click="addDocument()">{{ $lang.add_file }}</button>
			<document-modal :currentDocumentOperation="currentDocumentOperation" @storeDocument="storeDocument" @updateDocument="updateDocument"></document-modal>
		</div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import DocumentModal  from  './DocumentModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				documents: [],
				currentDocumentIndex: -1,
				currentDocumentOperation: 'add',
			};
		},
		components: {
			draggable,
			DocumentModal,
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
			this.getDocuments();
			this.$root.$on('getDocuments', this.getDocuments);
		},
		methods: {
			getDocuments() {
				var vm = this;
				Website.getDocuments(this.getWebsite).then(
	        (response) => {
	          vm.documents = response.data.data;
	          vm.documents = _.map(response.data.data, function(document) {
						  document.file = vm.getDocumentPath + document.file;
						  return document;
						});
	        },
	        (error) => {
	        }
	      );
			},
			addDocument() {
				var formData = {
					id: '',
					file: '',
				};
				this.currentDocumentIndex = this.documents.length;
				this.currentDocumentOperation = 'add';
				this.initializeModal(formData);
			},
			storeDocument(documentData) {
				this.documents.push({ id: '', file: documentData.file });
				$('#document_modal').modal('hide');
			},
			editDocument(document, index) {
				var formData = {
					id: document.id,
					file: document.file,
				};
				this.currentDocumentIndex = index;
				this.currentDocumentOperation = 'edit';
				this.initializeModal(formData);
			},
			updateDocument(documentData) {
				this.documents[this.currentDocumentIndex].file = documentData.file;
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
		},
	}
</script>