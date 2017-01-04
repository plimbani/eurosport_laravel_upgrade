export default {
	props: ["tournamentdata"],
    mounted() {
            console.log('Tournament Componenet ready.')
            console.log(this.tournamentdata);
            // this.$set('', 'kamal');
            //this.tournamentdata.tournaments_name = 'kaml';
    },

    methods: {
      saveTournamentData()  {
      // Here We have to save the data     
      this.tournamentdata.tournaments_start_date = $( "#tournaments_start_date" ).val();
      this.tournamentdata.tournament_days = $( "#tournament_days" ).val();

      var input = this.tournamentdata;      
      Vue.$http.post('store', input).then(
        response => {
          //this.item.title = '';
          //this.item.description = '';
          //if(response.data.status_code == '200'){
            console.log(this.tournamentdata);
            toastr.success(response.data.message, 'Success Alert', {timeOut: 6000});
          //}
      })

      .catch(function (error) {
          console.log(error);
      }); 

    	}
   }
}

