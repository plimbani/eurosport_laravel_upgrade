<template>
	<div class="main-content container" id="dashboardPage">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-info alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Hi Chris, Welcome to Tournament Planner. Got a question? Check the <strong> Help </strong>page or <strong> contact us </strong>        for assistance.
        </div>
      </div>
    </div>
    <div class="row home-content">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <h5 class="text-center"><strong>Manage Edition</strong></h5>
          </div>
          <div class="card-block text-center">
            <form>
              <div class="form-group">
                <select class="form-control ls-select2 col-sm-8 offset-sm-2">
                    <option value="">Select an existing edition</option>
                    <option value="">--------------</option>
                    <option value="">Copa Costa Brava 2017 (Unpublished)</option>
                    <option value="">Copa Costa Brava 2016 (Closed)</option>
                    <option value="">Copa Costa Brava 2015 (Closed)</option>
                </select>
              </div>
              <button class="btn btn-primary col-sm-8 btn-theme">Add a new edition</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h5 class="text-center"><strong>Add a new Tournament</strong></h5>
        </div>
        <div class="card-block text-center">
          <form>
            <div class="form-group">
              <ol class="col-sm-8 offset-sm-2">
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
  mounted() {
    // Here we set Default Value For Tournament
    let tournamentAdd  = {name:'Welcome', 'currentPage':'Home'}        
    this.$store.dispatch('SetTournamentName', tournamentAdd)
  },
  methods : {
    addNewTournament() {     
      // we have to Dispatch there is New Tournament 
      let tournamentAdd  = {name:'Your Tournament', 'currentPage':'TournamentAdd'}        
      this.$store.dispatch('SetTournamentName', tournamentAdd)      
      this.$router.push({name: 'tournament_add'})      
    }
  }
}
</script>