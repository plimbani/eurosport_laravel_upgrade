<template>
	<div>
		<transition>
			<img v-show="isLoad" :src="image_url" class="img-fluid" @load="loaded">
		</transition>
		<img v-show="!isLoad" src="http://www.gintell.com/webpages/lib/images/loading.gif">
	</div>
</template>

<script>
  export default  {
		data() {
			return {
				isLoad: false,
			}
		},
    props:['image_url', 'image_class'],
    methods: {
	    loaded() {
	      this.isLoad = true
	    },
    }
  }
</script>