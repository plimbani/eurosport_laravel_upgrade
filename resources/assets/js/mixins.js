// inject a handler for `myOption` custom option
Vue.mixin({
  methods: {
  	clearErrorMsgs() {
		this.$nextTick()
			.then(() => {
    	this.errors.clear();
	  	});
  	}
  }
})
new Vue({
})