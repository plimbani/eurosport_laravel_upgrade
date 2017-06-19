
<template>
  <form  class="form-inline pull-right">
    <div class="form-group">
      <label for="nameInput" class="control-label">
        <strong>{{$lang.teams_filter}}</strong>
      </label>
    </div>
    <div class="form-group">
      <label class="radio-inline control-label" v-if="section != 'pitchPlanner'">
        <input type="radio" id="team" name="filter" value="team"
        @click="getDropDownData('team')" class="mr-2">{{$lang.teams_team}}
      </label>
    </div>
    <div class="form-group">
      <label class="radio-inline control-label" v-if="section=='pitchPlanner'">
        <input type="radio" id="location" name="filter" value="location"
        @click="getDropDownData('location')" class="mr-2">{{$lang.teams_location}}
      </label>
    </div>
    <div class="form-group">
      <label class="radio-inline control-label" v-if="section=='teams'">
          <input type="radio" id="country" name="filter" value="country" @click="getDropDownData('country')" class="mr-2">{{$lang.teams_country}}
      </label>
    </div>
    <div class="form-group">
      <label class="radio-inline control-label">
          <input type="radio" id="age_category" name="filter" value="age_category" @click="getDropDownData('age_category')" class="mr-2">{{$lang.tournament_filter_age_category}}
      </label>
    </div>
    <div class="form-group">
      <select class="form-control ls-select2" v-model="dropDown" @change="setFilterValue()" style="width:130px">
        <option value="" >Select</option>
        <option :value="option.id"
        v-for="option in options"
        v-bind:value="option">
          {{option.name}}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label class="control-label">
        <a href="javascript:void(0)" @click="clearFilter()">{{$lang.teams_clear}}</a>
      </label>
    </div>
  </form>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
export default {
  data() {
    return {
      dropDownData: [],
      dropDown: '',
      options:[],
      selectMsg: 'Select a Team',
      filterKey: 'team',
      filterValue: ''
    }
  },
  computed: {
    activePath() {
      return this.$store.state.activePath
    }
  },
  props:['section'],
  mounted() {
    // By Default Called with Team
    if(this.section != 'pitchPlanner'){
      this.getDropDownData('team')
      $('#team').prop("checked",true)
    }
    else{
      this.getDropDownData('age_category')
      $('#age_category').prop("checked",true)
    }
  },
  methods: {
    clearFilter(){
      this.dropDown = ''
      this.setFilterValue()
      $('#team').trigger('click')
      this.getDropDownData('team')
    },
    setFilterValue() {

      this.filterValue = this.dropDown

      let tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue }
      this.$store.dispatch('setTournamentFilter', tournamentFilter);
      if(this.activePath == 'teams_groups'){
        this.$root.$emit('getTeamsByTournamentFilter',this.filterKey,this.filterValue);
      }else if(this.activePath == 'pitch_planner'){
        this.$root.$emit('getTeamsByTournamentFilter',this.filterKey,this.filterValue);
        //this.$root.$emit('getPitchesByTournamentFilter',this.filterKey,this.filterValue);
      }
    },
    getDropDownData(tourament_key) {
      let tournamentId = this.$store.state.Tournament.tournamentId
      // Here Call method to get Tournament Data for key
      this.filterKey = tourament_key
      let tournamentData = {'tournamentId':tournamentId,
      'keyData':tourament_key,'type':this.section}
      Tournament.getDropDownData(tournamentData).then(
        (response) => {
          // here we fill the options
          switch(tourament_key){
            case 'country':
              this.selectMsg = 'Select'
              break
            case 'team':
              this.selectMsg = 'Select'
              break
            case 'age_category':
              this.selectMsg = 'Select'
              break
            case 'location':
              this.selectMsg = 'Select'
              break
          }
           this.options =response.data.data
        },
        (error) => {
           console.log('Error occured during Tournament api ', error)
        }
      )
    }
  }
}
</script>
