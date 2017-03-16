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
          <td>{{competation.group_name}}</td>
          <td class="table-success">
              <div class="radio">
                  <label>
                      <input type="radio"                       
                      name="competationFormatTemplate"                      
                      :value="index"
                             checked>
                      {{competation.disp_format_name}}
                  </label>
              </div>
          </td>
          <td class="table-success">{{competation.total_match}}</td>
          <td class="table-success">{{competation.total_time | formatTime}}          
          </td>
          <td class="table-success">
              <a href="#">View</a>
          </td>
          <td>
              <a href="#" @click="editCompFormat(competation.id)">Edit</a> &nbsp;&nbsp;&nbsp;
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
     competationList : {}, TournamentId: 0, competation_id: '',setTime:'',
     tournamentTemplateId: '', totalTime:''
    }
  },

  mounted () {
  	// here we load the Competation Format data Based on tournament Id
    this.displayTournamentCompetationList()
    
  },
   filters: {
    formatTime: function(time) {
      var hours = Math.floor( time / 60); 
      var minutes = Math.floor(time % 60);

      return hours+ ' Hours And '+minutes+' Minutes'
    }
  },
  methods: {
    editCompFormat(Id) {
       // Call Child Class Component Method
      this.$root.$emit('setCompetationFormatData',  Id)   
    },
    displayTournamentCompetationList () {
      
    this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
    
    // Only called if valid tournament id is Present
    if (!isNaN(this.TournamentId)) {
      // here we add data for 
      let TournamentData = {'tournament_id': this.TournamentId}
      Tournament.getCompetationFormat(TournamentData).then(
      (response) => {          
        this.competationList = response.data.data         
        // console.log(this.competationList);
      },
      (error) => {
         console.log('Error occured during Tournament api ', error)
      }
      )
    } else {
      this.TournamentId = 0;
    }
    },    
    next() {      
      let index = $('input[name=competationFormatTemplate]:checked').val()
      let tournamentTemplateId =  this.competationList[index].tournament_template_id
      let tournamentTotalTime =  this.competationList[index].total_time      
      let tournamentData  = {'tournamentTemplateId' : tournamentTemplateId,
       'totalTime':tournamentTotalTime} 
      // Now here we set the template for it
      this.$store.dispatch('SetTemplate', tournamentData);
      this.$router.push({name: 'pitch_capacity'});
    }
  },
  created: function() {
    // We listen for the event on the eventHub    
     this.$root.$on('setTemplate', this.next);
     this.$root.$on('displayCompetationList', this.displayTournamentCompetationList);
  }
}
</script>