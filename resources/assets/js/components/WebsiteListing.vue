<template>
	<div class="form-group row">
		<div class="col-sm-6" v-for="website in allWebsites">
		    <div class="checkbox">
		      <div class="c-input">
		          <input type="checkbox" v-bind:id="`website-${website.id}`" class="euro-checkbox" v-bind:value="website.id" v-model="websites" />
		          <label v-bind:for="`website-${website.id}`">{{ website.tournament_name }}</label>
		      </div>
		    </div>
		</div>
	</div>
</template>

<script type="text/javascript">

	import User from '../api/users.js'

    export default {
        data() {
          return {
            websites: [],
          }
        },
        props:['user', 'allWebsites'],
        created() {
          this.$root.$on('getUserWebsites', this.getUserWebsites);
          this.$root.$on('getSelectedWebsites', this.getSelectedWebsites);
        },
        beforeMount() {
          // console.log("This is before mounted")
        },
        mounted() {
          // console.log("This is mounted")
        },
        methods: {
					getUserWebsites(user) {
						if(user) {
							this.websites = [];
							User.getUserWebsites(user.id).then(
							  (response)=> {
							    this.websites = response.data
							  },
							  (error)=>{
							  }
							)
						}
          },
          getSelectedWebsites(){
          	this.$emit('setSelectedWebsites', this.websites);
          }
        }
    }
</script>