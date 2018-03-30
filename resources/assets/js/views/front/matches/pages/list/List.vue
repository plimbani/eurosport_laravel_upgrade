<template>
  <div>
    <div>
        <div class="row align-items-center custom_radio_btn" v-if="currentView == 'Matches'">
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-5 col-xl-5">
                        <label class="label-of-input font-weight-bold mb-0">{{ $t('matches.match_overview') }}:</label>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-7 col-xl-7">
                        <label class="custom_select_box d-block mb-0" for="match_overview">
                            <select id="match_overview" class="border-0" v-on:change="onMatchDateChange()" v-model="matchDate">
                                <option value=""> {{ $t('matches.all_dates') }}</option>
                                <option v-for="date in tournamentDates" v-bind:value="date">{{ date | formatDate }}</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-lg-4 col-xl-4 my-2 my-lg-0 my-xl-0">
                <label class="label-of-input font-weight-bold mb-0">{{ $t('matches.filter_by') }}:</label>
                <div class="radio d-inline-block">
                    <input type="radio" v-model="filterBy" id="filter_category_and_competition" value="category_and_competition" name="match_filter" @change="getFilterOptions()">
                    <label for="filter_category_and_competition" class="d-inline-block mb-0">{{ $t('matches.category') }}</label>
                </div>
                <div class="radio d-inline-block">
                    <input type="radio" v-model="filterBy" id="filter_location" value="location" name="match_filter" @change="getFilterOptions()">
                    <label for="filter_location" class="d-inline-block mb-0">{{ $t('matches.location') }}</label>
                </div>
                <div class="radio d-inline-block">
                    <input type="radio" v-model="filterBy" id="filter_team" value="team" name="match_filter" @change="getFilterOptions()">
                    <label for="filter_team" class="d-inline-block mb-0">{{ $t('matches.team') }}</label>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 select2_override mt-0 mb-2 mt-sm-0 mt-md-2 my-lg-0 my-xl-0" v-show="filterBy == 'category_and_competition'">
                <select class="form-control js-category-and-competition">
                    <option value="">Select</option>
                    <option v-for="option in filterOptions" v-bind:data-val="setFilterOption(option)" v-bind:id="option.id" v-bind:value="setFilterOption(option)" :class="option.class">{{ option.name }}</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 select2_override mt-0 mb-2 mt-sm-0 mt-md-2 my-lg-0 my-xl-0"  v-show="filterBy != 'category_and_competition'">
              <label class="custom_select_box d-block mb-0" for="location_team_filter">
                <select id="location_team_filter" class="border-0" v-model="selectedOption" @change="setFilterOptions()">
                  <option value="">Select</option>
                  <option :value="option.id" v-for="option in filterOptions" v-bind:value="option">{{ option.name }}</option>
                </select>
              </label>
            </div>
            <div class="col-4 col-sm-2 col-md-2 col-lg-1 col-xl-1"> 
                <a href="javascript:void(0)" class="btn btn-primary btn-block" @click="clearFilter()">{{ $t('matches.clear') }}</a>
            </div>
        </div>
    </div>
    <component :is="currentView" :matches="matches" :competitionDetail="competitionDetail" :currentView="currentView"></component>
  </div>
</template>

<script type="text/babel">
  import MatchList from '../../../../../api/frontend/matchlist.js';
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
        $('.js-category-and-competition').select2().val(null).trigger("change");
        this.filterBy = 'category_and_competition';
        this.getFilterOptions();
      },
      getFilterOptions() {
        if ($('.js-category-and-competition').data('select2')) {
          $('.js-category-and-competition').select2('destroy');
        }
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
