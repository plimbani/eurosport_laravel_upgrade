
export default {
	props: ["tournamentdata"],
    mounted: function() {
            console.log('Tournament Componenet ready.')
            console.log(this.tournamentdata);
            // this.$set('', 'kamal');
            //this.tournamentdata.tournaments_name = 'kaml';
    },

    methods: {
    saveTournamentData: () => {
     // Here We have to save the data
     console.log(this.tournamentdata);
     //this.tournamentdata.tournaments_start_date = $( "#tournaments_start_date" ).val();
     //this.tournamentdata.tournament_days = $( "#tournament_days" ).val();

     var input = this.tournamentdata;      
     
     Vue.$http.post('store', input).then(function (response) {
      //console.log('Hello After');
      //console.debug(response);
      if(response.data.status_code == '200'){
        toastr.success(response.data.message, 'Success Alert', {timeOut: 6000});
        //window.location.href = '/';
      }
      //toastr.success('Tournament Added Successfully.', 'Success Alert', {timeOut: 10000});

      //window.location.href = '/';
        /*vuePitch.pitchDays = response.data.days;
        vuePitch.unAvailable = response.data.unavailable;
        vuePitch.timeSlot = response.data.timeSlot; // true
        */
      })

      .catch(function (error) {
          console.log(error);
      }); 
    	}
    }
}

