<template>
	<div>
		<div class="draggable--section">
			<draggable v-model="photos" :options="{draggable:'.photo-item', handle: '.photo-handle'}">
		  	<div class="draggable--section-card photo-item" v-for="(photo, index) in photos" :key="photo.id">
		  		<div class="draggable--section-card-header">
			  		<div class="draggable--section-card-header-panel">
			        <div>
			  				<img class="thumb" :src="photo.image">{{ photo.caption }}
			  			</div>
			        <div class="draggable--section-card-header-icons">
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="deletePhoto(index)">
				        	<i class="jv-icon jv-dustbin"></i>
				        </a>
				        <a class="text-primary" href="javascript:void(0)"
				        	@click="editPhoto(photo, index)">
				        	<i class="jv-icon jv-edit"></i>
				        </a>
				        <a class="text-primary photo-handle draggable-handle" href="javascript:void(0)">
				        	<i class="fa fa-bars"></i>
				        </a>
				      </div>
			      </div>
		      	<!-- Add child tags like draggable--section-child-1 -->
		      </div>
		    </div>
			</draggable>
			<button type="button" class="btn btn-primary" @click="addPhoto()">{{ $lang.add_image }}</button>
			<photo-modal :currentPhotoOperation="currentPhotoOperation" @storePhoto="storePhoto" @updatePhoto="updatePhoto"></photo-modal>
		</div>
	</div>
</template>

<script type="text/babel">
	import Website from '../api/website.js';
	import draggable from 'vuedraggable';
	import PhotoModal  from  './PhotoModal.vue';
	import _ from 'lodash';

	export default {
		data() {
			return {
				photos: [],
				currentPhotoIndex: -1,
				currentPhotoOperation: 'add',
			};
		},
		components: {
			draggable,
			PhotoModal,
		},
		computed: {
			getWebsite() {
				return this.$store.state.Website.id;
			},
			getPhotoImagePath() {
				return this.$store.state.Image.photoPath;
			},
		},
		mounted() {
			// Get all photos
			this.getPhotos();
			this.$root.$on('getPhotos', this.getPhotos);
		},
		methods: {
			getPhotos() {
				var vm = this;
				Website.getPhotos(this.getWebsite).then(
	        (response) => {
	          vm.photos = response.data.data;
	          vm.photos = _.map(response.data.data, function(photo) {
						  photo.image = vm.getPhotoImagePath + photo.image;
						  return photo;
						});
	        },
	        (error) => {
	        }
	      );
			},
			addPhoto() {
				var formData = {
					id: '',
					caption: '',
					image: '',
				};
				this.currentPhotoIndex = this.photos.length;
				this.currentPhotoOperation = 'add';
				this.initializeModal(formData);
			},
			storePhoto(photoData) {
				this.photos.push({ id: '', caption: photoData.caption, image: photoData.image });
				$('#photo_modal').modal('hide');
			},
			editPhoto(photo, index) {
				var formData = {
					id: photo.id,
					caption: photo.caption,
					image: photo.image,
				};
				this.currentPhotoIndex = index;
				this.currentPhotoOperation = 'edit';
				this.initializeModal(formData);
			},
			updatePhoto(photoData) {
				this.photos[this.currentPhotoIndex].caption = photoData.caption;
				this.photos[this.currentPhotoIndex].image = photoData.image;
				$('#photo_modal').modal('hide');
			},
			deletePhoto(deleteIndex) {
				this.photos = _.remove(this.photos, function(stat, index) {
				  return index != deleteIndex;
				});
			},
			initializeModal(formData) {
				var vm = this;
				this.$root.$emit('setPhotoData', formData);
				$('#photo_modal').modal('show');
			},
			getPhotos() {
        this.$emit('setPhotos', this.photos);
      },
		},
	}
</script>