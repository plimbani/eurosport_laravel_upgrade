<template>
	<div class="thumb">
		<transition>
			<img v-show="isLoad" :src="image_url" :class="image_class" @load="loaded">
		</transition>
		<img class="thumb" v-show="!isLoad" src="/images/loader2.gif">
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