<template>
	<div class="main-content" id="dashboardPage">
		<div class="row">
			<div class="col-md-12">
				<div class="welcome-div">
					<p>Hi Chris, Welcome to Tournament Planner. Got a question? Check the <strong> Help </strong>page or <strong> contact us </strong> for assistance.</p>
					<a href="#"><i class="fa fa-times"></i></a>
				</div>
			</div>
		</div>
		<div class="row home-content">
		  <div class="col-sm-6">
			 <div class="card">
        <div class="card-header">
          <h5 class="text-center"><strong>Manage Tournament</strong></h5>
        </div>
        <div class="card-block text-center">        
            <div class="form-group"><tournamentDropDown></tournamentDropDown></div>
              <button class="btn btn-primary col-sm-8 btn-theme" 
              @click="addNewTournament()">
              Add a new Tournament</button>                       
        </div>
    </div>
			</div>
			<div class="col-sm-6">
				<div class="card">
          <div class="card-header">
              <h5 class="text-center"><strong>Add a new User</strong></h5>
          </div>
          <div class="card-block text-center">
              <form>
              	<div class="form-group">
              		<ol class="col-sm-8 offset-2">
              			<li class="text-left">Add your edtition details</li>
              			<li class="text-left">Review</li>
              			<li class="text-left">Publish!</li>
              		</ol>
              	</div>
                <button class="btn btn-primary col-sm-8 btn-theme">Add a new User</button>
                <br>
              </form>
            <a href="" class="text-left">See tournament administrator view</a>                       
          </div>
          </div>
			</div>
		</div>		
	</div>	
</template>
<script>
import TournamentDropDown from '../../../components/TournamentDropDown.vue'
export default {    
  components : {
    TournamentDropDown
  },    
  methods : {
    addNewTournament() {     
      // we have to Dispatch there is New Tournament  
      this.$store.dispatch('SetTournamentName','Your Tournament')     
      this.$router.push({name: 'tournament_add'})      
    }
  }
}
</script>