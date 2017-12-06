<template>
  <form  class="form-inline d-flex justify-content-end">
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
      <select class="form-control ls-select2" v-model="dropDown" @change="setFilterValue()">
        <option value="">All</option>
        <option :value="option.id"
        v-for="option in options"
        v-bind:value="option">
          {{option.name}}
        </option>
      </select>
    </div>
    <div class="form-group" v-show="filterBy == 'age_category'">
      <select class="form-control ls-select2" v-model="selectedGroup" @change="setDependentFilterValue()">
        <option value="">Select group</option>
        <option :value="group.id"
        v-for="group in groups"
        v-bind:value="group.id">
          {{ filteredGroupName(group.actual_name) }}
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
      filterBy: '',
      dropDownData: [],
      dropDown: '',
      options:[],
      selectMsg: 'Select',
      filterKey: '',
      filterValue: '',
      filterDependentKey: 'group',
      filterDependentValue: '',
      groups: [],
      selectedGroup: '',
    }
  },
  computed: {
    activePath() {
      return this.$store.state.activePath
    }
  },
  props:['section'],
  mounted() {
    this.clearFilter()
  },
  methods: {
    clearFilter(){
      this.filterBy = ''
      this.dropDown = ''
      this.filterKey = this.filterBy
      this.filterValue = this.dropDown
      this.getMatchesByFilter()
    },
    setFilterValue() {
      this.filterValue = this.dropDown
      this.groups = []

      if(this.dropDown == '') {
        this.filterDependentValue = ''
      }

      if(this.filterBy == 'age_category' && this.dropDown != '') {
        this.selectedGroup = this.filterDependentValue = ''
        let tournamentId = this.$store.state.Tournament.tournamentId
        let tournamentData = {'ageGroupId': this.dropDown.id}
        Tournament.getCategoryCompetitions(tournamentData).then(
          (response) => {
            this.groups = response.data.competitions
            this.getMatchesByFilter();
          },
          (error) => {
          }
        )
      } else {
        this.getMatchesByFilter();
      }
    },
    setDependentFilterValue() {
      this.filterDependentValue = this.selectedGroup
      this.getMatchesByFilter()
    },
    getDropDownData() {
      if(this.filterBy == '') {
        this.clearFilter()
        return;
      }

      this.dropDown = ''
      this.filterDependentValue = ''
      let filterBy = this.filterBy;
      let tournamentId = this.$store.state.Tournament.tournamentId
      // Here Call method to get Tournament Data for key
      this.filterKey = filterBy
      let tournamentData = {'tournamentId':tournamentId,
      'keyData':filterBy,'type':this.section}
      Tournament.getDropDownData(tournamentData).then(
        (response) => {
          this.options = response.data.data
          this.setFilterValue()
        },
        (error) => {
        }
      )
    },
    getMatchesByFilter() {
      let tournamentFilter = {'filterKey': this.filterKey,'filterValue':this.filterValue,'filterDependentKey': this.filterDependentKey,'filterDependentValue': this.filterDependentValue}
      this.$store.dispatch('setTournamentFilter', tournamentFilter);
      this.$root.$emit('getPitchesByTournamentFilter',this.filterKey,this.filterValue,this.filterDependentKey,this.filterDependentValue);
    },
    filteredGroupName(actualGroupName) {
      let splittedName = actualGroupName.split('-');
      return splittedName[2] + '-' + splittedName[3];
    },
  }
}
</script>
