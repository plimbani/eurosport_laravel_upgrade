<template>
 <table class="table table-hover table-striped table-bordered add-category-table">
  <thead>
      <tr>
          <th>Age category</th>
          <th>Competition format</th>
          <th>Total matches</th>
          <th>Total time</th>
          <th>Match schedule</th>
          <th>Edit</th>
      </tr>
  </thead>
  <tbody>      
      <tr v-for="(competation, index) in competationList">
          <td>Age Category</td>
          <td class="table-success">
              <div class="radio">
                  <label>
                      <input type="radio"                       
                      name="competationFormatTemplate"
                      :value="competation.tournament_template_id"
                             checked>
                      {{competation.disp_format_name}}
                  </label>
              </div>
          </td>
          <td class="table-success">{{competation.total_match}}</td>
          <td class="table-success">{{competation.total_time}}</td>
          <td class="table-success">
              <a href="#">View</a>
          </td>
          <td>
              <a href="#">Edit</a> &nbsp;&nbsp;&nbsp;
              <a href="#">Delete</a>
          </td>
      </tr>     
  </tbody>
</table>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
export default {  
  data() {
  	return {
     competationList : {}, TournamentId: 0, competation_id: ''
    }
  },

  mounted () {
  	// here we load the Competation Format data Based on tournament Id
  	this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
  	
  	// Only called if valid tournament id is Present
  	if (!isNaN(this.TournamentId)) {
  		Tournament.getCompetationFormat(this.TournamentId).then(
      (response) => {          
        this.competationList = response.data.data  
        console.log(this.competationList);
      },
      (error) => {
         console.log('Error occured during Tournament api ', error)
      }
      )
  	} else {
      this.TournamentId = 0;
    }
  },
  methods: {
    next() {
      let tournamentTemplateId = $('input[name=competationFormatTemplate]:checked').val()
      // Now here we set the template for it
      this.$store.dispatch('SetTemplate', tournamentTemplateId);
      this.$router.push({name: 'pitch_capacity'});
    }
  },
  created: function() {
    // We listen for the event on the eventHub    
    this.$root.$on('setTemplate', this.next);
  }
}
</script>