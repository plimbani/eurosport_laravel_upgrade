<template>
	<div class="form-group row">
		<div class="col-sm-6" v-for="tournament in allTournaments">
		    <div class="checkbox">
		      <div class="c-input">
		          <input type="checkbox" v-bind:id="`tournament-${tournament.id}`" class="euro-checkbox" v-bind:value="tournament.id" v-model="tournaments" />
		          <label v-bind:for="`tournament-${tournament.id}`">{{ tournament.name }} ({{tournament.status}})</label>
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
        tournaments: [],
      }
    },
    props:['user', 'allTournaments'],
    created() {
      this.$root.$on('getUserTournaments', this.getUserTournaments);
      this.$root.$on('getSelectedTournaments', this.getSelectedTournaments);
    },
    beforeMount() {
    },
    mounted() {
    },
    methods: {
			getUserTournaments(user) {
				if(user) {
					this.tournaments = [];
					User.getUserTournaments(user.id).then(
					  (response)=> {
					    this.tournaments = response.data
					  },
					  (error)=>{
					  }
					)
				}
      },
      getSelectedTournaments(){
      	this.$emit('setSelectedTournaments', this.tournaments);
      }
    }
  }
</script>