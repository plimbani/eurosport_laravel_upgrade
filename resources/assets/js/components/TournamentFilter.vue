
<template>
  <div>
    <div class="pull-right mt-4">
      <form  class="form-inline filter-category-form" >
        <div class="form-group">
          <label for="nameInput" class="control-label">
            <strong>{{$lang.teams_filter}}</strong>
          </label>
          <label class="radio-inline control-label">
            <input type="radio" name="filter" value="team" checked="checked"
            @click="getDropDownData('team')">{{$lang.teams_team}}
          </label>
          <label class="radio-inline control-label" v-if="section=='pitchPlanner'">
            <input type="radio" name="filter" value="location"
            @click="getDropDownData('location')">{{$lang.teams_location}}
          </label>
          <label class="radio-inline control-label" v-if="section=='teams'">
              <input type="radio" name="filter" value="country" @click="getDropDownData('country')">{{$lang.teams_country}}
          </label>
          <label class="radio-inline control-label">
              <input type="radio" name="filter" value="age_category" @click="getDropDownData('age_category')">{{$lang.teams_age}}
          </label>
          <select name="selFilter" id="selFilter" @change="setFilterValue()" class="form-control ls-select2" v-model="dropDown">
            <option value="" >{{selectMsg}}</option>
            <option :value="option.id"
            v-for="option in options"
            v-bind:value="option">
              {{option.name}}
            </option>
          </select>
          <label class="control-label">
            <a href="javascript:void(0)" @click="clearFilter()">{{$lang.teams_clear}}</a>
          </label>
        </div>
      </form>
    </div>
  </div>
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
  props:['section'],
  mounted() {
    // By Default Called with Team
    this.getDropDownData('team')
  },
  methods: {
    clearFilter(){
      // this.dropDown = ''
      // this.setFilterValue()
    },
    setFilterValue() {

      this.filterValue = this.dropDown
      let tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue }
      // this.$store.dispatch('setTournamentFilter', tournamentFilter);
      this.$root.$emit('getTeamsByTournamentFilter',this.filterKey,this.filterValue);
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
              this.selectMsg = 'Select a country'
              break
            case 'team':
              this.selectMsg = 'Select team'
              break
            case 'age_category':
              this.selectMsg = 'Select age category'
              break
            case 'location':
              this.selectMsg = 'Select a location'
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
