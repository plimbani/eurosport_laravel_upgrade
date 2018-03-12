<template>
  <div>
    <div>
      {{ $lang.matches.match_overview }}
      <select v-on:change="onMatchDateChange()" v-model="matchDate">
        <option value="">{{ $lang.matches.all_dates }}</option>
        <option v-for="date in tournamentDates" v-bind:value="date">
          {{ date | formatDate }}
        </option>
      </select>
      {{ $lang.matches.filter_by }}
      <label>
        <input type="radio" v-model="filterBy" name="match_filter" value="category_and_competition" @click="getFilterOptions('category_and_competition')">{{ $lang.matches.category }}
      </label>
      <label>
        <input type="radio" v-model="filterBy" name="match_filter" value="location" @click="getFilterOptions('location')">{{ $lang.matches.location }}
      </label>
      <label>
        <input type="radio" v-model="filterBy" name="match_filter" value="team" @click="getFilterOptions('team')">{{ $lang.matches.team }}
      </label>
      <div>
        <select class="js-category-and-competition" v-if="filterBy == 'category_and_competition'">
          <option value="">Select</option>
          <option v-for="option in filterOptions" v-bind:data-val="setFilterOption(option)" v-bind:id="option.id" v-bind:value="setFilterOption(option)" :class="option.class">{{ option.name }}</option>
        </select>
        <select v-model="selectedOption" @change="setFilterOptions()" v-else>
          <option value="">Select</option>
          <option :value="option.id" v-for="option in filterOptions" v-bind:value="option">{{option.name}}</option>
        </select>
      </div>
      <label>
        <a href="javascript:void(0)" @click="clearFilter()">{{ $lang.matches.clear }}</a>
      </label>
    </div>
    <component :is="currentView" :matches="matches" :competitionDetail="competitionDetail" :currentView="currentView"></component>
  </div>
</template>

<script type="text/babel">
  import MatchList from '../../../../../api/matchlist.js';
  import Matches from './components/Matches.vue';
  import Competition from './components/Competition.vue';

  export default {
    data() {
  		return {
        matches: [],
        matchDate: '',
        currentView: 'Matches',
        tournamentData: tournamentData,
        tournamentDates: [],
        filterOptions: [],
        selectedOption: '',
        competitionDetail: {
          id: '',
          name: '',
          type: '',
        },
        filterBy: 'category_and_competition',
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
      this.getFilterOptions();
    },
    created() {
      this.$root.$on('showCompetitionData', this.showCompetitionData);
      this.$root.$on('showMatchesList', this.showMatchesList);
    },
    computed: {
    },
    components: {
      Matches,
      Competition,
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
        this.setFilterOptions();
      },
      setFilterOption(option) {
        return JSON.stringify(option);
      },
      clearFilter() {
        this.selectedOption = '';
        this.setFilterOptions();
        $('.js-category-and-competition').select2().val(null).trigger("change");
        this.filterBy = 'category_and_competition';
        this.getFilterOptions();
      },
      getFilterOptions() {
        let data = { 'tournamentId': tournamentData.id, 'filterBy': this.filterBy };
        this.selectedOption = '';
        MatchList.getFilterDropDownData(data).then(
          (response) => {
            this.filterOptions = response.data.options;
            if(this.filterBy == 'category_and_competition') {
              $('.js-category-and-competition').select2().val(null).trigger("change");
              var options = [];
              _.map(response.data.options, function(option) {
                options.push({ "id": option.id, "name": option.name, "class": "category", "data": option.id });
                _.map(option.competition, function(competition) {
                  let groupName = competition.name.split("-");
                  groupName = groupName.splice(2, groupName.length);
                  groupName = groupName.join('-');
                  options.push({ "id": competition.id, "name": groupName, "class":"competition", "data":competition });
                });
              });
              $('.js-category-and-competition').select2({
                minimumResultsForSearch: Infinity,
              });
              var vm =this;
              $('.js-category-and-competition').on("select2:select", function (e) {
                var selectedVal = $(this).val();
                vm.selectedOption = selectedVal != '' ? JSON.parse(selectedVal) : '';
                vm.setFilterOptions();
              });
              this.filterOptions = options;
            }
            this.setFilterOptions();
          },
          (error) => {
          }
        );
      },
      setFilterOptions() {
        var matchFilterKey = this.filterBy;

        // var tournamentFilter = {'filterKey': this.filterBy, 'filterValue': this.selectedOption}
        // this.$store.dispatch('setTournamentFilter', tournamentFilter);

        if(this.filterBy === 'category_and_competition') {
          if(this.selectedOption == '') {
            matchFilterKey = '';
          } else if(this.selectedOption.class == 'category'){
            matchFilterKey = 'competation_group_age';
          } else {
            matchFilterKey = 'competation_group';
          }
        }

        this.getAllMatches(matchFilterKey, this.selectedOption);
      },
      getAllMatches(filterKey, filterValue) {
        $("body .js-loader").removeClass('d-none');
  			var tournamentId = tournamentData.id;
  			var data = {};

        data.tournamentId = tournamentId;
        data.fiterEnable = true;
        this.matchDate != '' ? data.tournamentDate = this.matchDate : null;

  			if(filterKey != '' && filterValue != '') {
          data.filterKey = filterKey;
          data.filterValue = filterValue.id;
  	    }

  			let vm = this;
  			MatchList.getFixtures(data).then(
  				(response)=> {
  					$("body .js-loader").addClass('d-none');
            vm.matches = response.data.data;
            vm.matches = _.orderBy(vm.matches, ['match_datetime'], ['asc']);
            vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
  				},
  				(error) => {
  					console.log('Error in getting matches.');
  				}
  			)
      },
      showCompetitionData(id, competitionName, competitionType) {
        this.currentView = 'Competition';
        this.getCompetitionDetails(id, competitionName, competitionType);
      },
      getCompetitionDetails(competitionId, competitionName, competitionType) {
        var tournamentId = tournamentData.id;
  			var data = {'tournamentId': tournamentId, 'competitionId': competitionId};
        var vm = this;

  			this.competitionDetail.name = competitionName;
  			this.competitionDetail.id = competitionId;
        this.competitionDetail.type = competitionType;

  			MatchList.getFixtures(data).then(
  				(response)=> {
  					if(response.data.status_code == 200) {
  						vm.matches = response.data.data;
              vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
  					}
  				},
  				(error) => {
  					alert('Error in getting matches');
  				}
  			)
      },
      showMatchesList() {
        this.currentView = 'Matches';
        this.getFilterOptions();
      }
    },
  };
</script>
