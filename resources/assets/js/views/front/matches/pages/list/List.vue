<template>
  <div>
    {{ $lang.matches.match_overview }}
    <select class="form-control" v-on:change="onMatchDateChange()" v-model="matchDate">
      <option value="all">All dates</option>
      <option v-for="date in tournamentDates" v-bind:value="date">
        {{ date | formatDate }}
      </option>
    </select>
    {{ $lang.matches.filter_by }}
    <label>
      <input type="radio" v-model="filterBy" name="match_filter" value="age_category" @click="getFilterOptions('age_category')">{{ $lang.matches.category }}
    </label>
    <label>
      <input type="radio" v-model="filterBy" name="match_filter" value="location" @click="getFilterOptions('location')">{{ $lang.matches.location }}
    </label>
    <label>
      <input type="radio" v-model="filterBy" name="match_filter" value="team" @click="getFilterOptions('team')">{{ $lang.matches.team }}
    </label>
    <select>
      <option value="">{{ $lang.matches.select }}</option>
      <option v-for="option in filterOptions" v-bind:data-val="setFilterOption(option)" v-bind:id="option.id" v-bind:value="setFilterOption(option)" :class="option.class">{{ option.name }}</option>
    </select>
    <label>
      <a href="javascript:void(0)" @click="clearFilter()">{{ $lang.matches.clear }}</a>
    </label>
  </div>
</template>

<script type="text/babel">
  var moment = require('moment');
  import MatchList from '../../../../../api/matchlist.js';

  export default {
    data() {
  		return {
        matchDate: 'all',
        tournamentDates: [],
        filterOptions: [],
        filterBy: 'age_category',
      };
    },
    filters: {
      formatDate: function(date) {
        var date = moment(date,'DD/MM/YYYY');
        return moment(date).format("DD MMM YYYY");
      },
  	},
    mounted() {
      this.tournamentDates = this.getTournamentDates(tournamentData.start_date, tournamentData.end_date);
    },
    created() {
    },
    computed: {
    },
    components: {
    },
    methods: {
      getTournamentDates(startDate, endDate) {
        var dateArray = [];

        var startDate = new Date(moment(startDate, 'DD/MM/YYYY'))
        var endDate = new Date(moment(endDate, 'DD/MM/YYYY'))

        while (startDate <= endDate) {
          dateArray.push( moment(startDate).format('DD/MM/YYYY') )
          startDate = moment(startDate).add(1, 'days');
        }
        return dateArray;
      },
      onMatchDateChange() {

      },
      setFilterOption(option) {
        return JSON.stringify(option);
      },
      clearFilter() {

      },
      getFilterOptions() {
        let tournamentData = { 'tournamentId': tournamentId, 'filterBy': this.filterBy };
        Tournament.getFilterDropDownData(tournamentData).then(
          (response) => {
          },
          (error) => {
          }
        );
      },
    },
  };
</script>
