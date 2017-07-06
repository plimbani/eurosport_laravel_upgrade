<template>
<div>
  <div class="form-group row d-flex flex-row align-items-center">
    <label class="col-sm-1 pr-0"><h6 v-if="otherData.length != 0">{{otherData.Name}}</h6>
      <h6 class="mb-0" v-else>{{venueName}}</h6>
    </label>
    <div class="col-sm-11">
      <select class="form-control ls-select2 col-sm-4"
        v-on:change="onChangeLocation"
        v-model="location">
        <option v-for="option in locations" v-bind:value="option">{{option.venue_name}}-{{option.pitch_number}}
        </option>
      </select>
    </div>
  </div>
    <matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchList from './MatchList.vue'
import MatchListing from './MatchListing.vue'
import Tournament from '../api/tournament.js'

export default {
	props: ['matchData', 'otherData'],
	data() {
		return {
			VenueName: '',locations:[],location:''
		}
	},

  mounted() {
    // Display Location
    let TournamentId = this.$store.state.Tournament.tournamentId
    let tournamentData = {'tournamentId': TournamentId,'is_scheduled':1}

    Tournament.getFixtures(tournamentData).then(
        (response)=> {
          if(response.data.status_code == 200) {
            var uniqueArray = response.data.data.filter(function(item, pos) {
                if (!this.hasOwnProperty(item['pitchId'])) {
                    // here we set the location to current one
                  //  console.log(this.vdatapitchId)
                    return this[item['pitchId']] = true;
                }
                return false;
            }, {});
           // console.log(uniqueArray)
            this.locations = uniqueArray

            this.location = this.matchData[0]
          //  alert(JSON.stringify(uniqueNames))
            // Here we remove duplicate values
          }
        },
        (error) => {
          alert('Error in Getting Draws')
        }
      )
  },
  methods: {
    onChangeLocation() {
      // here call function to set location
      this.$root.$emit('changeComp',this.location);
    }
  },
	computed:{
		venueName() {
			if(typeof this.matchData[0].venue_name !== 'undefined' ||
        this.matchData[0].venue_name !== null)
			{
			 let venueName = this.matchData[0].venue_name + '-'+ this.matchData[0].pitch_number
			return venueName
			}
		}
	},
	components: {
		MatchList,MatchListing
	}
}
</script>
