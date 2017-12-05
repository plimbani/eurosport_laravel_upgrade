<template>
  <form  class="form-inline pull-right">
    <div class="form-group">
      <label for="nameInput" class="control-label">
        <strong>{{$lang.teams_filter}}</strong>
      </label>
    </div>
    <div class="form-group">
      <select class="form-control ls-select2" v-model="filterBy" @change="getDropDownData()">
        <option value="">All</option>
        <option value="age_category">{{$lang.tournament_filter_age_category}}</option>
        <option value="location">{{$lang.teams_location}}</option>
      </select>
    </div>
    <!-- <div class="form-group">
      <label class="radio-inline control-label">
          <input type="radio" id="age_category" name="filter" value="age_category"
           @click="getDropDownData('age_category')" class="mr-2">{{$lang.tournament_filter_age_category}}
      </label>
    </div>
    <div class="form-group">
      <label class="radio-inline control-label">
        <input type="radio" id="location" name="filter" value="location"
        @click="getDropDownData('location')" class="mr-2">{{$lang.teams_location}}
      </label>
    </div> -->
    <div class="form-group" v-show="filterBy != ''">
      <select class="form-control ls-select2" v-model="dropDown" @change="setFilterValue()" style="width:200px">
        <option value="">All</option>
        <option :value="option.id"
        v-for="option in options"
        v-bind:value="option">
          {{option.name}}
        </option>
      </select>
    </div>
    <div class="form-group" v-show="filterBy == 'age_category'">
      <select class="form-control ls-select2" v-model="groups">
        <option value="">Select group</option>
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
      filterBy: '',
      dropDownData: [],
      dropDown: '',
      options:[],
      selectMsg: 'Select a Team',
      filterKey: '',
      filterValue: '',
      groups: [],
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
    // if(this.section != 'scheduleResult' ){
    //   this.getDropDownData('age_category')
    //   $('#age_category').prop("checked",true)
    // }
    // if (this.section == 'scheduleResult' ){
    //   this.getDropDownData('competation_group')
    //   this.setFilterValue()
    //   $('#competation_group').prop("checked",true)
    // }
  },
  methods: {
    clearFilter(){
      this.filterBy = ''
      this.dropDown = ''
      this.filterKey = this.filterBy
      this.filterValue = this.dropDown
      let tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue }
      this.$store.dispatch('setTournamentFilter', tournamentFilter);
      this.$root.$emit('getPitchesByTournamentFilter',this.filterKey,this.filterValue);
      //$('#age_category').trigger('click')
      //this.getDropDownData('age_category')
    },
    setFilterValue() {
      this.filterValue = this.dropDown
      let tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue }
      this.$store.dispatch('setTournamentFilter', tournamentFilter);
      this.$root.$emit('getPitchesByTournamentFilter',this.filterKey,this.filterValue);
    },
    getDropDownData() {
      if(this.filterBy == '') {
        this.clearFilter()
        return;
      }

      this.dropDown = ''
      let filterBy = this.filterBy;
      let tournamentId = this.$store.state.Tournament.tournamentId
      // Here Call method to get Tournament Data for key
      this.filterKey = filterBy
      let tournamentData = {'tournamentId':tournamentId,
      'keyData':filterBy,'type':this.section}
      Tournament.getDropDownData(tournamentData).then(
        (response) => {
          // here we fill the options
          switch(filterBy){
            case 'age_category':
              this.selectMsg = 'Select'
              break
            case 'location':
              this.selectMsg = 'Select'
              break
          }

          this.options =response.data.data
          if(filterBy == 'age_category'){
            this.dropDown = ""
            this.setFilterValue()
          }
          if(filterBy == 'location') {
            let tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue }
            this.$store.dispatch('setTournamentFilter', tournamentFilter);
          }
        },
        (error) => {
        }
      )
    }
  }
}
</script>
