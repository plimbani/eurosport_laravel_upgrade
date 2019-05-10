<template>
	<div class="form-group justify-content-between row">
		<div class="col-sm-6">
			<label class="px-0 pt-0 form-control-label"><strong>{{$lang.additional_page}}</strong></label>
			<div class="draggable--section">
				<draggable v-model="pages" :options="{draggable:'.additional-page-item', handle: '.additional-page-handle'}">
					<div class="draggable--section-card additional-page-item" v-for="(page, index) in pages" :key="index"  v-if="isPageEnabled(page.name)">
						<div class="draggable--section-card-header">
							<div class="draggable--section-card-header-panel">
				  			<div class="d-flex align-items-center">
					        	<div class="draggable--section-card-header-panel-text-area">
					        		<div>{{ page.title }}</div>
					  			</div>
				  			</div>
				  			<div class="draggable--section-card-header-icons">
										<a v-if="isAdmin" class="text-primary" href="javascript:void(0)"
						        	@click="deletePage(index)">
						        	<i class="fas fa-trash"></i>
						        </a>
						        <a class="text-primary" href="javascript:void(0)"
						        	@click="editPage(page, index)">
						        	<i class="fas fa-pencil"></i>
						        </a>
						        <a v-if="isAdmin" class="text-primary additional-page-handle draggable-handle" href="javascript:void(0)">
						        	<i class="fas fa-bars"></i>
						        </a>
				  			</div>
							</div>
						</div>
					</div>
				</draggable>
			</div>
			<p class="pt-0 text-muted" v-show="pages.length === 0">{{ $lang.no_additional_page_title }}</p>
		</div>
		<div class="col-sm-6" v-show="currentPageOperation == 'edit' || isAdmin">
			<div class="row">
		  	<label class="col-sm-12 pt-0 form-control-label">{{$lang.page_title}}*</label>
		  	<div class="col-sm-12">
		  		<input type="text" class="form-control" v-model="additional_page.title" name="additional_page_title" data-vv-as="page title" v-validate="'required'" :class="{'is-danger': errors.has('tournament_name') }">
					<i v-show="errors.has('additional_page_title')" class="fas fa-warning"></i>
					<span class="help is-danger" v-show="errors.has('additional_page_title')">{{ errors.first('additional_page_title') }}</span>
		  	</div>
	  	</div>
			<div class="row">
		  	<label class="col-sm-12 no-padding form-control-label">{{$lang.page_content}}*</label>
		  	<div class="col-sm-12">
		  		<insert-text-editor :pageContentValidation="pageContentValidation" :validationFieldName="'page content'" :id="'additional_page_content'" :value="additional_page.content" @setEditorValue="setAdditionalPageContent"></insert-text-editor>
		  	</div>
		  	<div class="col-sm-12 mt-4" v-if="currentPageOperation == 'add' && isAdmin">
		  		<button type="button" class="btn btn-primary" @click="saveAdditionalPage()">{{$lang.add_additional_page_btn}}</button>
		  	</div>
		  	<div class="col-sm-6 mt-4" v-if="currentPageOperation == 'edit'">
		  		<button type="button" class="btn btn-primary" @click="updatePage()">{{$lang.update_additional_page_btn}}</button>
		  		<button v-if="isAdmin" type="button" class="btn btn-primary" @click="cancelPage()">{{$lang.cancel_additional_page_btn}}</button>
		  	</div>
	  	</div>
	  </div>
	</div>
</template>
<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import InsertTextEditor from '../components/InsertTextEditor/InsertTextEditor.vue';

	export default {
		components: {
			draggable,
			InsertTextEditor
		},
		props: ['additional_pages'],
		data() {
			return {
				additional_page: {
					id: '',
					title: '',
					content: '',
				},
				currentPageIndex: -1,
				pages: [],
				currentPageOperation: 'add',
				parent_id: null,
				pageContentValidation: {
					required: true
				}
			};
		},
		computed: {
		},
		mounted() {
			this.pages = this.additional_pages;
			this.$root.$on('getAdditionalPages', this.getAdditionalPages);
			this.$root.$on('setPages', this.setPages);
		},
		beforeCreate: function() {
			// Remove custom event listener 
			this.$root.$off('getAdditionalPages');
			this.$root.$off('setPages');
		},
		methods: {
			setAdditionalPageContent(content) {
				this.additional_page.content = content;
			},
			getWebsiteId() {
				return this.$store.state.Website.id;
			},
			saveAdditionalPage() {
				this.currentPageOperation = 'add';
				this.$root.$emit('getEditorValue');
				this.$validator.validateAll().then(
				(response) => {
					if(response) {
						this.pages.push({'id': this.additional_page.id, 'title': this.additional_page.title, 'content': this.additional_page.content});
						this.getAdditionalPages();
						this.resetAdditionalPageDetail();
					}
				},
				(error) => {

				})
			},
			resetAdditionalPageDetail() {
				this.additional_page.id = '';
				this.additional_page.title = '';
				this.additional_page.content = '';
				this.$root.$emit('blankEditorValue', 'additional_page_content');
				this.clearErrorMsgs();
			},
			editPage(page, index) {
				this.currentPageOperation = 'edit';
				this.currentPageIndex = index;
				this.additional_page.id = page.id;
				this.additional_page.title = page.title;
				this.additional_page.content = page.content;
				this.clearErrorMsgs();
			},
			updatePage() {
				this.$root.$emit('getEditorValue');
				this.$validator.validateAll().then(
				(response) => {
					if(response) {
						this.pages[this.currentPageIndex].title = this.additional_page.title;
						this.pages[this.currentPageIndex].content = this.additional_page.content;
						this.currentPageOperation = 'add';
						this.getAdditionalPages();
						this.resetAdditionalPageDetail();
					}
				},
				(error) => {
				});
			},
			deletePage(deleteIndex) {
				if(deleteIndex == this.currentPageIndex && this.currentPageOperation == 'edit') {
					this.currentPageOperation = 'add';
					this.resetAdditionalPageDetail();
				}
				this.pages = _.remove(this.pages, function(stat, index) {
					return index != deleteIndex;
				});
				this.getAdditionalPages();
			},
			cancelPage() {
				this.currentPageOperation = 'add';
				this.resetAdditionalPageDetail();
				this.clearErrorMsgs();
			},
			getAdditionalPages() {
        this.$emit('setAdditionalPages', this.pages);
      },
      setPages(pages) {
      	this.pages = pages;
      	this.clearErrorMsgs();
      },
		}
	}
</script>
